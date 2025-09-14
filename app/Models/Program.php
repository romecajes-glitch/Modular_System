<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'duration',
        'description',
        'price_per_session',
        'registration_fee',
        'status'
    ];

    protected $casts = [
        'duration' => 'integer',
        'price_per_session' => 'decimal:2',
        'registration_fee' => 'decimal:2',
    ];

    // Define relationship with enrollments
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class, 'program_id');
    }
    
    // Define relationship with schedules
    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
}
