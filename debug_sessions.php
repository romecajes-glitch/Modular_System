<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\Enrollment;
use App\Models\Program;
use App\Models\Schedule;

echo "=== DEBUGGING SESSION COUNT ISSUE ===\n\n";

// Check all enrollments
$allEnrollments = Enrollment::with(['user', 'program'])->get();
echo "Total enrollments: " . $allEnrollments->count() . "\n";

if ($allEnrollments->count() > 0) {
    echo "All enrollments:\n";
    foreach ($allEnrollments as $enrollment) {
        echo "  - ID: {$enrollment->id}, User: {$enrollment->user->name}, Program: " . ($enrollment->program->name ?? 'No program') . ", Status: {$enrollment->status}\n";
    }
} else {
    echo "No enrollments found in database!\n";
}

// Check all students
$allStudents = User::where('role', 'student')->get();
echo "\nTotal students: " . $allStudents->count() . "\n";

if ($allStudents->count() > 0) {
    echo "All students:\n";
    foreach ($allStudents as $student) {
        echo "  - ID: {$student->id}, Name: {$student->name}, Email: {$student->email}\n";

        // Check enrollments for this student
        $studentEnrollments = $student->enrollments()->with('program')->get();
        if ($studentEnrollments->count() > 0) {
            echo "    Enrollments:\n";
            foreach ($studentEnrollments as $enrollment) {
                echo "      - Program: " . ($enrollment->program->name ?? 'No program') . ", Status: {$enrollment->status}\n";

                if ($enrollment->program) {
                    $schedulesCount = $enrollment->program->schedules()->count();
                    echo "        Schedules count: {$schedulesCount}\n";
                }
            }
        } else {
            echo "    No enrollments found\n";
        }
    }
}

// Check all programs
$allPrograms = Program::all();
echo "\nTotal programs: " . $allPrograms->count() . "\n";

if ($allPrograms->count() > 0) {
    echo "All programs:\n";
    foreach ($allPrograms as $program) {
        echo "  - ID: {$program->id}, Name: {$program->name}, Duration: " . ($program->duration ?? 'Not set') . "\n";
        $schedulesCount = $program->schedules()->count();
        echo "    Schedules count: {$schedulesCount}\n";
    }
}

// Check all schedules
$allSchedules = Schedule::with('program')->get();
echo "\nTotal schedules: " . $allSchedules->count() . "\n";

if ($allSchedules->count() > 0) {
    echo "All schedules:\n";
    foreach ($allSchedules as $schedule) {
        echo "  - ID: {$schedule->id}, Program: " . ($schedule->program->name ?? 'No program') . ", Day: {$schedule->day}\n";
    }
}

echo "\n=== END DEBUG ===\n";
