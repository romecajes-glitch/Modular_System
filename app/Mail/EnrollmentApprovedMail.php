<?php
namespace App\Mail;

use App\Models\Enrollment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EnrollmentApprovedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $enrollment;
    public $username;
    public $password;

    public function __construct(Enrollment $enrollment, $username, $password)
    {
        $this->enrollment = $enrollment;
        $this->username = $username;
        $this->password = $password;
    }

    public function build()
    {
        return $this->subject('Your Enrollment Has Been Approved')
            ->view('emails.enrollment_approved');
    }
}