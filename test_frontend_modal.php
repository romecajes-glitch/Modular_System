<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

try {
    // Test the complete flow: enrollment exists, has attendance, and API returns data
    $enrollment = \App\Models\Enrollment::with(['user', 'program', 'attendances'])->find(1);

    if (!$enrollment) {
        echo "❌ No enrollment found with ID 1\n";
        exit(1);
    }

    echo "✅ Enrollment found:\n";
    echo "   - Student: {$enrollment->first_name} {$enrollment->last_name}\n";
    echo "   - Program: " . ($enrollment->program ? $enrollment->program->name : 'N/A') . "\n";
    echo "   - Attendances: {$enrollment->attendances->count()}\n";

    // Test the API endpoint
    $adminController = new \App\Http\Controllers\AdminController();
    $response = $adminController->getStatementOfAccount(1);

    if ($response->getStatusCode() !== 200) {
        echo "❌ API returned status code: {$response->getStatusCode()}\n";
        exit(1);
    }

    $data = json_decode($response->getContent(), true);

    if (isset($data['error'])) {
        echo "❌ API returned error: {$data['error']}\n";
        exit(1);
    }

    echo "✅ API Response successful:\n";
    echo "   - Student name: {$data['student']['first_name']} {$data['student']['last_name']}\n";
    echo "   - Attendances count: " . count($data['attendances']) . "\n";

    // Check if attendances have proper data
    if (count($data['attendances']) > 0) {
        $firstAttendance = $data['attendances'][0];
        echo "   - First attendance session: {$firstAttendance['session_number']}\n";
        echo "   - OR Number: {$firstAttendance['or_number']}\n";
        echo "   - Payment amount: " . ($firstAttendance['payment'] ? '₱' . $firstAttendance['payment']['amount'] : 'N/A') . "\n";
        echo "   - Reference number: " . ($firstAttendance['payment'] ? $firstAttendance['payment']['reference_number'] : 'N/A') . "\n";
    }

    echo "\n🎉 All tests passed! The statement of account functionality is working correctly.\n";
    echo "The frontend modal should now be able to display this data when clicking 'View Details'.\n";

} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "Trace:\n" . $e->getTraceAsString() . "\n";
}
