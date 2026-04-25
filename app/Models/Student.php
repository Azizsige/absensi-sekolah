<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // Relasi: Siswa ini punya akun User (untuk login)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi: Siswa ini ada di satu Kelas
    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    // Relasi: Satu Siswa punya banyak riwayat Absen
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    // Relasi: Satu Siswa bisa ngajuin banyak Surat Izin/Sakit
    public function leaveRequests()
    {
        return $this->hasMany(LeaveRequest::class);
    }
}
