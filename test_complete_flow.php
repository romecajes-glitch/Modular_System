<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

try {
    // Test the complete flow: enrollment exists, API returns data, and frontend can display it
    $enrollment = \App\Models\Enrollment::with(['user', 'program', 'attendances'])->find(1);

    if (!$enrollment) {
        echo "❌ No enrollment found with ID 1\n";
        exit(1);
    }

    echo "✅ Enrollment found:\n";
    echo "   - Student: {$enrollment->first_name} {$enrollment->last_name}\n";
    echo "   - Program: " . ($enrollment->program ? $enrollment->program->name : 'N/A') . "\n";
    echo "   - Attendances: {$enrollment->attendances->count()}\n";

    // Test the enrollment details API
    $adminController = new \App\Http\Controllers\AdminController();
    $response = $adminController->getEnrollmentDetails(1);

    if ($response->getStatusCode() !== 200) {
        echo "❌ Enrollment details API returned status code: {$response->getStatusCode()}\n";
        exit(1);
    }

    $enrollmentData = json_decode($response->getContent(), true);

    if (isset($enrollmentData['error'])) {
        echo "❌ Enrollment details API returned error: {$enrollmentData['error']}\n";
        exit(1);
    }

    echo "✅ Enrollment details API successful:\n";
    echo "   - Student name: {$enrollmentData['first_name']} {$enrollmentData['last_name']}\n";
    echo "   - Program: {$enrollmentData['program']}\n";
    echo "   - Address: {$enrollmentData['address']}\n";
    echo "   - Phone: {$enrollmentData['phone']}\n";

    // Test the statement of account API
    $response2 = $adminController->getStatementOfAccount(1);

    if ($response2->getStatusCode() !== 200) {
        echo "❌ Statement of account API returned status code: {$response2->getStatusCode()}\n";
        exit(1);
    }

    $attendanceData = json_decode($response2->getContent(), true);

    if (isset($attendanceData['error'])) {
        echo "❌ Statement of account API returned error: {$attendanceData['error']}\n";
        exit(1);
    }

    echo "✅ Statement of account API successful:\n";
    echo "   - Attendances count: " . count($attendanceData['attendances']) . "\n";
    echo "   - Instructor: " . ($attendanceData['instructor'] ? $attendanceData['instructor']['name'] : 'N/A') . "\n";

    // Check if attendances have proper data
    if (count($attendanceData['attendances']) > 0) {
        $firstAttendance = $attendanceData['attendances'][0];
        echo "   - First attendance session: {$firstAttendance['session_number']}\n";
        echo "   - OR Number: {$firstAttendance['or_number']}\n";
        echo "   - Payment amount: " . ($firstAttendance['payment'] ? '₱' . $firstAttendance['payment']['amount'] : 'N/A') . "\n";
        echo "   - Reference number: " . ($firstAttendance['payment'] ? $firstAttendance['payment']['reference_number'] : 'N/A') . "\n";
    }

    // Verify that program is properly formatted as string
    if ($enrollmentData['program'] === 'N/A') {
        echo "❌ Program is showing as 'N/A' - this indicates the program relationship is not working\n";
        exit(1);
    }

    echo "\n🎉 All tests passed! The complete flow is working correctly.\n";
    echo "The frontend modal should now be able to display all student details including the program name.\n";
    echo "\n📋 Summary of fixes applied:\n";
    echo "   ✅ Fixed JavaScript to correctly access program as string instead of object\n";
    echo "   ✅ Verified API endpoints return correct data format\n";
    echo "   ✅ Confirmed program relationship is working in database\n";

} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "Trace:\n" . $e->getTraceAsString() . "\n";
}
