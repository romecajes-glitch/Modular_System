<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Enrollment;
use App\Models\Certificate;
use App\Models\Program;
use App\Models\Schedule;
use App\Models\Attendance;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class AdminController extends Controller
{
    // Dashboard Overview
    public function dashboard()
    {
        $totalStudents = User::where('role', 'student')->count();
        $activeEnrollments = Enrollment::count();
        $instructors = User::where('role', 'instructor')->count();
        $certificatesIssued = Certificate::count();
        $recentEnrollments = Enrollment::with(['user', 'program'])->latest()->take(3)->get();
        $recentCertificates = Certificate::latest()->take(2)->get();
        $recentPayments = Payment::with(['student'])->latest()->take(3)->get();

        $adminUser = Auth::user();

        return view('Admin.dashboard', compact(
            'totalStudents',
            'activeEnrollments',
            'instructors',
            'certificatesIssued',
            'recentEnrollments',
            'recentCertificates',
            'recentPayments',
            'adminUser'
        ));
    }

    // Show all enrollments (not paginated)
    public function showEnrollments()
    {
        $enrollments = Enrollment::with(['user', 'program'])->latest()->get();
        return view('Admin.enrollment_management', compact('enrollments'));
    }

    // Paginated enrollment management
    public function enrollmentManagement()
    {
        $pendingEnrollments = Enrollment::where('status', 'pending')->with(['user', 'program'])->latest()->paginate(10, ['*'], 'pending_page');
        $approvedEnrollments = Enrollment::where('status', 'approved')->with(['user', 'program'])->latest()->paginate(10, ['*'], 'approved_page');
        $rejectedEnrollments = Enrollment::where('status', 'rejected')->with(['user', 'program'])->latest()->paginate(10, ['*'], 'rejected_page');
        return view('Admin.enrollment_management', compact('pendingEnrollments', 'approvedEnrollments', 'rejectedEnrollments'));
    }

    // Approve enrollment
    public function approveEnrollment(Enrollment $enrollment)
    {
        $enrollment->update([
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => Auth::id(),
            'rejected_at' => null,
            'rejected_by' => null,
            'rejection_reason' => null,
        ]);
        return response()->json(['success' => true, 'message' => 'Enrollment approved successfully']);
    }

    // Reject enrollment
    public function rejectEnrollment(Request $request, Enrollment $enrollment)
    {
        $enrollment->update([
            'status' => 'rejected',
            'approved_at' => null,
            'approved_by' => null,
            'rejected_at' => now(),
            'rejected_by' => Auth::id(),
            'rejection_reason' => $request->input('reason'),
        ]);

        // Send email notification to student
        try {
            Mail::to($enrollment->email)->send(new \App\Mail\EnrollmentRejectedMail($enrollment, $request->input('reason')));
        } catch (\Exception $e) {
            Log::error('Failed to send rejection email: ' . $e->getMessage());
        }

        return response()->json(['success' => true, 'message' => 'Enrollment rejected successfully']);
    }

    //Enrolled Students - Only show students who have paid registration fee and are enrolled
    public function enrolledStudent()
    {
        $students = \App\Models\User::where('role', 'student')->where('status', 'active')->get();

        // Get only students with enrolled status AND who have paid registration fee
        $enrolledStudents = \App\Models\User::where('role', 'student')
            ->where('status', 'active')
            ->whereHas('enrollments', function($query) {
                $query->where('status', \App\Models\Enrollment::STATUS_ENROLLED)
                      ->where('registration_fee_paid', true); // Only show students who paid registration fee
            })
            ->with(['payments' => function($query) {
                $query->orderBy('payment_date', 'desc');
            }, 'enrollments' => function($query) {
                $query->where('status', \App\Models\Enrollment::STATUS_ENROLLED)
                      ->where('registration_fee_paid', true) // Ensure registration fee is paid
                      ->with('program');
            }])
            ->get()
            ->map(function ($student) {
                $enrollment = $student->enrollments->first();
                if ($enrollment) {
                    // Determine if student has completed all sessions in their program
                    $programDuration = $enrollment->program->duration ?? '1';
                    $programSessions = 1;
                    if (is_numeric($programDuration)) {
                        $programSessions = (int)$programDuration;
                    } else {
                        preg_match('/(\d+)/', $programDuration, $matches);
                        if (!empty($matches[1])) {
                            $programSessions = (int)$matches[1];
                        }
                    }

                    // Count attendance records for this enrollment
                    $attendanceCount = $enrollment->attendances()->where('status', 'present')->count();

                    // If attendance count >= program sessions, mark payment status as complete
                    if ($attendanceCount >= $programSessions) {
                        // Mark all payments as completed for this student
                        foreach ($student->payments as $payment) {
                            if ($payment->status !== 'completed') {
                                $payment->status = 'completed';
                                $payment->save();
                            }
                        }
                    }
                }
                return $student;
            });

        // Sorting and searching will be handled in the view via JavaScript or server-side if needed

        // Paginate the collection manually after map
        $perPage = 10;
        $page = request()->get('page', 1);
        $paginated = new \Illuminate\Pagination\LengthAwarePaginator(
            $enrolledStudents->forPage($page, $perPage),
            $enrolledStudents->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view('Admin.enrolled_student', ['students' => $students, 'enrolledStudents' => $paginated]);
    }

    // Get student payments
    public function getStudentPayments($studentId)
    {
        $payments = \App\Models\Payment::where('student_id', $studentId)
            ->orderBy('payment_date', 'desc')
            ->get();

        return response()->json($payments);
    }

    // User management
    public function userManagement()
    {
        $students = User::where('role', 'student')->get();
        $instructors = User::where('role', 'instructor')->get();
        $programs = Program::all(); // <-- Add this line
        return view('Admin.user_management', compact('students', 'instructors', 'programs')); // <-- Add 'programs'
    }

    // Get a specific user by ID
    public function getUser($id)
    {
        try {
            $user = User::findOrFail($id);
            $enrollment = $user->enrollments()->latest()->first();

            return response()->json([
                'success' => true,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'username' => $user->username,
                    'email' => $user->email,
                    'phone' => $enrollment ? $enrollment->phone : $user->phone,
                    'role' => $user->role,
                    'status' => $user->status,
                    'created_at' => $user->created_at->toISOString(),
                    'last_login' => $user->last_login ? $user->last_login->toISOString() : null,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }
    }

    // Update user data
    public function updateUser(Request $request, $id)
    {
        try {
            $request->validate([
                'full_name' => 'required|string|max:255',
                'username' => 'required|string|max:255|unique:users,username,' . $id,
                'email' => 'required|email|max:255|unique:users,email,' . $id,
                'phone' => 'nullable|string|max:20',
                'status' => 'required|in:active,inactive',
            ]);

            $user = User::findOrFail($id);
            $newStatus = $request->input('status');

            // Handle inactive status changes
            $updateData = [
                'name' => $request->input('full_name'),
                'username' => $request->input('username'),
                'email' => $request->input('email'),
                'phone' => $request->input('phone'),
                'status' => $newStatus,
            ];

            if ($newStatus === 'inactive' && $user->status !== 'inactive') {
                // User is being marked as inactive for the first time
                $updateData['inactive_at'] = now();
                $updateData['scheduled_deletion_at'] = now()->addDays(30);
            } elseif ($newStatus === 'active' && $user->status === 'inactive') {
                // User is being reactivated, clear inactive timestamps
                $updateData['inactive_at'] = null;
                $updateData['scheduled_deletion_at'] = null;
            }

            $user->update($updateData);

            $message = 'User updated successfully';
            if ($newStatus === 'inactive' && $user->status !== 'inactive') {
                $message .= '. This user will be permanently deleted after 30 days of inactivity.';
            }
            return response()->json([
                'success' => true,
                'message' => $message
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update user: ' . $e->getMessage()
            ], 500);
        }
    }

    // Reset user password
    public function resetUserPassword($id)
    {
        try {
            $user = User::findOrFail($id);

            // Check if user has birthdate in users table
            if (!$user->birthdate) {
                return response()->json([
                    'success' => false,
                    'message' => 'User birthdate not found. Cannot reset password.'
                ], 400);
            }

            // Use birthdate as the new password (format: YYYY-MM-DD)
            $newPassword = $user->birthdate;

            $user->update([
                'password' => bcrypt($newPassword),
            ]);

            // Here you could send an email with the new password
            // For now, we'll just return the new password in the response
            // In production, you should send this via email instead

            return response()->json([
                'success' => true,
                'message' => 'Password reset successfully',
                'username' => $user->username,
                'new_password' => $newPassword // Remove this in production and send via email
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to reset password: ' . $e->getMessage()
            ], 500);
        }
    }

    // Reports (payments, attendance, etc.)
    public function reports(Request $request)
    {
        $dateRange = $request->input('date_range', '30'); // Default to last 30 days
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $enrollmentsQuery = Enrollment::query();
        $usersQuery = User::query();
        $paymentsQuery = Payment::query();

        // Apply date filtering
        if ($dateRange === 'custom' && $startDate && $endDate) {
            $enrollmentsQuery->whereBetween('created_at', [$startDate, $endDate]);
            $usersQuery->whereBetween('created_at', [$startDate, $endDate]);
            $paymentsQuery->whereBetween('payment_date', [$startDate, $endDate]);
        } elseif ($dateRange !== 'all' && is_numeric($dateRange)) {
            $startDate = now()->subDays($dateRange)->startOfDay();
            $endDate = now()->endOfDay();
            $enrollmentsQuery->whereBetween('created_at', [$startDate, $endDate]);
            $usersQuery->whereBetween('created_at', [$startDate, $endDate]);
            $paymentsQuery->whereBetween('payment_date', [$startDate, $endDate]);
        }


        $totalEnrollments = $enrollmentsQuery->count();
        $totalUsers = $usersQuery->count();
        $totalStudents = User::where('role', 'student')->count();
        $totalInstructors = User::where('role', 'instructor')->count();
        $totalCertificates = Certificate::count();
        $totalPrograms = Program::count();

        // Get enrollment data by week, filtered by date range if provided
        $enrollmentQuery = Enrollment::query();
        
        // Apply date filtering if provided
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $enrollmentQuery->whereBetween('created_at', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ]);
        } elseif ($request->filled('date_range') && $request->date_range !== 'all') {
            $days = (int) $request->date_range;
            $enrollmentQuery->where('created_at', '>=', now()->subDays($days));
        }
        // If date_range is 'all' or not provided, no filtering is applied (shows all data)
        
        $enrollmentByWeek = $enrollmentQuery->selectRaw('
                YEAR(created_at) as year,
                WEEK(created_at, 1) as week,
                DATE(DATE_SUB(created_at, INTERVAL WEEKDAY(created_at) DAY)) as week_start,
                COUNT(*) as count
            ')
            ->groupBy('year', 'week', 'week_start')
            ->orderBy('year', 'asc')
            ->orderBy('week', 'asc')
            ->get()
            ->map(function ($item) {
                return [
                    'week' => 'Week ' . $item->week . ', ' . $item->year,
                    'week_start' => $item->week_start,
                    'count' => $item->count
                ];
            });

        $enrollmentByStatus = Enrollment::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get();

        $programEnrollments = Enrollment::selectRaw('program_id, COUNT(*) as count')
            ->groupBy('program_id')
            ->orderBy('count', 'desc')
            ->get();

        $mostPopularProgram = $programEnrollments->first();

        $recentEnrollments = Enrollment::with(['user', 'program'])
            ->latest()
            ->paginate(10, ['*'], 'enrollments_page');

        $allUsers = User::latest()
            ->paginate(10, ['*'], 'users_page');

        $userActivities = User::select('users.*')
            ->where('status', 'active')
            ->latest()
            ->paginate(10, ['*'], 'activities_page');

        $systemLogs = User::select('users.*')
            ->latest()
            ->paginate(10, ['*'], 'logs_page');

        $paymentLogs = $paymentsQuery->clone()->with(['student'])->latest()->paginate(10, ['*'], 'payments_page');

        // Calculate total online payments (completed payments with online methods)
        $totalOnlinePayments = $paymentsQuery->clone()
            ->where('status', 'completed')
            ->whereIn('payment_method', ['online', 'GCash', 'PayMaya', 'GrabPay', 'PayPal'])
            ->sum('amount');

        // Calculate payment breakdown by type
        $registrationPayments = $paymentsQuery->clone()
            ->where('status', 'completed')
            ->whereIn('payment_method', ['online', 'GCash', 'PayMaya', 'GrabPay', 'PayPal'])
            ->where('session_count', 0) // Registration fees have 0 session count
            ->sum('amount');

        $sessionPayments = $paymentsQuery->clone()
            ->where('status', 'completed')
            ->whereIn('payment_method', ['online', 'GCash', 'PayMaya', 'GrabPay', 'PayPal'])
            ->where('session_count', '>', 0) // Session payments have session count > 0
            ->sum('amount');

        // Get all programs for the pie chart
        $programs = Program::all();

        // QR Code Statistics
        $totalQrCodes = DB::table('qr_codes')->count();
        $usedQrCodes = DB::table('qr_codes')->where('is_used', true)->count();
        $unusedQrCodes = $totalQrCodes - $usedQrCodes;
        $qrCodeUsageRate = $totalQrCodes > 0 ? round(($usedQrCodes / $totalQrCodes) * 100, 1) : 0;

        return view('Admin.reports', compact(
            'totalEnrollments',
            'totalUsers',
            'totalStudents',
            'totalInstructors',
            'totalCertificates',
            'totalPrograms',
            'enrollmentByWeek',
            'enrollmentByStatus',
            'programEnrollments',
            'mostPopularProgram',
            'recentEnrollments',
            'allUsers',
            'userActivities',
            'systemLogs',
            'paymentLogs',
            'totalOnlinePayments',
            'registrationPayments',
            'sessionPayments',
            'programs',
            'totalQrCodes',
            'usedQrCodes',
            'unusedQrCodes',
            'qrCodeUsageRate'
        ));
    }

    // View and manage class schedules
    public function schedules()
    {
        $programs = Program::all();
        $schedules = Schedule::with('program')->get();
        return view('Admin.schedule', compact('programs', 'schedules'));
    }

    // Get a specific schedule
    public function getSchedule($id)
    {
        $schedule = Schedule::findOrFail($id);
        return response()->json($schedule);
    }

    // Update a specific schedule
    public function updateSchedule(Request $request, $id)
    {
        $schedule = Schedule::findOrFail($id);
        $schedule->update($request->only(['program_id', 'day', 'start_time', 'end_time']));
        return response()->json(['success' => true, 'message' => 'Schedule updated successfully.']);
    }

    // Delete a specific schedule
    public function deleteSchedule($id)
    {
        $schedule = Schedule::findOrFail($id);
        $schedule->delete();
        return response()->json(['success' => true, 'message' => 'Schedule deleted successfully.']);
    }

    public function getEnrollmentDetails($enrollmentId)
    {
        try {
            $enrollment = Enrollment::with(['program', 'user'])->findOrFail($enrollmentId);

            // Determine photo URL - check enrollment photo first, then user photo as fallback
            $photoUrl = null;
            if ($enrollment->photo) {
                $photoUrl = asset('storage/' . $enrollment->photo);
            } elseif ($enrollment->user && $enrollment->user->photo) {
                $photoUrl = asset('storage/' . $enrollment->user->photo);
            }

            return response()->json([
                'id' => $enrollment->id,
                'first_name' => $enrollment->first_name,
                'last_name' => $enrollment->last_name,
                'middle_name' => $enrollment->middle_name,
                'suffix_name' => $enrollment->suffix_name,
                'birthdate' => $enrollment->birthdate,
                'email' => $enrollment->email,
                'phone' => $enrollment->phone,
                'address' => $enrollment->address,
                'program' => (string) ($enrollment->program?->name ?? 'N/A'),
                'recruiter' => $enrollment->recruiter,
                'photo' => $photoUrl,
                'status' => $enrollment->status,
                'batch_number' => $enrollment->batch_number,
                'created_at' => $enrollment->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $enrollment->updated_at->format('Y-m-d H:i:s')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Enrollment not found',
                'message' => 'The requested enrollment could not be found.'
            ], 404);
        }
    }

    // Get Statement of Account for enrollment
    public function getStatementOfAccount($enrollmentId)
    {
        try {
            $enrollment = Enrollment::with(['user', 'program.schedules.instructorUser', 'attendances'])->findOrFail($enrollmentId);

            // Get attendance records with payment information
            $attendances = $enrollment->attendances()
                ->orderBy('session_number')
                ->get()
                ->map(function ($attendance) use ($enrollment) {
                    // Get payment amount for this session
                    $payment = Payment::where('student_id', $enrollment->student_id)
                        ->where('status', 'completed')
                        ->where('session_count', '>=', $attendance->session_number)
                        ->orderBy('payment_date', 'desc')
                        ->first();

                    // If no payment record exists, create a default payment entry based on attendance OR number
                    if (!$payment && $attendance->or_number) {
                        // Get program price per session
                        $programPrice = $enrollment->program ? $enrollment->program->price_per_session : 0;

                        return [
                            'session_number' => $attendance->session_number,
                            'session_date' => $attendance->session_date,
                            'start_time' => $attendance->start_time,
                            'end_time' => $attendance->end_time,
                            'created_at' => $attendance->created_at,
                            'or_number' => $attendance->or_number,
                            'payment' => [
                                'amount' => $programPrice,
                                'reference_number' => null // Reference number should be null when no payment exists
                            ]
                        ];
                    }

                    return [
                        'session_number' => $attendance->session_number,
                        'session_date' => $attendance->session_date,
                        'start_time' => $attendance->start_time,
                        'end_time' => $attendance->end_time,
                        'created_at' => $attendance->created_at,
                        'or_number' => $attendance->or_number,
                        'payment' => $payment ? [
                            'amount' => $payment->amount,
                            'reference_number' => $payment->transaction_id
                        ] : null
                    ];
                });

            // Get all instructors assigned to the program
            $instructors = [];
            if ($enrollment->program) {
                $instructors = \App\Models\User::where('role', 'instructor')
                    ->where('program_id', $enrollment->program->id)
                    ->get()
                    ->map(function ($instructor) {
                        return ['name' => ucwords(strtolower($instructor->name))];
                    })
                    ->toArray();
            }

            return response()->json([
                'student' => [
                    'first_name' => $enrollment->first_name,
                    'last_name' => $enrollment->last_name,
                    'middle_name' => $enrollment->middle_name,
                    'suffix_name' => $enrollment->suffix_name,
                    'address' => $enrollment->address,
                    'phone' => $enrollment->phone,
                    'email' => $enrollment->email
                ],
                'attendances' => $attendances,
                'instructors' => $instructors
            ]);
        } catch (\Exception $e) {
            Log::error('Error in getStatementOfAccount: ' . $e->getMessage());
            return response()->json([
                'error' => 'Enrollment not found',
                'message' => 'The requested enrollment could not be found.'
            ], 404);
        }
    }

    // Attendance page
    public function attendance(Request $request)
    {
        $programs = Program::all();
        $currentSession = $request->route('session', 1);

        // Get max sessions from first program
        $maxSessions = 1;
        if ($programs->first()) {
            $durationString = $programs->first()->duration ?? '1';
            if (is_numeric($durationString)) {
                $maxSessions = (int)$durationString;
            } else {
                preg_match('/(\d+)/', $durationString, $matches);
                if (!empty($matches[1])) {
                    $maxSessions = (int)$matches[1];
                }
            }
            $maxSessions = max(1, $maxSessions);
        }

        // Calculate student counts for each session
        $sessionStudentCounts = [];
        for ($i = 1; $i <= $maxSessions; $i++) {
            $count = Attendance::where('session_number', $i)->where('status', 'present')->count();
            $sessionStudentCounts[$i] = $count;
        }

        // Get all students with enrolled enrollments only
        $enrolledStudents = \App\Models\User::where('role', 'student')
            ->whereHas('enrollments', function($query) {
                $query->where('status', \App\Models\Enrollment::STATUS_ENROLLED);
            })
            ->with(['payments' => function($query) {
                $query->where('status', 'completed')->orderBy('payment_date', 'desc');
            }, 'enrollments' => function($query) {
                $query->where('status', \App\Models\Enrollment::STATUS_ENROLLED)->with('program');
            }, 'enrollments.attendances'])
            ->get()
            ->map(function($student) {
                // Filter payments to exclude registration fee payments for Reference Number display
                $student->session_payments = $student->payments->filter(function($payment) use ($student) {
                    // Get the student's enrollment to check registration fee
                    $enrollment = $student->enrollments->first();
                    if (!$enrollment || !$enrollment->program) {
                        return true; // Keep payment if no enrollment or program found
                    }

                    // Exclude payments that match the registration fee amount
                    $registrationFee = $enrollment->program->registration_fee ?? 0;
                    return $payment->amount != $registrationFee;
                });

                return $student;
            })
            ->map(function($student) use ($currentSession) {
                // Get the student's enrolled enrollment
                $enrollment = $student->enrollments->firstWhere(function($enrollment) {
                    return $enrollment->status === \App\Models\Enrollment::STATUS_ENROLLED;
                });

                // Get OR number from enrollment
                $orNumber = $enrollment ? $enrollment->or_number : null;

                // Get total paid sessions from actual session payments only (exclude registration fees)
                $paidSessions = 0;
                if ($enrollment) {
                    // Calculate from actual session payments only
                    $sessionPayments = $student->payments()
                        ->where('status', 'completed')
                        ->where('payment_type', 'session')
                        ->sum('session_count');
                    
                    // Also include manual OR number increments from enrollment
                    $manualPaidSessions = $enrollment->paid_sessions ?? 0;
                    
                    // Use the higher of the two (to account for manual OR number entries)
                    $paidSessions = max($sessionPayments, $manualPaidSessions);
                }

                // Check if student has been marked present in current session already
                $alreadyMarkedPresent = false;
                if ($enrollment) {
                    $alreadyMarkedPresent = \App\Models\Attendance::where('enrollment_id', $enrollment->id)
                        ->where('session_number', $currentSession)
                        ->where('status', 'present')
                        ->exists();
                }

                // Check attendance for previous session (if applicable)
                $hasAttendedPreviousSession = true;
                if ($currentSession > 1) {
                    if ($enrollment) {
                        $previousSessionAttendance = \App\Models\Attendance::where('enrollment_id', $enrollment->id)
                            ->where('session_number', $currentSession - 1)
                            ->where('status', 'present')
                            ->exists();

                        $hasAttendedPreviousSession = $previousSessionAttendance;
                    } else {
                        $hasAttendedPreviousSession = false;
                    }
                }

                // Determine if student should appear in this session
                if ($currentSession === 1) {
                    // Session 1: Show all students with approved enrollments who haven't been marked present yet
                    $showInSession = !$alreadyMarkedPresent;
                } else {
                    // Session N: Show students who attended Session N-1 and haven't been marked present in Session N yet
                    $showInSession = $hasAttendedPreviousSession && !$alreadyMarkedPresent;
                }

                // Check if student has OR number (required for eligibility)
                $hasOrNumber = !empty($orNumber);

                // Check if student has paid for this session (for payment status display)
                $hasPaidForSession = $currentSession <= $paidSessions;

                // If OR number is present, consider them as paid (overrides payment records)
                $effectivePaymentStatus = $hasOrNumber ? 'paid' : ($hasPaidForSession ? 'paid' : 'pending');

                // Can mark present if they appear in session and have OR number
                $canMarkPresent = $showInSession && $hasOrNumber;

                $paymentStatus = $effectivePaymentStatus;
                $orStatus = $hasOrNumber ? 'provided' : 'missing';

                $student->session_eligibility = [
                    'current_session' => $currentSession,
                    'paid_sessions' => $paidSessions,
                    'has_paid' => $hasPaidForSession,
                    'eligible' => $showInSession, // Show in session if attended previous or it's Session 1
                    'can_mark_present' => $canMarkPresent, // Can only mark present if OR number is provided
                    'payment_status' => $paymentStatus,
                    'has_attended_previous' => $hasAttendedPreviousSession,
                    'or_number' => $orNumber,
                    'has_or_number' => $hasOrNumber,
                    'or_status' => $orStatus,
                ];

                return $student;
            });

        // Count eligible students for session 1 display
        $session1StudentCount = $enrolledStudents->where('session_eligibility.eligible', true)->count();

        return view('Admin.attendance', compact('programs', 'enrolledStudents', 'currentSession', 'session1StudentCount', 'sessionStudentCounts', 'maxSessions'));
    }

    // Save attendance for a session
    public function saveAttendance(Request $request)
    {
        // Debug: Log all request data
        Log::info('=== ATTENDANCE SAVE REQUEST ===');
        Log::info('All request data: ' . json_encode($request->all()));
        Log::info('Session number: ' . $request->input('session_number'));
        Log::info('Attendance array: ' . json_encode($request->input('attendance', [])));

        try {
            $request->validate([
                'session_number' => 'required|integer|min:1',
                'attendance' => 'sometimes|array',
                'attendance.*' => 'integer|exists:users,id',
                'selected_time_slot' => 'required|string',
                'schedule_id' => 'required|integer|exists:schedules,id',
            ]);

            $sessionNumber = $request->input('session_number');
            $attendanceIds = $request->input('attendance', []);
            $selectedTimeSlot = $request->input('selected_time_slot');
            $scheduleId = $request->input('schedule_id');
            $savedCount = 0;
            $errors = [];

            // Parse the time slot (format: "start_time-end_time")
            $timeParts = explode('-', $selectedTimeSlot);
            $startTime = $timeParts[0] ?? null;
            $endTime = $timeParts[1] ?? null;

            // Get schedule details for additional validation
            $schedule = Schedule::find($scheduleId);
            if (!$schedule) {
                return redirect()->back()->with('error', 'Invalid schedule selected.');
            }

            Log::info("Processing {$sessionNumber} students for session {$sessionNumber}");
            Log::info("Time slot: {$selectedTimeSlot} (Start: {$startTime}, End: {$endTime})");

            foreach ($attendanceIds as $studentId) {
                Log::info("Processing student ID: {$studentId}");

                // Get the user by student ID
                $user = User::find($studentId);

                if (!$user) {
                    Log::error("User not found for ID: {$studentId}");
                    $errors[] = "User not found: {$studentId}";
                    continue;
                }

                Log::info("Found user: {$user->name} (ID: {$user->id})");

                // Query enrollment by student_id
                $enrollment = Enrollment::where('student_id', $user->id)
                    ->where('status', \App\Models\Enrollment::STATUS_ENROLLED)
                    ->first();

                if (!$enrollment) {
                    Log::error("No approved enrollment found for user ID: {$user->id}");
                    $errors[] = "No enrollment found for: {$user->name}";
                    continue;
                }

                Log::info("Found enrollment ID: {$enrollment->id} for user: {$user->name}");

                // Note: OR number validation removed as it's entered onsite and may not exist in database initially
                // The frontend will handle OR number requirement for eligibility display

                // Check if attendance already recorded for this session and enrollment
                $existingAttendance = Attendance::where('enrollment_id', $enrollment->id)
                    ->where('session_number', $sessionNumber)
                    ->first();

                if ($existingAttendance) {
                    Log::info("Attendance already exists for enrollment {$enrollment->id} session {$sessionNumber}");
                    continue;
                }

                try {
                    // Get OR number from enrollment or from online payment transaction
                    $orNumber = $enrollment->or_number;

                    // If no OR number in enrollment, check for online payment transaction ID
                    if (!$orNumber) {
                        $payment = Payment::where('student_id', $user->id)
                            ->where('status', 'completed')
                            ->where('session_count', '>=', $sessionNumber)
                            ->orderBy('payment_date', 'desc')
                            ->first();

                        if ($payment && $payment->transaction_id) {
                            $orNumber = $payment->transaction_id;
                        }
                    }

                    $attendance = Attendance::create([
                        'enrollment_id' => $enrollment->id,
                        'session_number' => $sessionNumber,
                        'session_date' => now()->toDateString(),
                        'start_time' => $startTime,
                        'end_time' => $endTime,
                        'status' => 'present',
                        'or_number' => $orNumber,
                        'marked_by_user_id' => Auth::id(),
                    ]);

                    // Clear OR number from enrollment after saving to attendance (per session payment)
                    // and increment paid sessions if OR number was provided
                    if ($enrollment->or_number) {
                        $enrollment->update([
                            'or_number' => null,
                            'paid_sessions' => ($enrollment->paid_sessions ?? 0) + 1
                        ]);
                    } elseif ($orNumber) {
                        // If OR number came from online payment, also increment paid sessions
                        $enrollment->update([
                            'paid_sessions' => ($enrollment->paid_sessions ?? 0) + 1
                        ]);
                    }

                    Log::info("Created attendance record ID: {$attendance->id}");
                    $savedCount++;
                } catch (\Exception $e) {
                    Log::error("Failed to create attendance record: " . $e->getMessage());
                    $errors[] = "Failed to save for: {$user->name}";
                }
            }

            Log::info("Attendance save completed. Saved: {$savedCount}, Errors: " . count($errors));

            // Check for students who have completed all sessions in their programs
            $completedStudents = collect();
            if ($savedCount > 0) {
                foreach ($attendanceIds as $studentId) {
                    $user = User::find($studentId);
                    if ($user) {
                        $enrollment = Enrollment::where('student_id', $user->id)
                            ->whereIn('status', ['approved', 'enrolled'])
                            ->with('program')
                            ->first();

                        if ($enrollment && $enrollment->program) {
                            // Get program duration
                            $durationString = $enrollment->program->duration ?? '1';
                            $programSessions = 1;

                            if (is_numeric($durationString)) {
                                $programSessions = (int)$durationString;
                            } else {
                                preg_match('/(\d+)/', $durationString, $matches);
                                if (!empty($matches[1])) {
                                    $programSessions = (int)$matches[1];
                                }
                            }

                            // Check if student has attended all sessions
                            $totalAttendance = Attendance::where('enrollment_id', $enrollment->id)
                                ->where('status', 'present')
                                ->count();

                            if ($totalAttendance >= $programSessions) {
                                $completedStudents->push([
                                    'student' => $user,
                                    'program' => $enrollment->program
                                ]);
                            }
                        }
                    }
                }
            }

            // Redirect to next session
            $nextSession = $sessionNumber + 1;

            if ($savedCount > 0) {
                $message = "Attendance saved successfully! {$savedCount} student(s) marked as present for Session {$sessionNumber}.";
                $message .= " Students who completed Session {$sessionNumber} are now eligible for Session {$nextSession}.";

                // Add completion message if any students finished their program
                if ($completedStudents->isNotEmpty()) {
                    $completionCount = $completedStudents->count();
                    $programNames = $completedStudents->pluck('program.name')->unique()->implode(', ');
                    $message .= " {$completionCount} student/s Completed All the Sessions in the Program {$programNames}.";
                }
            } else {
                $message = "No attendance records were saved.";
                if (!empty($errors)) {
                    $message .= " Errors: " . implode(', ', $errors);
                }
            }

            return redirect()->route('admin.attendance', ['session' => $nextSession])->with('success', $message);

        } catch (\Exception $e) {
            Log::error('Exception in saveAttendance: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return redirect()->back()->with('error', 'Error saving attendance: ' . $e->getMessage());
        }
    }

    // Attendance Records page
    public function attendanceRecords(Request $request)
    {
        $selectedProgram = $request->get('program_id');
        $selectedSession = $request->get('session_number', 1);

        // Get all programs for dropdown
        $programs = Program::all();

        // Get attendance records with relationships
        $query = Attendance::with(['enrollment.user', 'enrollment.program', 'markedByUser']);

        if ($selectedProgram) {
            $query->whereHas('enrollment.program', function($q) use ($selectedProgram) {
                $q->where('id', $selectedProgram);
            });
        }

        if ($selectedSession) {
            $query->where('session_number', $selectedSession);
        }

        $attendanceRecords = $query->orderBy('session_date', 'desc')
                                  ->orderBy('start_time', 'asc')
                                  ->get();

        // Process attendance records to fix payment display
        $attendanceRecords = $attendanceRecords->map(function($record) {
            // Fix marked by display
            if (!$record->markedByUser) {
                $record->marked_by_display = 'System';
            } else {
                $record->marked_by_display = $record->markedByUser->name;
            }

            // Fix payment amount - show per session amount
            if ($record->amount_paid) {
                $record->payment_amount_display = $record->enrollment->program->price_per_session ?? 300;
            } else {
                $record->payment_amount_display = $record->enrollment->program->price_per_session ?? 300;
            }

            // Fix reference number - use the same OR number for all sessions covered by a payment
            $record->reference_number_display = $record->or_number ?? $record->enrollment->or_number ?? 'N/A';

            return $record;
        });

        // Get unique session numbers for tabs
        $availableSessions = Attendance::select('session_number')
                                     ->distinct()
                                     ->orderBy('session_number')
                                     ->pluck('session_number');

        // Get instructors for the selected program
        $instructors = [];
        if ($selectedProgram) {
            $program = Program::find($selectedProgram);
            if ($program) {
                $instructors = $program->schedules->pluck('instructorUser.name')->unique()->filter()->values();
            }
        }

        return view('Admin.attendance_records', compact(
            'programs',
            'attendanceRecords',
            'availableSessions',
            'selectedProgram',
            'selectedSession',
            'instructors'
        ));
    }

    // Attendance Report page - Complete overview across all sessions
    public function attendanceReport(Request $request)
    {
        $selectedProgram = $request->get('program_id');

        // Get all programs for dropdown
        $programs = Program::all();

        $selectedProgramName = '';
        $attendanceMatrix = collect();
        $sessionSummary = collect();
        $totalStudents = 0;
        $totalSessions = 0;
        $totalAttendance = 0;
        $attendancePercentage = 0;
        $maxSessions = 0;

        if ($selectedProgram) {
            $program = Program::find($selectedProgram);
            if ($program) {
                $selectedProgramName = $program->name;
                // Extract numeric value from duration string (e.g., "10 weeks" -> 10)
                $durationString = $program->duration ?? '1';
                $maxSessions = 1; // Default fallback

                if (is_numeric($durationString)) {
                    $maxSessions = (int)$durationString;
                } else {
                    // Try to extract number from string like "10 weeks" or "2 months"
                    preg_match('/(\d+)/', $durationString, $matches);
                    if (!empty($matches[1])) {
                        $maxSessions = (int)$matches[1];
                    }
                }

                // Ensure maxSessions is at least 1
                $maxSessions = max(1, $maxSessions);

                // Get all enrollments for this program
                $enrollments = Enrollment::where('program_id', $selectedProgram)
                    ->whereIn('status', ['approved', 'enrolled'])
                    ->with(['user', 'attendances'])
                    ->get();

                $totalStudents = $enrollments->count();

                // Build attendance matrix
                $attendanceMatrix = $enrollments->map(function($enrollment) use ($maxSessions) {
                    $student = $enrollment->user;
                    $attendances = $enrollment->attendances;

                    $sessions = [];
                    $totalPresent = 0;

                    for ($i = 1; $i <= $maxSessions; $i++) {
                        $sessionAttendance = $attendances->firstWhere('session_number', $i);

                        if ($sessionAttendance) {
                            $sessions[$i] = [
                                'status' => $sessionAttendance->status,
                                'date' => $sessionAttendance->session_date,
                                'time' => $sessionAttendance->start_time
                            ];
                            if ($sessionAttendance->status === 'present') {
                                $totalPresent++;
                            }
                        } else {
                            $sessions[$i] = null;
                        }
                    }

                    $attendanceRate = $maxSessions > 0 ? round(($totalPresent / $maxSessions) * 100, 1) : 0;

                    return [
                        'id' => $student->id,
                        'name' => $student->name,
                        'email' => $student->email,
                        'photo' => $enrollment->photo,
                        'sessions' => $sessions,
                        'total_present' => $totalPresent,
                        'attendance_rate' => $attendanceRate
                    ];
                });

                // Calculate session summary
                $sessionSummary = collect();
                for ($i = 1; $i <= $maxSessions; $i++) {
                    $sessionAttendances = $enrollments->flatMap->attendances->where('session_number', $i);

                    $presentCount = $sessionAttendances->where('status', 'present')->count();
                    $attendanceRate = $totalStudents > 0 ? round(($presentCount / $totalStudents) * 100, 1) : 0;

                    // Get the most recent date for this session
                    $sessionDate = $sessionAttendances->sortByDesc('session_date')->first()?->session_date;

                    $sessionSummary->push([
                        'session_number' => $i,
                        'present_count' => $presentCount,
                        'total_students' => $totalStudents,
                        'attendance_rate' => $attendanceRate,
                        'date' => $sessionDate
                    ]);
                }

                // Calculate totals
                $totalSessions = $maxSessions;
                $totalAttendance = $attendanceMatrix->sum('total_present');
                $overallAttendanceRate = $attendanceMatrix->avg('attendance_rate');
                $attendancePercentage = round($overallAttendanceRate, 1);
            }
        }

        return view('Admin.attendance_report', compact(
            'programs',
            'selectedProgram',
            'selectedProgramName',
            'attendanceMatrix',
            'sessionSummary',
            'totalStudents',
            'totalSessions',
            'totalAttendance',
            'attendancePercentage',
            'maxSessions'
        ));
    }

    // Certificate management page
    public function certificates()
    {
        // Get issued certificates (only those not marked as done)
        $issuedCertificates = Certificate::with(['enrollment.user', 'enrollment.program'])
            ->where('status', 'issued')
            ->latest()
            ->paginate(10);

        // Get eligible students who have completed all sessions but don't have certificates yet
        $eligibleStudents = Enrollment::where('enrollments.status', Enrollment::STATUS_ENROLLED)
            ->join('programs', 'enrollments.program_id', '=', 'programs.id')
            ->leftJoin('attendances', function($join) {
                $join->on('enrollments.id', '=', 'attendances.enrollment_id')
                     ->where('attendances.status', '=', 'present');
            })
            ->leftJoin('certificates', 'enrollments.id', '=', 'certificates.enrollment_id')
            ->select('enrollments.id', 'enrollments.student_id', 'enrollments.program_id', 'enrollments.status', 'enrollments.created_at', 'enrollments.updated_at', 'programs.duration', DB::raw('COUNT(attendances.id) as attendance_count'))
            ->groupBy('enrollments.id', 'enrollments.student_id', 'enrollments.program_id', 'enrollments.status', 'enrollments.created_at', 'enrollments.updated_at', 'programs.duration')
            ->havingRaw('COUNT(attendances.id) = CASE
                WHEN programs.duration REGEXP "^[0-9]+$" THEN CAST(programs.duration AS UNSIGNED)
                ELSE CAST(REGEXP_SUBSTR(programs.duration, "[0-9]+") AS UNSIGNED)
            END')
            ->whereNull('certificates.id') // Only students without certificates
            ->with(['user', 'program'])
            ->latest()
            ->paginate(10);

        // Get all instructors for the modal
        $instructors = User::where('role', 'instructor')->get();

        return view('Admin.certificate', compact('issuedCertificates', 'eligibleStudents', 'instructors'));
    }

    // Generate and save certificate to database
    public function generateCertificate(Request $request, $enrollmentId)
    {
        try {
            $request->validate([
                'issue_date' => 'required|date',
                'instructor_name' => 'required|string|max:255'
            ]);

            $enrollment = Enrollment::with(['user', 'program'])->findOrFail($enrollmentId);

            // Check if certificate already exists for this enrollment
            $existingCertificate = Certificate::where('enrollment_id', $enrollmentId)->first();

            if ($existingCertificate) {
                return response()->json([
                    'success' => false,
                    'message' => 'Certificate already exists for this enrollment'
                ], 400);
            }

            // Generate unique certificate number
            $certificateNumber = 'CERT-' . date('Y') . '-' . str_pad($enrollmentId, 6, '0', STR_PAD_LEFT);

            // Create certificate record
            $certificate = Certificate::create([
                'enrollment_id' => $enrollmentId,
                'certificate_number' => $certificateNumber,
                'issued_by' => Auth::user()->name ?? 'System Admin',
                'issue_date' => $request->issue_date,
                'instructor_name' => $request->instructor_name,
                'status' => 'issued'
            ]);

            // Update enrollment status from 'enrolled' to 'completed'
            $enrollment->update(['status' => 'completed']);

            // Send email notification to student
            try {
                Mail::to($enrollment->user->email)->send(new \App\Mail\CertificateReadyMail($certificate));
            } catch (\Exception $e) {
                // Log email error but don't fail the certificate generation
                Log::error('Failed to send certificate email: ' . $e->getMessage());
            }

            return response()->json([
                'success' => true,
                'message' => 'Certificate generated successfully and email notification sent',
                'certificate_id' => $certificate->id,
                'certificate_number' => $certificateNumber
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate certificate: ' . $e->getMessage()
            ], 500);
        }
    }

    // PDF Generation for Certificate
    public function generateCertificatePdf($id)
    {
        $enrollment = Enrollment::with(['user', 'program'])->findOrFail($id);

        // Pass enrollment to a dedicated PDF blade view
        $pdf = PDF::loadView('Admin.certificate_pdf', [
            'studentName' => $enrollment->user->name,
            'programName' => $enrollment->program ? $enrollment->program->name : 'N/A',
            'completionDate' => $enrollment->completion_date,
        ]);

        return $pdf->download('certificate_' . $enrollment->user->student_id . '.pdf');
    }

    // Get student certificates for student view
    public function getStudentCertificates($studentId)
    {
        $certificates = Certificate::with(['enrollment.program'])
            ->whereHas('enrollment', function($query) use ($studentId) {
                $query->where('student_id', $studentId);
            })
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'certificates' => $certificates
        ]);
    }

    // Store a new program
    public function storeProgram(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'duration' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'price_per_session' => 'nullable|numeric|min:0',
            'registration_fee' => 'nullable|numeric|min:0',
        ]);

        Program::create($request->only(['name', 'duration', 'description', 'price_per_session', 'registration_fee']));

        return redirect()->back()->with('success', 'Program created successfully.');
    }

    // Get a specific program
    public function getProgram($id)
    {
        $program = Program::findOrFail($id);
        return response()->json($program);
    }

    // Update a specific program
    public function updateProgram(Request $request, $id)
    {
        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'duration' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'price_per_session' => 'sometimes|nullable|numeric|min:0',
            'registration_fee' => 'sometimes|nullable|numeric|min:0',
        ]);

        $program = Program::findOrFail($id);
        $program->update($request->only(['name', 'duration', 'description', 'price_per_session', 'registration_fee']));

        return response()->json(['success' => true, 'message' => 'Program updated successfully.']);
    }

    // Toggle program status (activate/deactivate)
    public function toggleProgramStatus($id)
    {
        $program = Program::findOrFail($id);

        if ($program->status === 'active') {
            $program->update(['status' => 'inactive']);
            $message = 'Program deactivated successfully.';
        } else {
            $program->update(['status' => 'active']);
            $message = 'Program activated successfully.';
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'new_status' => $program->status
        ]);
    }

    // Store a new schedule
    public function storeSchedule(Request $request)
    {
        $request->validate([
            'program_id' => 'required|exists:programs,id',
            'day' => 'required|string|max:255',
            'start_time' => 'required',
            'end_time' => 'required',
        ]);

        Schedule::create($request->only(['program_id', 'day', 'start_time', 'end_time']));

        return response()->json(['success' => true]);
    }

    // Get schedules for a specific program
    public function getSchedulesForProgram($programId)
    {
        try {
            $schedules = Schedule::where('program_id', $programId)
                ->orderBy('day')
                ->orderBy('start_time')
                ->get();

            return response()->json([
                'success' => true,
                'schedules' => $schedules
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching schedules: ' . $e->getMessage()
            ], 500);
        }
    }

    // Generate QR codes
    public function generateQrCodes(Request $request)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:100',
            'type' => 'required|in:enrollment,attendance'
        ]);

        try {
            $quantity = $request->input('quantity');
            $type = $request->input('type');

            $qrCodes = \App\Models\QrCode::generateMultiple($quantity, $type);

            return response()->json([
                'success' => true,
                'qr_codes' => $qrCodes,
                'message' => "$quantity QR codes generated successfully"
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate QR codes: ' . $e->getMessage()
            ], 500);
        }
    }

    // Update OR number for enrollment
    public function updateOrNumber(Request $request, $enrollmentId)
    {
        $request->validate([
            'or_number' => 'nullable|string|max:255',
        ]);

        try {
            $enrollment = Enrollment::findOrFail($enrollmentId);

            $newOrNumber = $request->input('or_number');
            $oldOrNumber = $enrollment->or_number;

            // Check if OR number is being added (not just updated)
            $isNewOrNumber = empty($oldOrNumber) && !empty($newOrNumber);

            // Check if OR number is being cleared (set to empty)
            $isClearingOrNumber = !empty($oldOrNumber) && empty($newOrNumber);

            $enrollment->update([
                'or_number' => $newOrNumber
            ]);

            // If OR number is being added for the first time, increment paid_sessions
            // BUT ONLY if this is for a session payment, not a registration fee
            if ($isNewOrNumber) {
                // Check if this OR number is for a session payment or registration fee
                // We'll assume it's for a session payment unless explicitly marked otherwise
                // This should be updated to have a field to distinguish between registration and session OR numbers
                $enrollment->increment('paid_sessions');

                // If paid_sessions > 0, change status to 'enrolled'
                if ($enrollment->paid_sessions > 0) {
                    $enrollment->update(['status' => 'enrolled']);
                }
            }

            // If OR number is being cleared, reset payment status
            if ($isClearingOrNumber) {
                // Decrement paid_sessions by 1 but not below 0
                if ($enrollment->paid_sessions > 0) {
                    $enrollment->decrement('paid_sessions');
                }

                // If no paid sessions remain, change status back to 'approved'
                if ($enrollment->paid_sessions <= 0) {
                    $enrollment->update(['status' => 'approved']);
                }
            }

            return response()->json([
                'success' => true,
                'message' => empty($newOrNumber) ? 'OR number cleared successfully. Payment status reset to pending.' : 'OR number updated successfully',
                'or_number' => $newOrNumber
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update OR number: ' . $e->getMessage()
            ], 500);
        }
    }

    // Set OR number for approved enrollment (used in enrollment management)
    public function setOrNumber(Request $request, $enrollmentId)
    {
        $request->validate([
            'or_number' => 'required|string|max:255',
        ]);

        try {
            $enrollment = Enrollment::findOrFail($enrollmentId);

            // Verify enrollment is in approved status
            if ($enrollment->status !== 'approved') {
                return response()->json([
                    'success' => false,
                    'message' => 'Only approved enrollments can have OR numbers set'
                ], 400);
            }

            // Check if OR number is already set
            if (!empty($enrollment->or_number)) {
                return response()->json([
                    'success' => false,
                    'message' => 'OR number is already set for this enrollment'
                ], 400);
            }

            $enrollment->update([
                'or_number' => $request->input('or_number'),
                'paid_sessions' => 1, // Set to 1 since OR number indicates first payment
                'status' => 'enrolled' // Change status to enrolled once OR number is set
            ]);

            return response()->json([
                'success' => true,
                'message' => 'OR number set successfully. Student status updated to enrolled.',
                'or_number' => $request->input('or_number')
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to set OR number: ' . $e->getMessage()
            ], 500);
        }
    }

    // Get pending onsite payments for admin verification
    public function getPendingOnsitePayments()
    {
        $pendingOnsitePayments = Enrollment::where('status', 'approved')
            ->whereNotNull('or_number')
            ->where('or_number', '!=', '')
            ->with(['user', 'program'])
            ->latest()
            ->paginate(10);

        return response()->json([
            'success' => true,
            'pending_payments' => $pendingOnsitePayments
        ]);
    }

    // Confirm onsite payment and change status to enrolled
    public function confirmOnsitePayment(Request $request, $enrollmentId)
    {
        $request->validate([
            'notes' => 'nullable|string|max:500',
        ]);

        try {
            $enrollment = Enrollment::findOrFail($enrollmentId);

            // Verify enrollment is in approved status and has OR number
            if ($enrollment->status !== 'approved') {
                return response()->json([
                    'success' => false,
                    'message' => 'Only approved enrollments can be confirmed for onsite payment'
                ], 400);
            }

            if (empty($enrollment->or_number)) {
                return response()->json([
                    'success' => false,
                    'message' => 'OR number is required for onsite payment confirmation'
                ], 400);
            }

        // Update enrollment status to enrolled and mark registration fee as paid
        $enrollment->update([
            'status' => 'enrolled',
            'registration_fee_paid' => true, // Mark registration fee as paid for onsite payments
            'payment_confirmed_at' => now(),
            'payment_confirmed_by' => Auth::id(),
            'payment_notes' => $request->input('notes'),
        ]);

            // Create payment record for onsite payment
            Payment::create([
                'student_id' => $enrollment->student_id,
                'enrollment_id' => $enrollment->id,
                'amount' => $enrollment->program->registration_fee ?? 0,
                'payment_method' => 'onsite',
                'transaction_id' => $enrollment->or_number,
                'status' => 'completed',
                'payment_date' => now(),
                'session_count' => 1, // Initial payment covers first session
                'notes' => 'Onsite payment confirmed by admin: ' . ($request->input('notes') ?? 'N/A'),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Onsite payment confirmed successfully. Student status updated to enrolled.',
                'enrollment' => $enrollment->load(['user', 'program'])
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to confirm onsite payment: ' . $e->getMessage()
            ], 500);
        }
    }

    // Reject onsite payment (if verification fails)
    public function rejectOnsitePayment(Request $request, $enrollmentId)
    {
        $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        try {
            $enrollment = Enrollment::findOrFail($enrollmentId);

            // Verify enrollment is in approved status
            if ($enrollment->status !== 'approved') {
                return response()->json([
                    'success' => false,
                    'message' => 'Only approved enrollments can be rejected for onsite payment'
                ], 400);
            }

            // Clear OR number and add rejection note
            $enrollment->update([
                'or_number' => null,
                'paid_sessions' => 0,
                'payment_rejected_at' => now(),
                'payment_rejected_by' => Auth::id(),
                'payment_rejection_reason' => $request->input('reason'),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Onsite payment rejected. OR number cleared and student must provide new payment.',
                'enrollment' => $enrollment->load(['user', 'program'])
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to reject onsite payment: ' . $e->getMessage()
            ], 500);
        }
    }

    // Store manual enrollment
    public function storeManualEnrollment(Request $request)
    {
        Log::info('=== MANUAL ENROLLMENT START ===');
        Log::info('Request data:', $request->all());

        $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'middle_name' => 'nullable|string|max:100',
            'suffix_name' => 'nullable|string|max:10',
            'birthdate' => 'required|date',
            'gender' => 'required|string|max:10',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'program_id' => 'required|exists:programs,id',
            'address' => 'nullable|string|max:255',
            'status' => 'required|in:enrolled,approved',
        ]);

        Log::info('Validation passed');

        try {
            // Calculate age from birthdate
            $age = \Carbon\Carbon::parse($request->birthdate)->diffInYears(now());
            Log::info('Calculated age: ' . $age);

            // Create user account
            $fullName = $request->first_name . ' ' . $request->last_name . ' ' . $request->suffix_name;
            $username = str_replace(' ', '', strtolower($fullName));
            $birthdate = $request->birthdate;

            Log::info('Creating user with data:', [
                'name' => $fullName,
                'username' => $username,
                'email' => $request->email,
                'role' => 'student',
            ]);

            $user = User::create([
                'name' => $fullName,
                'username' => $username,
                'email' => $request->email,
                'password' => Hash::make($birthdate),
                'role' => 'student',
            ]);

            Log::info('User created successfully with ID: ' . $user->id);

            // Calculate batch number
            $batch_number = \App\Services\BatchNumberService::calculateBatchNumber(now());
            Log::info('Calculated batch number: ' . $batch_number);

            // Prepare enrollment data
            $enrollmentData = [
                'student_id' => $user->id,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'middle_name' => $request->middle_name,
                'suffix_name' => $request->suffix_name,
                'birthdate' => $request->birthdate,
                'age' => $age,
                'gender' => $request->gender,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'program_id' => $request->program_id,
                'batch_number' => $batch_number,
                'status' => $request->status,
                'photo' => null, // Manual enrollment doesn't include photo upload
                'registration_fee_paid' => true, // Manual enrollment assumes payment is handled
                'approved_at' => $request->status === 'approved' ? now() : null,
                'approved_by' => $request->status === 'approved' ? Auth::id() : null,
            ];

            Log::info('Creating enrollment with data:', $enrollmentData);

            // Create enrollment with selected status
            $enrollment = Enrollment::create($enrollmentData);

            Log::info('Enrollment created successfully with ID: ' . $enrollment->id);

            // If status is approved, create a payment record
            if ($request->status === 'approved') {
                Log::info('Creating payment record for approved enrollment');

                $paymentData = [
                    'student_id' => $user->id,
                    'enrollment_id' => $enrollment->id,
                    'amount' => $enrollment->program->registration_fee ?? 0,
                    'payment_method' => 'manual',
                    'status' => 'completed',
                    'payment_date' => now(),
                    'session_count' => 1,
                    'notes' => 'Manual enrollment - approved by admin',
                ];

                Log::info('Payment data:', $paymentData);

                Payment::create($paymentData);

                Log::info('Payment record created successfully');
            }

            Log::info('=== MANUAL ENROLLMENT COMPLETED SUCCESSFULLY ===');

            return response()->json([
                'success' => true,
                'message' => 'Student enrolled successfully!',
                'username' => $username,
                'password' => $birthdate,
                'status' => $request->status,
                'redirect_url' => route('admin.enrollments') . '?tab=' . ($request->status === 'approved' ? 'approved' : 'enrolled')
            ]);

        } catch (\Exception $e) {
            Log::error('=== MANUAL ENROLLMENT ERROR ===');
            Log::error('Error message: ' . $e->getMessage());
            Log::error('Error file: ' . $e->getFile());
            Log::error('Error line: ' . $e->getLine());
            Log::error('Stack trace: ' . $e->getTraceAsString());

            return response()->json([
                'success' => false,
                'message' => 'Failed to enroll student: ' . $e->getMessage()
            ], 500);
        }
    }

    // Update enrollment status (for approved enrollments)
    public function updateEnrollmentStatus(Request $request, Enrollment $enrollment)
    {
        $request->validate([
            'status' => 'required|in:approved,enrolled',
        ]);

        try {
            $newStatus = $request->input('status');

            // Only allow status changes for approved enrollments
            if ($enrollment->status !== 'approved') {
                return response()->json([
                    'success' => false,
                    'message' => 'Only approved enrollments can have their status updated'
                ], 400);
            }

            // Update the enrollment status
            $enrollment->update([
                'status' => $newStatus,
                'updated_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Enrollment status updated successfully',
                'new_status' => $newStatus
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update enrollment status: ' . $e->getMessage()
            ], 500);
        }
    }

    // Verify user password for security-sensitive operations
    public function verifyPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string',
        ]);

        try {
            $user = Auth::user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }

            // Check if the provided password matches the user's password
            if (\Illuminate\Support\Facades\Hash::check($request->input('password'), $user->password)) {
                return response()->json([
                    'success' => true,
                    'message' => 'Password verified successfully'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Incorrect password'
                ], 401);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Password verification failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the authenticated admin's profile photo.
     */
    public function updateProfilePhoto(Request $request)
    {
        $request->validate([
            'profile_photo' => 'required|image|max:2048', // max 2MB
        ]);

        $admin = Auth::user();

        if ($request->hasFile('profile_photo')) {
            $file = $request->file('profile_photo');
            $path = $file->store('profile_photos', 'public');

            // Update admin's photo path
            $admin->photo = $path;
            $admin->save();

            return response()->json(['success' => true, 'path' => $path]);
        }

        return response()->json(['success' => false, 'message' => 'No file uploaded']);
    }

    /**
     * Send OTP to email for verification
     */
    public function sendEmailOTP(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $email = $request->input('email');
        $user = Auth::user();

        // Check if email is already taken by another user
        $existingUser = User::where('email', $email)->where('id', '!=', $user->id)->first();
        if ($existingUser) {
            return response()->json([
                'success' => false,
                'message' => 'This email address is already in use by another user.'
            ], 400);
        }

        // Generate 6-digit OTP
        $otp = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);

        // Store OTP in session with expiry (5 minutes)
        session([
            'email_otp' => $otp,
            'email_otp_expires' => now()->addMinutes(5),
            'email_otp_email' => $email,
        ]);

        try {
            // Send OTP email
            \Illuminate\Support\Facades\Mail::raw(
                "Your email verification code is: {$otp}\n\nThis code will expire in 5 minutes.\n\nIf you did not request this change, please ignore this email.",
                function ($message) use ($email) {
                    $message->to($email)
                            ->subject('Email Verification Code - Admin Profile Update');
                }
            );

            return response()->json([
                'success' => true,
                'message' => 'Verification code sent to your email address.'
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to send OTP email: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to send verification code. Please try again.'
            ], 500);
        }
    }

    /**
     * Verify OTP and update email
     */
    public function verifyEmailOTP(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|string|size:6',
        ]);

        $email = $request->input('email');
        $otp = $request->input('otp');
        $user = Auth::user();

        // Check if OTP exists in session
        if (!session()->has('email_otp') || !session()->has('email_otp_expires') || !session()->has('email_otp_email')) {
            return response()->json([
                'success' => false,
                'message' => 'No verification code found. Please request a new code.'
            ], 400);
        }

        // Check if OTP has expired
        if (now()->isAfter(session('email_otp_expires'))) {
            // Clear expired OTP
            session()->forget(['email_otp', 'email_otp_expires', 'email_otp_email']);
            return response()->json([
                'success' => false,
                'message' => 'Verification code has expired. Please request a new code.'
            ], 400);
        }

        // Check if email matches the one in session
        if ($email !== session('email_otp_email')) {
            return response()->json([
                'success' => false,
                'message' => 'Email address does not match the verification request.'
            ], 400);
        }

        // Verify OTP
        if ($otp !== session('email_otp')) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid verification code. Please try again.'
            ], 400);
        }

        try {
            // Update user's email
            $user->email = $email;
            $user->email_verified_at = now();
            $user->save();

            // Clear OTP from session
            session()->forget(['email_otp', 'email_otp_expires', 'email_otp_email']);

            return response()->json([
                'success' => true,
                'message' => 'Email address updated successfully!'
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to update email: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update email address. Please try again.'
            ], 500);
        }
    }

    /**
     * Update the authenticated admin's phone number.
     */
    public function updatePhoneNumber(Request $request)
    {
        $request->validate([
            'phone' => 'nullable|string|max:20',
        ]);

        $user = Auth::user();

        try {
            $user->phone = $request->input('phone');
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Phone number updated successfully!',
                'phone' => $request->input('phone')
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to update phone number: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update phone number. Please try again.'
            ], 500);
        }
    }

    /**
     * Update the authenticated admin's gender.
     */
    public function updateGender(Request $request)
    {
        $request->validate([
            'gender' => 'required|string|in:male,female,other',
        ]);

        $user = Auth::user();

        try {
            $user->gender = $request->input('gender');
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Gender updated successfully!',
                'gender' => $request->input('gender')
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to update gender: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update gender. Please try again.'
            ], 500);
        }
    }

    /**
     * Update the authenticated admin's birthdate.
     */
    public function updateBirthdate(Request $request)
    {
        $request->validate([
            'birthdate' => 'required|date|before:today',
        ]);

        $user = Auth::user();

        try {
            $user->birthdate = $request->input('birthdate');
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Birthdate updated successfully!',
                'birthdate' => $request->input('birthdate')
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to update birthdate: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update birthdate. Please try again.'
            ], 500);
        }
    }

    /**
     * Update amount paid for a specific student session
     */
    public function updateAmountPaid(Request $request)
    {
        try {
            $request->validate([
                'student_id' => 'required|integer',
                'session' => 'required|integer',
                'amount' => 'required|numeric|min:0'
            ]);

            $studentId = $request->student_id;
            $session = $request->session;
            $amount = $request->amount;

            // Find the student
            $student = User::findOrFail($studentId);
            
            // Get the enrollment
            $enrollment = $student->enrollments()->where('status', 'enrolled')->first();
            
            if (!$enrollment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Student enrollment not found'
                ], 404);
            }

            // Find or create attendance record for this session
            $attendance = Attendance::where('enrollment_id', $enrollment->id)
                ->where('session_number', $session)
                ->first();

            if ($attendance) {
                // Update existing attendance record
                $attendance->update(['amount_paid' => $amount]);
            } else {
                // Create new attendance record with amount_paid
                Attendance::create([
                    'enrollment_id' => $enrollment->id,
                    'session_number' => $session,
                    'amount_paid' => $amount,
                    'status' => 'pending', // Not marked as present yet
                    'session_date' => now(),
                ]);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Amount updated successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Error updating amount paid: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update amount. Please try again.'
            ], 500);
        }
    }

    // Get QR codes grouped by date
    public function getQrCodesByDate()
    {
        $qrCodesByDate = DB::table('qr_codes')
            ->select(DB::raw('DATE(generated_at) as date'), DB::raw('COUNT(*) as count'))
            ->groupBy(DB::raw('DATE(generated_at)'))
            ->orderBy('date', 'desc')
            ->get();

        $qrCodesDetails = [];
        foreach ($qrCodesByDate as $dateGroup) {
            $qrCodes = DB::table('qr_codes')
                ->whereDate('generated_at', $dateGroup->date)
                ->orderBy('generated_at', 'desc')
                ->get();

            $qrCodesDetails[] = [
                'date' => $dateGroup->date,
                'count' => $dateGroup->count,
                'qr_codes' => $qrCodes
            ];
        }

        return response()->json([
            'success' => true,
            'data' => $qrCodesDetails
        ]);
    }

    // Get QR codes for a specific date
    public function getQrCodesForDate(Request $request)
    {
        $date = $request->input('date');
        
        $qrCodes = DB::table('qr_codes')
            ->whereDate('generated_at', $date)
            ->orderBy('generated_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'qr_codes' => $qrCodes
        ]);
    }

    // Mark certificate as done
    public function markCertificateAsDone($certificateId)
    {
        try {
            $certificate = Certificate::findOrFail($certificateId);
            $certificate->update(['status' => 'done']);
            
            return response()->json([
                'success' => true,
                'message' => 'Certificate marked as done successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to mark certificate as done: ' . $e->getMessage()
            ], 500);
        }
    }

    // Get all students with issued certificates
    public function getStudentRecords()
    {
        try {
            // Get all certificates with their related data
            $certificates = Certificate::with([
                'enrollment.user',
                'enrollment.program'
            ])->get();

            $students = $certificates->map(function ($certificate) {
                $enrollment = $certificate->enrollment;
                if (!$enrollment) {
                    return null;
                }
                
                $user = $enrollment->user;
                if (!$user) {
                    return null;
                }
                
                $program = $enrollment->program;
                if (!$program) {
                    return null;
                }
                
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'student_id' => $user->student_id,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'program_name' => $program->name,
                    'certificate_issue_date' => $certificate->issue_date,
                    'certificate_number' => $certificate->certificate_number,
                ];
            })->filter(); // Remove null values

            return response()->json([
                'success' => true,
                'students' => $students
            ]);
        } catch (\Exception $e) {
            Log::error('Student records error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to load student records: ' . $e->getMessage()
            ], 500);
        }
    }

    // Get comprehensive student details
    public function getStudentDetails($studentId)
    {
        try {
            $user = User::with([
                'enrollments.program',
                'enrollments.certificate',
                'enrollments.attendances' => function($query) {
                    $query->orderBy('session_number');
                },
                'payments' => function($query) {
                    $query->orderBy('created_at', 'desc');
                }
            ])->findOrFail($studentId);

            // Debug: Log enrollment statuses
            Log::info('Student enrollments:', [
                'student_id' => $studentId,
                'enrollments' => $user->enrollments->map(function($enrollment) {
                    return [
                        'id' => $enrollment->id,
                        'status' => $enrollment->status,
                        'has_certificate' => $enrollment->certificate ? true : false,
                        'certificate_id' => $enrollment->certificate ? $enrollment->certificate->id : null
                    ];
                })->toArray()
            ]);

            // Look for enrollment with certificate first, then fallback to any enrollment
            $enrollment = $user->enrollments->where('status', 'completed')->first();
            if (!$enrollment) {
                // If no 'complete' status, look for any enrollment that has a certificate
                $enrollment = $user->enrollments->filter(function($enrollment) {
                    return $enrollment->certificate !== null;
                })->first();
            }
            if (!$enrollment) {
                // If still no enrollment, get the most recent one
                $enrollment = $user->enrollments->first();
            }
            if (!$enrollment) {
                return response()->json([
                    'success' => false,
                    'message' => 'No enrollment found for this student'
                ], 404);
            }
            
            $certificate = $enrollment->certificate;
            if (!$certificate) {
                return response()->json([
                    'success' => false,
                    'message' => 'No certificate found for this student'
                ], 404);
            }

            $studentData = [
                'id' => $user->id,
                'name' => $user->name,
                'student_id' => $user->student_id,
                'email' => $user->email,
                'phone' => $enrollment->phone ?? $user->phone,
                'photo' => $enrollment->photo ?? $user->photo,
                'birthdate' => $enrollment->birthdate,
                'enrollment_date' => $enrollment->created_at,
                'program_name' => $enrollment->program->name,
                'program_duration' => $enrollment->program->duration,
                'attendances' => $enrollment->attendances->map(function($attendance) {
                    // Get payment information for reference number
                    $payment = null;
                    $referenceNumber = $attendance->or_number;
                    $markedBy = 'System';
                    
                    if ($attendance->or_number) {
                        $payment = \App\Models\Payment::where('transaction_id', $attendance->or_number)
                            ->first();
                        
                        if ($payment) {
                            $referenceNumber = $payment->transaction_id;
                        } else {
                            // If no payment found, use the or_number from attendance as reference
                            $referenceNumber = $attendance->or_number;
                        }
                    }
                    
                    // Get marked by information
                    if ($attendance->marked_by_user_id) {
                        $markedByUser = \App\Models\User::find($attendance->marked_by_user_id);
                        if ($markedByUser) {
                            $markedBy = $markedByUser->name;
                        }
                    }
                    
                    return [
                        'session_number' => $attendance->session_number,
                        'date' => $attendance->session_date,
                        'status' => $attendance->status,
                        'amount_paid' => $attendance->amount_paid,
                        'reference_number' => $referenceNumber,
                        'marked_by' => $markedBy,
                    ];
                }),
                'payments' => $user->payments->map(function($payment) {
                    return [
                        'amount' => $payment->amount,
                        'payment_method' => $payment->payment_method,
                        'or_number' => $payment->transaction_id ?: 'N/A',
                        'status' => $payment->status,
                        'created_at' => $payment->created_at,
                    ];
                }),
                'certificate_id' => $certificate->id,
                'certificate_number' => $certificate->certificate_number,
                'certificate_issue_date' => $certificate->issue_date,
                'certificate_issued_by' => $certificate->issued_by,
                'certificate_instructor' => $certificate->instructor_name,
            ];

            return response()->json([
                'success' => true,
                'student' => $studentData
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load student details: ' . $e->getMessage()
            ], 500);
        }
    }

    // Get enrollment details for the "View More" modal
    public function getStudentEnrollmentDetails($studentId)
    {
        try {
            // Find the user and their enrollment
            $user = User::findOrFail($studentId);
            
            // Get the enrollment with all related data including attendances
            $enrollment = Enrollment::with(['program', 'user', 'attendances' => function($query) {
                $query->orderBy('session_number');
            }])
                ->where('student_id', $studentId)
                ->first();
            
            if (!$enrollment) {
                return response()->json([
                    'success' => false,
                    'message' => 'No enrollment found for this student'
                ], 404);
            }

            // Prepare enrollment data
            $enrollmentData = [
                'id' => $enrollment->id,
                'user_id' => $enrollment->student_id,
                'first_name' => $enrollment->first_name,
                'middle_name' => $enrollment->middle_name,
                'last_name' => $enrollment->last_name,
                'suffix_name' => $enrollment->suffix_name,
                'email' => $enrollment->email,
                'phone' => $enrollment->phone,
                'birthdate' => $enrollment->birthdate,
                'age' => $enrollment->age,
                'gender' => $enrollment->gender,
                'civil_status' => $enrollment->civil_status,
                'spouse_name' => $enrollment->spouse_name,
                'citizenship' => $enrollment->citizenship,
                'religion' => $enrollment->religion,
                'place_of_birth' => $enrollment->place_of_birth,
                'father_name' => $enrollment->father_name,
                'mother_name' => $enrollment->mother_name,
                'guardian' => $enrollment->guardian,
                'guardian_contact' => $enrollment->guardian_contact,
                'address' => $enrollment->address,
                'program_name' => $enrollment->program ? $enrollment->program->name : 'N/A',
                'enrollment_date' => $enrollment->created_at,
                'status' => $enrollment->status,
                'registration_fee' => $enrollment->program ? $enrollment->program->registration_fee : 0,
                'attendance_percentage' => $enrollment->attendance_percentage ?? 0,
                'payment_status' => $enrollment->payment_status ?? 'Pending',
                'certificate_eligible' => $enrollment->isEligibleForCertificate(),
                'updated_at' => $enrollment->updated_at,
                'photo' => $enrollment->photo ? asset('storage/' . $enrollment->photo) : null,
                'attendances' => $enrollment->attendances->map(function($attendance) {
                    // Get payment information for reference number
                    $payment = null;
                    $referenceNumber = $attendance->or_number;
                    $markedBy = 'System';
                    
                    if ($attendance->or_number) {
                        $payment = \App\Models\Payment::where('transaction_id', $attendance->or_number)
                            ->first();
                        
                        if ($payment) {
                            $referenceNumber = $payment->transaction_id;
                        } else {
                            // If no payment found, use the or_number from attendance as reference
                            $referenceNumber = $attendance->or_number;
                        }
                    }
                    
                    // Get marked by information
                    if ($attendance->marked_by_user_id) {
                        $markedByUser = \App\Models\User::find($attendance->marked_by_user_id);
                        if ($markedByUser) {
                            $markedBy = $markedByUser->name;
                        }
                    }
                    
                    return [
                        'session_number' => $attendance->session_number,
                        'date' => $attendance->session_date,
                        'status' => $attendance->status,
                        'amount_paid' => $attendance->amount_paid,
                        'reference_number' => $referenceNumber,
                        'marked_by' => $markedBy,
                    ];
                }),
            ];

            return response()->json([
                'success' => true,
                'enrollment' => $enrollmentData
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching enrollment details: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to load enrollment details: ' . $e->getMessage()
            ], 500);
        }
    }

    // Get certificate details for preview
    public function getCertificateDetails($certificateId)
    {
        try {
            $certificate = Certificate::with(['enrollment.user', 'enrollment.program'])
                ->findOrFail($certificateId);

            $certificateData = [
                'id' => $certificate->id,
                'student_name' => $certificate->enrollment->user->name,
                'program_name' => $certificate->enrollment->program->name,
                'issue_date' => $certificate->issue_date,
                'certificate_number' => $certificate->certificate_number,
                'issued_by' => $certificate->issued_by,
                'instructor_name' => $certificate->instructor_name,
            ];

            return response()->json([
                'success' => true,
                'certificate' => $certificateData
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load certificate details: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update admin password
     */
    public function updatePassword(Request $request)
    {
        try {
            $request->validate([
                'current_password' => 'required',
                'new_password' => 'required|min:8|confirmed',
                'new_password_confirmation' => 'required'
            ]);

            $user = Auth::user();

            // Verify current password
            if (!Hash::check($request->current_password, $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Current password is incorrect.'
                ], 400);
            }

            // Check if new password is different from current
            if (Hash::check($request->new_password, $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'New password must be different from current password.'
                ], 400);
            }

            // Update password
            $hashedPassword = Hash::make($request->new_password);
            $user->password = $hashedPassword;
            $saved = $user->save();

            // Log the update attempt
            Log::info('Password update attempt', [
                'user_id' => $user->id,
                'saved' => $saved,
                'password_updated_at' => $user->updated_at,
                'password_hash_length' => strlen($hashedPassword),
                'password_hash_start' => substr($hashedPassword, 0, 10) . '...'
            ]);

            // Verify the password was actually saved by reloading from database
            $user->refresh();
            $passwordMatches = Hash::check($request->new_password, $user->password);
            
            Log::info('Password verification after save', [
                'user_id' => $user->id,
                'password_matches' => $passwordMatches,
                'stored_hash_length' => strlen($user->password)
            ]);

            if (!$saved) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to save password to database.'
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Password updated successfully!'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed: ' . implode(', ', $e->validator->errors()->all())
            ], 422);
        } catch (\Exception $e) {
            Log::error('Password update error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update password. Please try again.'
            ], 500);
        }
    }
}
