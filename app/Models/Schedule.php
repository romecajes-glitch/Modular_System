<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = [
        'program_id',
        'instructor_id',
        'day',
        'start_time',
        'end_time'
    ];

    // Define relationship with programs
    public function program()
    {
        return $this->belongsTo(Program::class, 'program_id');
    }

    // Define relationship with instructor (User model)
    public function instructorUser()
    {
        return $this->belongsTo(\App\Models\User::class, 'instructor_id');
    }
}
