<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

try {
    $enrollment = \App\Models\Enrollment::with(['user', 'program', 'attendances'])->find(1);

    if (!$enrollment) {
        echo "Enrollment with ID 1 not found\n";
        exit(1);
    }

    echo "Enrollment found:\n";
    echo "ID: " . $enrollment->id . "\n";
    echo "Student ID: " . $enrollment->student_id . "\n";
    echo "First Name: " . $enrollment->first_name . "\n";
    echo "Last Name: " . $enrollment->last_name . "\n";
    echo "Program: " . ($enrollment->program ? $enrollment->program->name : 'N/A') . "\n";
    echo "Attendances count: " . $enrollment->attendances->count() . "\n";

    echo "\nAttendances:\n";
    foreach ($enrollment->attendances as $attendance) {
        echo "- Session " . $attendance->session_number . ": " . $attendance->status . " (OR: " . $attendance->or_number . ")\n";
    }

    echo "\nTesting Payment query:\n";
    $payments = \App\Models\Payment::where('student_id', $enrollment->student_id)
        ->where('status', 'completed')
        ->where('session_count', '>=', 1)
        ->orderBy('payment_date', 'desc')
        ->get();

    echo "Payments found: " . $payments->count() . "\n";
    foreach ($payments as $payment) {
        echo "- Amount: " . $payment->amount . ", Session Count: " . $payment->session_count . "\n";
    }

} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "Trace:\n" . $e->getTraceAsString() . "\n";
}
