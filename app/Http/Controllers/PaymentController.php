<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\Payment;
use App\Models\User;

class PaymentController extends Controller
{
    public function checkout(Request $request)
    {
        $amount = $request->input('amount'); // Amount in PHP pesos (e.g., 100 for ₱100)
        $email = $request->input('email');
        $sessionCount = $request->input('session_count', 1);
        $studentId = Auth::user()->id;

        $response = Http::withToken(env('PAYMONGO_SECRET_KEY'))
            ->post('https://api.paymongo.com/v1/checkout_sessions', [
                'data' => [
                    'attributes' => [
                        'line_items' => [
                            [
                                'name' => "Modular Class Session" . ($sessionCount > 1 ? "s ($sessionCount)" : ""),
                                'quantity' => 1,
                                'currency' => 'PHP',
                                'amount' => $amount * 100, // Convert to cents
                            ]
                        ],
                        'payment_method_types' => ['gcash'],
                        'success_url' => env('PAYMONGO_SUCCESS_URL') . "?student_id=$studentId&session_count=$sessionCount&amount=$amount",
                        'cancel_url' => env('PAYMONGO_CANCEL_URL'),
                    ]
                ]
            ]);

        if ($response->successful()) {
            $checkoutURL = $response->json()['data']['attributes']['checkout_url'];
            return redirect($checkoutURL);
        } else {
            return back()->with('error', 'Unable to create payment session.');
        }
    }

    public function success(Request $request)
    {
        // Handle successful payment logic here
        $studentId = $request->input('student_id');
        $sessionCount = $request->input('session_count', 1);
        $amount = $request->input('amount');

        if ($studentId) {
            // Create payment record
            $payment = Payment::create([
                'student_id' => $studentId,
                'amount' => $amount,
                'session_count' => $sessionCount,
                'payment_date' => now(),
                'status' => 'completed',
                'payment_method' => 'gcash',
                'transaction_id' => 'TXN-' . now()->format('YmdHis')
            ]);

            // Get student and enrollment data for the payment view
            $student = User::find($studentId);
            $enrollment = $student->enrollments()
                ->with(['program'])
                ->latest()
                ->first();
            
            // Update the enrollment with the new paid sessions
            if ($enrollment) {
                $currentPaidSessions = $enrollment->paid_sessions ?? 0;
                $enrollment->paid_sessions = $currentPaidSessions + $sessionCount;
                $enrollment->save();
                
                // Create attendance records for the newly paid sessions
                $this->createAttendanceSessions($enrollment, $sessionCount, $currentPaidSessions);
            }
            
            $program = $enrollment->program ?? null;

            // Get payment data including the newly created payment
            $paymentData = $this->getPaymentDataForView($student);

            // Prepare success message with payment details
            $successMessage = "Payment successful! You have purchased {$sessionCount} session" . 
                            ($sessionCount > 1 ? 's' : '') . 
                            " for ₱" . number_format($amount, 2) . 
                            ". Your sessions have been credited to your account.";

            return view('Student.payment', compact(
                'student', 
                'enrollment', 
                'program',
                'paymentData'
            ))->with('success', $successMessage);
        }

        // Fallback if student_id is not provided
        return redirect()->route('student.payment')->with('error', 'Payment successful but unable to display details. Please check your payment history.');
    }

    /**
     * Get payment data for the view including the newly created payment
     */
    private function getPaymentDataForView($student)
    {
        try {
            // Get all completed payment records including the newly created one
            $paymentRecords = $student->payments()
                ->where('status', 'completed')
                ->orderBy('payment_date', 'desc')
                ->get();

            // Add program name to each payment record
            $paymentRecords->each(function ($payment) use ($student) {
                // Get the enrollment for this student at the time of payment
                $enrollment = $student->enrollments()
                    ->where('created_at', '<=', $payment->payment_date)
                    ->orderBy('created_at', 'desc')
                    ->first();
                
                if ($enrollment && $enrollment->program) {
                    $payment->program_name = $enrollment->program->name;
                } else {
                    $payment->program_name = 'Program Payment';
                }
            });

            $totalPaid = $paymentRecords->sum('amount');
            
            // Get the latest payment details
            $latestPayment = $paymentRecords->first();
            $paymentDate = $latestPayment ? \Carbon\Carbon::parse($latestPayment->payment_date)->format('M d, Y') : '--';
            $paymentMethod = $latestPayment ? ($latestPayment->payment_method ?? '--') : '--';
            
            return [
                'total_paid' => $totalPaid,
                'total_due' => 500, // Placeholder for now
                'payment_date' => $paymentDate,
                'payment_method' => $paymentMethod,
                'payment_status' => 'current',
                'payment_records' => $paymentRecords
            ];
        } catch (\Exception $e) {
            // Fallback to placeholder data if there's an error
            return [
                'total_paid' => 0,
                'total_due' => 500,
                'payment_date' => '--',
                'payment_method' => '--',
                'payment_status' => 'current',
                'payment_records' => collect([])
            ];
        }
    }

