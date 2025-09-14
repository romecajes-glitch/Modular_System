<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    // Check if we have any users
    $user = App\Models\User::first();
    
    if ($user) {
        echo "User found: " . $user->id . " - " . $user->name . "\n";
        
        // Create a test payment
        $payment = App\Models\Payment::create([
            'student_id' => $user->id,
            'amount' => 500.00,
            'session_count' => 2,
            'payment_date' => now(),
            'status' => 'completed',
            'payment_method' => 'test',
            'transaction_id' => 'TEST-' . now()->format('YmdHis')
        ]);
        
        echo "Test payment created successfully!\n";
        echo "Payment ID: " . $payment->id . "\n";
        echo "Session Count: " . $payment->session_count . "\n";
        
        // Test the payment relationship
        $userPayments = $user->payments()->where('status', 'completed')->get();
        echo "Completed payments for user: " . $userPayments->count() . "\n";
        echo "Total paid sessions: " . $userPayments->sum('session_count') . "\n";
        
    } else {
        echo "No users found in the database.\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
