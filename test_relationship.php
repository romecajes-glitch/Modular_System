<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Enrollment;

try {
    $enrollment = Enrollment::with('user')->first();

    if ($enrollment) {
        echo "Enrollment ID: " . $enrollment->id . "\n";
        echo "Student ID: " . $enrollment->student_id . "\n";

        if ($enrollment->user) {
            echo "User found: " . $enrollment->user->name . "\n";
        } else {
            echo "No user relationship found\n";
        }
    } else {
        echo "No enrollments found\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
