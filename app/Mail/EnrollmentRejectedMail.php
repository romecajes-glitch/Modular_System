<?php
namespace App\Mail;

use App\Models\Enrollment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EnrollmentRejectedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $enrollment;
    public $reason;

    public function __construct(Enrollment $enrollment, $reason)
    {
        $this->enrollment = $enrollment;
        $this->reason = $reason;
    }

    public function build()
    {
        return $this->subject('Your Enrollment Has Been Rejected')
            ->view('emails.enrollment_rejected');
    }
}