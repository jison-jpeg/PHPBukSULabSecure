<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entrance extends Model
{
    use HasFactory;

    protected $table = 'entrances';

    protected $fillable = [
        'attendance_id',
        'time_in',
    ];

    public function attendance()
    {
        return $this->belongsTo(Attendance::class);
    }
}