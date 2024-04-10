<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'departmentName',
        'departmentDescription',
        'college_id',
    ];

    /**
     * Get the college that owns the department.
     */
    public function college()
    {
        return $this->belongsTo(College::class);
    }

    /**
     * Get the subjects that belong to this department.
     */
    public function subjects()
    {
        return $this->hasMany(Subject::class);
    }

    /**
     * Get the sections that belong to this department.
     */
    public function sections()
    {
        return $this->hasMany(Section::class);
    }

    
}
