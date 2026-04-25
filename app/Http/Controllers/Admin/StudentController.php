<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Inertia\Inertia; // Wajib di-import

class StudentController extends Controller
{
    public function index()
    {
        // Ambil data siswa, sekalian ambil data relasi kelas & jurusan
        // Kita pakai paginate() biar datanya terpotong 10 per halaman, nggak berat
        $students = Student::with(['classroom.major'])->latest()->paginate(10);

        // Kirim datanya ke halaman Vue lewat Inertia
        return Inertia::render('Admin/Student/Index', [
            'students' => $students
        ]);
    }
}
