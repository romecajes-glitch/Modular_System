<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Certificate;

class Enrollment extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id', 'first_name', 'last_name', 'middle_name', 'suffix_name', 'birthdate', 'age', 'gender', 'email', 'phone',
        'address', 'citizenship', 'religion', 'place_of_birth', 'civil_status', 'spouse_name',
        'father_name', 'mother_name', 'guardian', 'guardian_contact', 'program_id', 'recruiter',
        'photo', 'parent_consent', 'status', 'batch_number', 'or_number', 'completion_date', 'rejection_reason',
        'approved_at', 'approved_by', 'rejected_at', 'rejected_by', 'qr_pin', 'paid_sessions', 'registration_fee_paid',
        'is_re_enrollment', 'original_enrollment_id', 're_enrollment_date'
    ];
    
    protected $attributes = [
        'status' => 'pending'
    ];

    // Relationship to User
    public function user()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    // Relationship to original enrollment (for re-enrollments)
    public function originalEnrollment()
    {
        return $this->belongsTo(Enrollment::class, 'original_enrollment_id');
    }

    // Relationship to re-enrollments (for original enrollments)
    public function reEnrollments()
    {
        return $this->hasMany(Enrollment::class, 'original_enrollment_id');
    }

    // Full name accessor
    public function getFullNameAttribute()
    {
        $name = $this->first_name . ' ';
        if ($this->middle_name) {
            $name .= $this->middle_name . ' ';
        }
        $name .= $this->last_name;
        if ($this->suffix_name) {
            $name .= ' ' . $this->suffix_name;
        }
        return trim($name);
    }

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_ENROLLED = 'enrolled';
    const STATUS_REJECTED = 'rejected';
    const STATUS_COMPLETED = 'completed';

    // Scope for enrolled students (includes both approved and enrolled statuses)
    public function scopeEnrolled($query)
    {
        return $query->whereIn('status', [self::STATUS_APPROVED, self::STATUS_ENROLLED]);
    }

    // Scope for completed students (for certificates)
    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED)->whereNotNull('completion_date');
    }

    // Check if student is enrolled (approved or enrolled status)
    public function isEnrolled()
    {
        return in_array($this->status, [self::STATUS_APPROVED, self::STATUS_ENROLLED]);
    }

    // Check if student is eligible for certificate
    public function isEligibleForCertificate()
    {
        return $this->status === self::STATUS_COMPLETED && !is_null($this->completion_date);
    }

    // Relationship to Program
    public function program()
    {
        return $this->belongsTo(Program::class, 'program_id');
    }

    // Relationship to Attendance records
    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'enrollment_id');
    }

    // Relationship to Certificate
    public function certificate()
    {
        return $this->hasOne(Certificate::class, 'enrollment_id');
    }
}