<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

try {
    // Test the getStatementOfAccount method directly
    $adminController = new \App\Http\Controllers\AdminController();

    // Create a mock request for enrollment ID 1
    $request = new \Illuminate\Http\Request();
    $request->merge(['enrollmentId' => 1]);

    // Call the method
    $response = $adminController->getStatementOfAccount(1);

    echo "API Response:\n";
    echo $response->getContent() . "\n";

} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "Trace:\n" . $e->getTraceAsString() . "\n";
}
