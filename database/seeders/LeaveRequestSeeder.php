<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LeaveRequest;
use App\Models\Student;
use Carbon\Carbon;

class LeaveRequestSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Cari satu siswa secara acak (Pastikan lu udah punya minimal 1 siswa di database ya!)
        $student = Student::first();

        if (!$student) {
            $this->command->warn('Waduh bro, data siswa masih kosong. Tambah 1 siswa dulu di menu Data Siswa!');
            return;
        }

        // 2. Suntik data dummy 1: Izin Sakit (2 hari)
        LeaveRequest::create([
            'student_id' => $student->id,
            'start_date' => Carbon::now()->format('Y-m-d'), // Mulai hari ini
            'end_date' => Carbon::now()->addDay()->format('Y-m-d'), // Sampai besok (2 hari)
            'type' => 'sick',
            'reason' => 'Sakit demam tinggi bro, butuh bed rest.',
            'status' => 'pending',
        ]);

        // 3. Suntik data dummy 2: Izin Biasa (1 hari)
        LeaveRequest::create([
            'student_id' => $student->id,
            'start_date' => Carbon::now()->addDays(3)->format('Y-m-d'), // 3 hari dari sekarang
            'end_date' => Carbon::now()->addDays(3)->format('Y-m-d'),
            'type' => 'leave',
            'reason' => 'Izin ada acara keluarga ke luar kota.',
            'status' => 'pending',
        ]);

        $this->command->info('Mantap! 2 Data Surat Izin berstatus Pending berhasil disuntikkan!');
    }
}
