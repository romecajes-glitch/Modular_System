<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Auth;

// Simulate admin user login
$user = \App\Models\User::where('role', 'admin')->first();
if ($user) {
    Auth::login($user);
    echo "Logged in as: " . $user->name . "\n";
} else {
    echo "No admin user found\n";
    exit;
}

try {
    $controller = new AdminController();
    $result = $controller->userManagement();

    echo "Method executed successfully\n";
    echo "Result type: " . gettype($result) . "\n";

    if (is_object($result)) {
        echo "Result class: " . get_class($result) . "\n";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
}