    public function cancel(Request $request)
    {
        // Handle failed payment logic here
        return view('payment.cancel');
    }

    /**
     * Create attendance sessions for newly paid sessions
     */
    private function createAttendanceSessions($enrollment, $newSessionCount, $currentPaidSessions)
    {
        try {
            // Get the program to determine session dates
            $program = $enrollment->program;
            if (!$program) {
                Log::warning('Cannot create attendance sessions: No program found for enrollment', [
                    'enrollment_id' => $enrollment->id
                ]);
                return;
            }

            // Get existing schedules for the program
            $schedules = $program->schedules()->orderBy('day')->get();
            
            if ($schedules->isEmpty()) {
                Log::warning('Cannot create attendance sessions: No schedules found for program', [
                    'program_id' => $program->id,
                    'program_name' => $program->name
                ]);
                return;
            }

            // Calculate session dates based on program schedules
            $sessionDates = $this->calculateSessionDates($schedules, $newSessionCount);

            // Create attendance records for each new session
            foreach ($sessionDates as $index => $sessionDate) {
                $sessionNumber = $currentPaidSessions + $index + 1;
                
                \App\Models\Attendance::create([
                    'enrollment_id' => $enrollment->id,
                    'session_number' => $sessionNumber,
                    'session_date' => $sessionDate,
                    'status' => null, // Initially not attended
                    'notes' => 'Session purchased via payment'
                ]);
            }

            Log::info('Created attendance sessions for enrollment', [
                'enrollment_id' => $enrollment->id,
                'new_sessions' => $newSessionCount,
                'total_paid_sessions' => $enrollment->paid_sessions
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to create attendance sessions: ' . $e->getMessage(), [
                'enrollment_id' => $enrollment->id,
                'new_session_count' => $newSessionCount
            ]);
        }
    }

    /**
     * Calculate session dates based on program schedules
     */
    private function calculateSessionDates($schedules, $sessionCount)
    {
        $sessionDates = [];
        $currentDate = now()->startOfDay();
        $daysOfWeek = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
        
        // Get the days when sessions occur
        $sessionDays = $schedules->pluck('day')->unique()->map(function($day) use ($daysOfWeek) {
            return array_search(strtolower($day), $daysOfWeek);
        })->filter(function($index) {
            return $index !== false;
        })->sort()->values();

        if ($sessionDays->isEmpty()) {
            // Fallback: create sessions on weekdays if no specific days are set
            $sessionDays = collect([1, 2, 3, 4, 5]); // Monday to Friday
        }

        $sessionIndex = 0;
        $dayOffset = 0;

        while ($sessionIndex < $sessionCount) {
            $currentDay = $currentDate->copy()->addDays($dayOffset);
            $dayOfWeek = $currentDay->dayOfWeek;

            if ($sessionDays->contains($dayOfWeek)) {
                $sessionDates[] = $currentDay->format('Y-m-d');
                $sessionIndex++;
            }

            $dayOffset++;

            // Safety check to prevent infinite loop
            if ($dayOffset > 365) {
                Log::warning('Session date calculation exceeded safety limit', [
                    'session_count' => $sessionCount,
                    'calculated_sessions' => count($sessionDates)
                ]);
                break;
            }
        }

        return $sessionDates;
    }

    /**
     * Process direct payment without external payment gateway
     */
    public function processDirectPayment(Request $request)
    {
        try {
            $amount = $request->input('amount');
            $sessionCount = $request->input('session_count', 1);
            $studentId = Auth::user()->id;

            // Create payment record
            $payment = Payment::create([
                'student_id' => $studentId,
                'amount' => $amount,
                'session_count' => $sessionCount,
                'payment_date' => now(),
                'status' => 'completed',
                'payment_method' => 'direct',
                'transaction_id' => 'DIRECT-' . now()->format('YmdHis')
            ]);

            // Get the student's enrollment to update paid sessions
            $student = Auth::user();
            $enrollment = $student->enrollments()
                ->latest()
                ->first();

            $totalPaidSessions = 0;
            if ($enrollment) {
                // Update the enrollment with the new paid sessions
                $currentPaidSessions = $enrollment->paid_sessions ?? 0;
                $enrollment->paid_sessions = $currentPaidSessions + $sessionCount;
                $enrollment->save();
                $totalPaidSessions = $enrollment->paid_sessions;
                
                // Create attendance records for the newly paid sessions
                $this->createAttendanceSessions($enrollment, $sessionCount, $currentPaidSessions);
            }

            return response()->json([
                'success' => true,
                'message' => 'Payment successful! Your ' . $sessionCount . ' session' . ($sessionCount > 1 ? 's have' : ' has') . ' been credited to your account.',
                'paid_sessions' => $totalPaidSessions
            ]);

        } catch (\Exception $e) {
            Log::error('Direct payment failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Payment failed. Please try again or contact support.'
            ], 500);
        }
    }
}
