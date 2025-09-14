<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

try {
    // Test the enrollment details API to check if program is returned
    $enrollment = \App\Models\Enrollment::with(['user', 'program'])->find(1);

    if (!$enrollment) {
        echo "❌ No enrollment found with ID 1\n";
        exit(1);
    }

    echo "✅ Enrollment found:\n";
    echo "   - Student: {$enrollment->first_name} {$enrollment->last_name}\n";
    echo "   - Program: " . ($enrollment->program ? $enrollment->program->name : 'N/A') . "\n";

    // Test the API endpoint
    $adminController = new \App\Http\Controllers\AdminController();
    $response = $adminController->getEnrollmentDetails(1);

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
    echo "   - Student name: {$data['first_name']} {$data['last_name']}\n";
    echo "   - Program: {$data['program']}\n";

    // Check if program is properly formatted
    if ($data['program'] === 'N/A') {
        echo "❌ Program is showing as 'N/A' - this indicates the program relationship is not working\n";
        exit(1);
    }

    echo "\n🎉 Program display test passed! The program name is being returned correctly.\n";
    echo "The frontend should now display the program name under the student name.\n";

} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "Trace:\n" . $e->getTraceAsString() . "\n";
}
