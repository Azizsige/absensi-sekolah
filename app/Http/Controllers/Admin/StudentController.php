<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Classroom; // 1. Tambahin import model Classroom
use Illuminate\Http\Request;
use Inertia\Inertia;

class StudentController extends Controller
{
    public function index()
    {
        $students = Student::with(['classroom.major'])->latest()->paginate(10);

        // 2. Ambil semua data kelas sekalian sama nama jurusannya
        $classrooms = Classroom::with('major')->get();

        return Inertia::render('Admin/Student/Index', [
            'students' => $students,
            'classrooms' => $classrooms // 3. Kirim ke Vue
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nisn' => 'required|digits:10|unique:students,nisn',
            'name' => 'required|string|max:255',
            'gender' => 'required|in:L,P',
            'classroom_id' => 'required|exists:classrooms,id',
        ]);

        Student::create($validated);

        // Balikin ke halaman sebelumnya (Inertia bakal otomatis update datanya tanpa refresh)
        return redirect()->back();
    }

    // FUNGSI UBAH DATA (UPDATE)
    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            // NISN boleh sama dengan miliknya sendiri saat diedit, makanya ada exception id
            'nisn' => 'required|digits:10|unique:students,nisn,' . $student->id,
            'name' => 'required|string|max:255',
            'gender' => 'required|in:L,P',
            'classroom_id' => 'required|exists:classrooms,id',
        ]);

        $student->update($validated);

        return redirect()->back();
    }

    // FUNGSI HAPUS DATA (DELETE)
    public function destroy(Student $student)
    {
        $student->delete();
        return redirect()->back();
    }
}
