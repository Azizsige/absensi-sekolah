<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Major;
use App\Models\Classroom;
use App\Models\Student;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Bikin Akun Admin (Bisa lu pakai buat login nanti)
        User::create([
            'name' => 'Admin TU',
            'email' => 'admin@sekolah.com',
            'password' => Hash::make('password123'),
        ]);

        // 2. Bikin Data Jurusan Tetap
        $rpl = Major::create(['name' => 'Rekayasa Perangkat Lunak', 'code' => 'RPL']);
        $tkj = Major::create(['name' => 'Teknik Komputer Jaringan', 'code' => 'TKJ']);

        // 3. Bikin Data Kelas Tetap
        Classroom::create(['major_id' => $rpl->id, 'name' => 'X RPL 1', 'level' => '10']);
        Classroom::create(['major_id' => $tkj->id, 'name' => 'X TKJ 1', 'level' => '10']);

        // 4. Nyalakan Pabrik! Generate 50 data siswa acak
        Student::factory(50)->create();
    }
}
