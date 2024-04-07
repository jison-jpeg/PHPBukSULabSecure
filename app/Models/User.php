<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = "users";

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'rfid_number',
        'first_name',
        'middle_name',
        'last_name',
        'username',
        'role',
        'email',
        'college_id',
        'department_id',
        'section_code',
        'birthdate',
        'phone',
        'password',
    ];

    // get full name
    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->middle_name} {$this->last_name}";
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Get the college that owns the user.
     */
    public function college()
    {
        return $this->belongsTo(College::class);
    }

    /**
     * Get the department that owns the user.
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Get the subjects for the user.
     */
    public function subjects()
    {
        return $this->belongsToMany(Subject::class);
    }


    /**
     * Get the logs for the user.
     */
    public function logs()
    {
        return $this->hasMany(Logs::class);
    }

    /**
     * Get the user's full name.
     *
     * @return string
     */

    public function getFullName()
    {
        return "{$this->last_name} {$this->first_name} {$this->middle_name}";
    }

    /**
     * Get the user's ID formatted as an instructor ID.
     *
     * @return string
     */
    public function getInstructorIdAttribute()
    {
        return "f-" . str_pad($this->id, 5, '0', STR_PAD_LEFT);
    }
}
