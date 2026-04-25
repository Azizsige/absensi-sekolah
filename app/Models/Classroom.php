<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // Relasi: Kelas ini milik satu Jurusan
    public function major()
    {
        return $this->belongsTo(Major::class);
    }

    // Relasi: Satu Kelas punya banyak Siswa
    public function students()
    {
        return $this->hasMany(Student::class);
    }
}
