<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$email = 'geromecajes123@gmail.com';

// Check if email already exists in enrollments table
$existingEnrollment = DB::table('enrollments')->where('email', $email)->first();

if ($existingEnrollment) {
    echo "Existing enrollment found for email '$email':\n";
    echo "ID: " . $existingEnrollment->id . "\n";
    echo "Name: " . $existingEnrollment->first_name . " " . $existingEnrollment->last_name . "\n";
    echo "Status: " . ($existingEnrollment->status ?? 'Pending') . "\n";
    echo "Created At: " . $existingEnrollment->created_at . "\n";
} else {
    echo "No existing enrollment found for email '$email'.\n";
}

// Also check users table again to be sure
$existingUser = DB::table('users')->where('email', $email)->first();
if ($existingUser) {
    echo "User account already exists for email '$email':\n";
    echo "User ID: " . $existingUser->id . "\n";
    echo "Username: " . $existingUser->username . "\n";
} else {
    echo "No user account found for email '$email'.\n";
}
?>
