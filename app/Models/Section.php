<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'sectionCode',
        'sectionDescription',
        'department_id',
    ];

    /**
     * Get the department that owns the section.
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Get the students assigned to this section.
     */
    public function students()
    {
        return $this->hasMany(User::class, 'section_id');
    }

    /**
     * Get the schedules assigned to this section.
     */
    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
}
