<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Models\Student;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        // 1. Ambil semua data kelas untuk Dropdown Filter
        $classrooms = Classroom::with('major')->get();

        // 2. Tangkap filter dari URL (Kalau kosong, default tanggal hari ini)
        $selectedClassroom = $request->query('classroom_id');
        $selectedDate = $request->query('date', now()->toDateString());

        $students = [];

        // 3. Kalau admin udah milih kelas, baru kita panggil data siswanya
        if ($selectedClassroom) {
            $students = Student::where('classroom_id', $selectedClassroom)
                // JURUS MAGIC: Tarik juga data absen siswa INI khusus di TANGGAL INI aja
                ->with(['attendances' => function ($query) use ($selectedDate) {
                    $query->whereDate('date', $selectedDate);
                }])
                ->get();
        }

        return Inertia::render('Admin/Log/Index', [
            'classrooms' => $classrooms,
            'students' => $students,
            'filters' => [
                'classroom_id' => $selectedClassroom,
                'date' => $selectedDate,
            ]
        ]);
    }

    // Fungsi untuk simpan data absen massal
    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'attendances' => 'required|array',
            'attendances.*.student_id' => 'required|exists:students,id',
            'attendances.*.status' => 'required|in:present,late,alpha,leave,sick',
        ]);

        // Looping data absen yang dikirim dari Vue
        foreach ($validated['attendances'] as $data) {
            // updateOrCreate: Kalau hari ini siswa udah diabsen, update statusnya.
            // Kalau belum, bikin data baru. Ini menggantikan fungsi "Unique" di level database!
            Attendance::updateOrCreate(
                [
                    'student_id' => $data['student_id'],
                    'date' => $validated['date'],
                ],
                [
                    'status' => $data['status'],
                    'check_in' => $data['status'] === 'present' || $data['status'] === 'late' ? now()->format('H:i:s') : null,
                ]
            );
        }

        return redirect()->back();
    }
}
