<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Payment;

class TestPayment extends Command
{
    protected $signature = 'payment:test';
    protected $description = 'Test payment functionality by creating a test payment record';

    public function handle()
    {
        try {
            $user = User::first();
            
            if (!$user) {
                $this->error('No users found in database');
                return 1;
            }

            $this->info("User found: {$user->id} - {$user->name}");

            // Create a test payment
            $payment = Payment::create([
                'student_id' => $user->id,
                'amount' => 500.00,
                'session_count' => 2,
                'payment_date' => now(),
                'status' => 'completed',
                'payment_method' => 'test',
                'transaction_id' => 'TEST-' . now()->format('YmdHis')
            ]);

            $this->info("Test payment created successfully!");
            $this->info("Payment ID: {$payment->id}");
            $this->info("Session Count: {$payment->session_count}");

            // Test the payment relationship
            $userPayments = $user->payments()->where('status', 'completed')->get();
            $this->info("Completed payments for user: " . $userPayments->count());
            $this->info("Total paid sessions: " . $userPayments->sum('session_count'));

            return 0;

        } catch (\Exception $e) {
            $this->error("Error: " . $e->getMessage());
            return 1;
        }
    }
}
