<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Logs extends Model
{
    use HasFactory;

    protected $table = 'logs';

    protected $fillable = [
        'user_id',
        'laboratory_id',
        'name',
        'description',
        'action',
    ];

    /**
     * Get the user that owns the log.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the laboratory that the log belongs to.
     */
    public function laboratory()
    {
        return $this->belongsTo(Laboratory::class);
    }

    /**
     * Get user's full name.
     */
    public function getFullNameAttribute()
    {
        return "{$this->user->first_name} {$this->user->middle_name} {$this->user->last_name}";
    }

}
