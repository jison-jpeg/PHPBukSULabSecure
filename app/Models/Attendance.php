<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $table = 'attendances';

    protected $fillable = [
        'user_id',
        'laboratory_id',
        'subject_id',
        'schedule_id',
        'time_in',
        'time_out',
        'percentage',
        'status',
    ];

    /**
     * Get the user that owns the attendance.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the laboratory that the attendance belongs to.
     */
    public function laboratory()
    {
        return $this->belongsTo(Laboratory::class);
    }

    /**
     * Get the subject that the attendance belongs to.
     */
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * Get the schedule that the attendance belongs to.
     */
    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }
}
