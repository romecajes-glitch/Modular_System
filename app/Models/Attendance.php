<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = [
        'enrollment_id',
        'session_number',
        'session_date',
        'start_time',
        'end_time',
        'status',
        'or_number',
        'amount_paid',
        'notes',
        'marked_by_user_id'
    ];

    protected $casts = [
        'session_date' => 'date',
    ];

    // Relationship with enrollment
    public function enrollment()
    {
        return $this->belongsTo(Enrollment::class);
    }

    // Relationship with user who marked attendance
    public function markedByUser()
    {
        return $this->belongsTo(User::class, 'marked_by_user_id');
    }

    // Scope for present attendance
    public function scopePresent($query)
    {
        return $query->where('status', 'present');
    }

    // Scope for absent attendance
    public function scopeAbsent($query)
    {
        return $query->where('status', 'absent');
    }

    // Get session duration in minutes
    public function getDurationAttribute()
    {
        if ($this->start_time && $this->end_time) {
            $start = \Carbon\Carbon::parse($this->start_time);
            $end = \Carbon\Carbon::parse($this->end_time);
            return $start->diffInMinutes($end);
        }
        return 0;
    }

    // Check if session is completed
    public function getIsCompletedAttribute()
    {
        return $this->status !== null;
    }
}
