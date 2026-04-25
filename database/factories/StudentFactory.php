<?php

namespace Database\Factories;

use App\Models\Classroom;
use Illuminate\Database\Eloquent\Factories\Factory;

class StudentFactory extends Factory
{
    public function definition(): array
    {
        return [
            // Pilih kelas secara acak dari tabel classrooms
            'classroom_id' => Classroom::inRandomOrder()->first()->id ?? 1,
            // Bikin 10 digit angka acak untuk NISN
            'nisn' => fake()->unique()->numerify('##########'),
            // Bikin nama acak orang Indonesia (kalau app.locale di config diset id_ID, namanya jadi nama indo)
            'name' => fake()->name(),
            // Random jenis kelamin L atau P
            'gender' => fake()->randomElement(['L', 'P']),
            // Random nomor HP
            'phone' => fake()->phoneNumber(),
            // device_id dikosongin dulu karena bakal diisi otomatis pas siswa login/scan QR pertama kali (Anti-Clone)
            'device_id' => null,
        ];
    }
}
