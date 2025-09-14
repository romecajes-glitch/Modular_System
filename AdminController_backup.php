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
use Barryvdh\DomPDF\Facade\Pdf;

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

        return view('Admin.dashboard', compact(
            'totalStudents',
            'activeEnrollments',
            'instructors',
            'certificatesIssued',
            'recentEnrollments',
            'recentCertificates'
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
            'rejected_at' => null,
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
            'rejected_at' => now(),
            'rejection_reason' => $request->input('reason'),
        ]);
        return response()->json(['success' => true, 'message' => 'Enrollment rejected successfully']);
    }

    //Enrolled Students
    public function enrolledStudent()
    {
        $students = \App\Models\User::where('role', 'student')->get();

        // Get students who have made payments with pagination, including their enrollments
        $studentsWithPayments = \App\Models\User::where('role', 'student')
            ->whereHas('payments', function($query) {
                $query->where('status', 'completed');
            })
            ->with(['payments' => function($query) {
                $query->where('status', 'completed')->orderBy('payment_date', 'desc');
            }, 'enrollments' => function($query) {
                $query->where('status', 'approved')->with('program');
            }])
            ->paginate(10);

        return view('Admin.enrolled_student', compact('students', 'studentsWithPayments'));
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
        $cashiers = User::where('role', 'cashier')->get();
        $programs = Program::all(); // <-- Add this line
        return view('Admin.user_management', compact('students', 'instructors', 'cashiers', 'programs')); // <-- Add 'programs'
    }

    // Reports (payments, attendance, etc.)
    public function reports(Request $request)
    {
        $dateRange = $request->input('date_range', 'all');
        $userRole = $request->input('user_role', 'all');
        $status = $request->input('status', 'all');

        $enrollmentsQuery = Enrollment::query();
        $usersQuery = User::query();

        if ($dateRange !== 'all') {
            switch ($dateRange) {
                case '7days':
                    $enrollmentsQuery->where('created_at', '>=', now()->subDays(7));
                    $usersQuery->where('created_at', '>=', now()->subDays(7));
                    break;
                case '30days':
                    $enrollmentsQuery->where('created_at', '>=', now()->subDays(30));
                    $usersQuery->where('created_at', '>=', now()->subDays(30));
                    break;
                case '90days':
                    $enrollmentsQuery->where('created_at', '>=', now()->subDays(90));
                    $usersQuery->where('created_at', '>=', now()->subDays(90));
                    break;
            }
        }

        if ($userRole !== 'all') {
            $usersQuery->where('role', $userRole);
        }

        if ($status !== 'all') {
            $enrollmentsQuery->where('status', $status);
            $usersQuery->where('status', $status);
        }

        $totalEnrollments = $enrollmentsQuery->count();
        $totalUsers = $usersQuery->count();
        $totalStudents = User::where('role', 'student')->count();
        $totalInstructors = User::where('role', 'instructor')->count();
        $totalCashiers = User::where('role', 'cashier')->count();
        $totalCertificates = Certificate::count();
        $totalPrograms = Program::count();

        $enrollmentByMonth = Enrollment::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

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

        return view('Admin.reports', compact(
            'totalEnrollments',
            'totalUsers',
            'totalStudents',
            'totalInstructors',
            'totalCashiers',
            'totalCertificates',
            'totalPrograms',
            'enrollmentByMonth',
            'enrollmentByStatus',
            'programEnrollments',
            'mostPopularProgram',
            'recentEnrollments',
            'allUsers',
            'userActivities',
            'systemLogs'
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
        $schedule->update($request->all());
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
            $enrollment = Enrollment::with('program')->findOrFail($enrollmentId);

            return response()->json([
                'id' => $enrollment->id,
                'first_name' => $enrollment->first_name,
                'last_name' => $enrollment->last_name,
                'middle_name' => $enrollment->middle_name,
                'suffix_name' => $enrollment->suffix_name,
                'birthdate' => $enrollment->birthdate,
                'email' => $enrollment->email,
                'phone' => $enrollment->phone,
                'program' => $enrollment->program ? $enrollment->program->name : null,
                'recruiter' => $enrollment->recruiter,
                'photo' => $enrollment->photo ? asset('storage/' . $enrollment->photo) : null,
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

    // Attendance page
    public function attendance(Request $request)
    {
        $programs = Program::all();
        $currentSession = $request->route('session', 1);

        // Get students who have made payments with their enrollments
        $studentsWithPayments = \App\Models\User::where('role', 'student')
            ->whereHas('payments', function($query) {
                $query->where('status', 'completed');
            })
            ->with(['payments' => function($query) {
                $query->where('status', 'completed')->orderBy('payment_date', 'desc');
            }, 'enrollments' => function($query) {
                $query->where('status', 'approved')->with('program');
            }, 'enrollments.attendances'])
            ->get()
            ->map(function($student) use ($currentSession) {
                // Sum all completed payments' session_count to get total paid sessions
                $paidSessions = $student->payments->sum('session_count');

                // Get the student's approved enrollment
                $enrollment = $student->enrollments->firstWhere('status', 'approved');

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
                    // Session 1: Show all students who have paid for at least 1 session and haven't been marked present yet
                    $showInSession = ($paidSessions >= 1) && !$alreadyMarkedPresent;
                } else {
                    // Session N: Show students who attended Session N-1 and haven't been marked present in Session N yet
                    $showInSession = $hasAttendedPreviousSession && !$alreadyMarkedPresent;
                }

                // Check if student has paid for this session
                $hasPaidForSession = $currentSession <= $paidSessions;

                // Can mark present if they appear in session and have paid
                $canMarkPresent = $showInSession && $hasPaidForSession;

                $paymentStatus = $hasPaidForSession ? 'paid' : 'pending';

                $student->session_eligibility = [
                    'current_session' => $currentSession,
                    'paid_sessions' => $paidSessions,
                    'has_paid' => $hasPaidForSession,
                    'eligible' => $showInSession, // Show in session if attended previous or it's Session 1
                    'can_mark_present' => $canMarkPresent, // Can only mark present if paid
                    'payment_status' => $paymentStatus,
                    'has_attended_previous' => $hasAttendedPreviousSession
                ];

                return $student;
            });

        return view('Admin.attendance', compact('programs', 'studentsWithPayments', 'currentSession'));
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
            ]);

            $sessionNumber = $request->input('session_number');
            $attendanceIds = $request->input('attendance', []);
            $savedCount = 0;
            $errors = [];

            Log::info("Processing {$sessionNumber} students for session {$sessionNumber}");

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
                    ->where('status', 'approved')
                    ->first();

                if (!$enrollment) {
                    Log::error("No approved enrollment found for user ID: {$user->id}");
                    $errors[] = "No enrollment found for: {$user->name}";
                    continue;
                }

                Log::info("Found enrollment ID: {$enrollment->id} for user: {$user->name}");

                // Check if attendance already recorded for this session and enrollment
                $existingAttendance = Attendance::where('enrollment_id', $enrollment->id)
                    ->where('session_number', $sessionNumber)
                    ->first();

                if ($existingAttendance) {
                    Log::info("Attendance already exists for enrollment {$enrollment->id} session {$sessionNumber}");
                    continue;
                }

                try {
                    $attendance = Attendance::create([
                        'enrollment_id' => $enrollment->id,
                        'session_number' => $sessionNumber,
                        'session_date' => now()->toDateString(),
                        'status' => 'present',
                    ]);

                    Log::info("Created attendance record ID: {$attendance->id}");
                    $savedCount++;
                } catch (\Exception $e) {
                    Log::error("Failed to create attendance record: " . $e->getMessage());
                    $errors[] = "Failed to save for: {$user->name}";
                }
            }

            Log::info("Attendance save completed. Saved: {$savedCount}, Errors: " . count($errors));

            // Redirect to next session
            $nextSession = $sessionNumber + 1;

            if ($savedCount > 0) {
                $message = "Attendance saved successfully! {$savedCount} student(s) marked as present for Session {$sessionNumber}.";
                $message .= " Students who completed Session {$sessionNumber} are now eligible for Session {$nextSession}.";
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
        $query = Attendance::with(['enrollment.user', 'enrollment.program']);

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
                $instructors = $program->schedules->pluck('instructor')->unique()->values();
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
                $maxSessions = (int)$program->duration;

                // Get all enrollments for this program
                $enrollments = Enrollment::where('program_id', $selectedProgram)
                    ->where('status', 'approved')
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
        $certificates = Certificate::with('user')->latest()->paginate(10);
        $eligibleStudents = Enrollment::where('status', 'approved')
            ->with(['user', 'program'])
            ->latest()
            ->paginate(10);

        return view('Admin.certificate', compact('certificates', 'eligibleStudents'));
    }

    // PDF Generation for Certificate
    public function generateCertificatePdf($id)
    {
        $enrollment = Enrollment::with(['user', 'program'])->findOrFail($id);

        // Pass enrollment to a dedicated PDF blade view
        $pdf = PDF::loadView('Admin.certificate_pdf', [
            'studentName' => $enrollment->user->name,
            'programName' => $enrollment->program ? $enrollment->program->name : null,
            'completionDate' => $enrollment->completion_date,
        ]);

        return $pdf->download('certificate_' . $enrollment->user->student_id . '.pdf');
    }

    // Store a new program
    public function storeProgram(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'duration' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'price_per_session' => 'nullable|numeric|min:0',
        ]);

        Program::create($request->only(['name', 'duration', 'description', 'price_per_session']));

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
        ]);

        $program = Program::findOrFail($id);
        $program->update($request->only(['name', 'duration', 'description', 'price_per_session']));

        return response()->json(['success' => true, 'message' => 'Program updated successfully.']);
    }

    // Delete a specific program
    public function deleteProgram($id)
    {
        $program = Program::findOrFail($id);
        $program->delete();
        return response()->json(['success' => true, 'message' => 'Program deleted successfully.']);
    }

    // Store a new schedule
    public function storeSchedule(Request $request)
    {
        $request->validate([
            'program_id' => 'required|exists:programs,id',
            'instructor' => 'required|string|max:255',
            'day' => 'required|string|max:255',
            'start_time' => 'required',
            'end_time' => 'required',
        ]);

        Schedule::create($request->all());

        return response()->json(['success' => true]);
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
}
