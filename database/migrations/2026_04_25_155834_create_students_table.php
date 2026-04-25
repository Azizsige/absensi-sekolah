<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            // Kita hubungkan ke tabel users bawaan Laravel buat login (kalau siswa butuh login)
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('classroom_id')->constrained('classrooms')->cascadeOnDelete();
            $table->string('nisn')->unique();
            $table->string('name');
            $table->enum('gender', ['L', 'P']);
            $table->string('phone')->nullable(); // Buat WA Gateway nanti

            // INI MAGIC-NYA: Buat nyimpen ID HP/Browser biar nggak bisa titip absen
            $table->string('device_id')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
