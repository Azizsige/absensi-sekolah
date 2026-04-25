<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveRequest extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // Relasi: Surat izin ini diajukan oleh satu Siswa
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
