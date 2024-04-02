<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceExit extends Model
{
    use HasFactory;

    protected $table = 'attendance_exits';

    protected $fillable = [
        'attendance_id',
        'time_out',
    ];

    public function attendance()
    {
        return $this->belongsTo(Attendance::class);
    }
}