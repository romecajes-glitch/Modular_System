<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\Schedule;
use App\Models\Enrollment;
use App\Models\Attendance;
use App\Models\Payment;
use App\Models\Program;
use App\Models\User;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\InstructorAttendanceExport;
use Illuminate\Support\Facades\Schema;

class InstructorController extends Controller
{
    /**
     * Get program IDs assigned to the instructor.
     * Primary source: schedules table. Fallback: programs.instructor_id if column exists.
     */
    protected function getInstructorProgramIds($instructorId)
    {
        // Prefer schedules as the source of truth
        $programIdsFromSchedules = Schedule::where('instructor_id', $instructorId)
            ->pluck('program_id')
            ->unique();

        $programIds = collect($programIdsFromSchedules);

        // Fallback: if programs table has instructor_id, use it
        if (Schema::hasColumn('programs', 'instructor_id')) {
            $programIdsFromPrograms = Program::where('instructor_id', $instructorId)
                ->pluck('id')
                ->unique();
            $programIds = $programIds->merge($programIdsFromPrograms);
        }

        // Additional fallback: user's own program_id if set
        $userProgramId = User::find($instructorId)->program_id ?? null;
        if (!empty($userProgramId)) {
            $programIds->push($userProgramId);
        }

        return $programIds->unique()->values();
    }

    /**
     * Show the instructor dashboard.
     */
    public function dashboard()
    {
        $instructorId = Auth::id();

        // Get programs assigned to the instructor using the helper method
        $programIds = $this->getInstructorProgramIds($instructorId);

        $programs = Program::whereIn('id', $programIds)->get();
        // Fallback: if still empty, derive via schedules relation to catch orphaned links
        if ($programs->isEmpty()) {
            $normalizedName = mb_strtolower(trim(Auth::user()->name));
            $assignedSchedules = Schedule::when(
                    Schema::hasColumn('schedules', 'instructor_id'),
                    function ($query) use ($instructorId) {
                        return $query->where('instructor_id', $instructorId);
                    },
                    function ($query) use ($normalizedName) {
                        return $query->whereRaw('LOWER(TRIM(instructor)) = ?', [$normalizedName]);
                    }
                )
                ->with('program')
                ->get();

            $programs = $assignedSchedules->pluck('program')->filter()->unique('id')->values();
        }

        // Get enrollments for these programs with enrolled status only
        $enrollments = Enrollment::whereIn('program_id', $programIds)
            ->where('status', Enrollment::STATUS_ENROLLED)
            ->with(['user', 'program'])
            ->get();

        // Count enrolled students
        $enrolledStudentsCount = $enrollments->count();

        // Today's attendance: count present for today's sessions
        $today = Carbon::today();
        $todaysAttendances = Attendance::whereHas('enrollment', function ($query) use ($programIds) {
            $query->whereIn('program_id', $programIds);
        })
        ->whereDate('session_date', $today)
        ->get();

        $presentToday = $todaysAttendances->where('status', 'present')->count();

        // Get last session date
        $lastSession = Attendance::whereHas('enrollment', function ($query) use ($programIds) {
            $query->whereIn('program_id', $programIds);
        })
        ->orderByDesc('session_date')
        ->first();

        $lastSessionDate = $lastSession ? $lastSession->session_date->format('F d, Y') : 'N/A';

        // Get upcoming session (next schedule)
        $now = Carbon::now();
        $upcomingSchedule = Schedule::whereIn('program_id', $programIds)
            ->where(function ($query) use ($now) {
                $query->where('day', '>=', $now->format('l'))
                      ->orWhere(function ($q) use ($now) {
                          $q->where('day', $now->format('l'))
                            ->where('start_time', '>', $now->format('H:i:s'));
                      });
            })
            ->orderBy('day')
            ->orderBy('start_time')
            ->with('program')
            ->first();

        $upcomingSession = null;
        if ($upcomingSchedule) {
            $upcomingSession = [
                'date' => $now->next($upcomingSchedule->day)->format('F d, Y'),
                'time' => date('g:i A', strtotime($upcomingSchedule->start_time)),
                'program' => $upcomingSchedule->program->name ?? 'N/A',
            ];
        }

        // Recent enrolled students: latest 5 enrollments with enrolled status only
        $recentEnrolledStudents = Enrollment::whereIn('program_id', $programIds)
            ->where('status', Enrollment::STATUS_ENROLLED)
            ->with('user', 'program')
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        // Get total programs count for instructor
        $totalPrograms = $programs->count();

        // Get program details for display
        $assignedPrograms = $programs->take(3); // Show up to 3 programs

        // Get total sessions completed today across all programs
        $totalSessionsToday = Schedule::whereIn('program_id', $programIds)
            ->where('day', Carbon::today()->format('l'))
            ->count();

        // Get attendance statistics
        $totalAttendanceRecords = Attendance::whereHas('enrollment', function ($query) use ($programIds) {
            $query->whereIn('program_id', $programIds);
        })->count();

        // Get recent notifications (mock data for now - can be enhanced with real notifications table)
        $recentNotifications = [
            [
                'type' => 'session',
                'title' => 'New session scheduled',
                'message' => $upcomingSession ? $upcomingSession['program'] . ' - ' . $upcomingSession['date'] . ' ' . $upcomingSession['time'] : 'No upcoming sessions',
                'icon' => 'calendar-check',
                'color' => 'blue'
            ],
            [
                'type' => 'enrollment',
                'title' => 'New enrollments',
                'message' => $recentEnrolledStudents->count() . ' students have enrolled recently',
                'icon' => 'user-plus',
                'color' => 'green'
            ],
            [
                'type' => 'certificate',
                'title' => 'Certificate available',
                'message' => 'Check certificates for completed students',
                'icon' => 'certificate',
                'color' => 'purple'
            ]
        ];

        // Get recent messages (mock data for now)
        $recentMessages = [
            [
                'from' => 'System',
                'title' => 'Welcome to Instructor Portal',
                'message' => 'You have ' . $enrolledStudentsCount . ' enrolled students',
                'icon' => 'user-graduate',
                'color' => 'blue'
            ],
            [
                'from' => 'Admin',
                'title' => 'Schedule Update',
                'message' => 'Your schedule has been updated for this week',
                'icon' => 'exclamation-triangle',
                'color' => 'yellow'
            ]
        ];

        $userName = Auth::user()->name;

        return view('Instructor.dashboard', compact(
            'enrolledStudentsCount',
            'presentToday',
            'lastSessionDate',
            'upcomingSession',
            'recentEnrolledStudents',
            'userName',
            'totalPrograms',
            'assignedPrograms',
            'totalSessionsToday',
            'totalAttendanceRecords',
            'recentNotifications',
            'recentMessages'
        ));
    }

    /**
     * Show all students for the instructor.
     */
    public function students()
    {
        $instructorId = Auth::id();

        // Get programs assigned to the instructor using the same comprehensive logic as dashboard
        $programIds = $this->getInstructorProgramIds($instructorId);

        // If no assigned programs found, return empty paginator for view compatibility
        if ($programIds->isEmpty()) {
            $enrolledStudents = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10);
            return view('Instructor.students', compact('enrolledStudents'));
        }

        // Get enrollments for ALL assigned programs with enrolled status only (same as dashboard logic)
        $enrollments = Enrollment::whereIn('program_id', $programIds)
            ->where('status', Enrollment::STATUS_ENROLLED)
            ->with(['user.payments', 'program'])
            ->orderByDesc('created_at')
            ->get();

        // Convert to paginated collection for view compatibility
        $enrolledStudents = new \Illuminate\Pagination\LengthAwarePaginator(
            $enrollments,
            $enrollments->count(),
            10,
            1,
            ['path' => request()->url()]
        );

        return view('Instructor.students', compact('enrolledStudents'));
    }

    /**
     * View a specific student details.
     */
    public function viewStudent(Request $request, $id)
    {
        $instructorId = Auth::id();

        // Get programs assigned to the instructor using the helper method
        $programIds = $this->getInstructorProgramIds($instructorId);

        // Get enrollment for this student in instructor's programs
        $enrollment = Enrollment::where('id', $id)
            ->whereIn('program_id', $programIds)
            ->where('status', Enrollment::STATUS_ENROLLED)
            ->with(['user.payments', 'program', 'attendances' => function($query) {
                $query->with(['enrollment.user', 'markedByUser']); // Include the user who created the attendance
            }])
            ->firstOrFail();

        if ($request->ajax()) {
            return response()->json([
                'id' => $enrollment->id,
                'full_name' => $enrollment->full_name,
                'email' => $enrollment->email,
                'phone' => $enrollment->phone,
                'birthdate' => $enrollment->birthdate,
                'address' => $enrollment->address,
                'status' => $enrollment->status,
                'paid_sessions' => $enrollment->paid_sessions,
                'total_sessions' => $enrollment->program->duration ?? 0,
                'created_at' => $enrollment->created_at,
                'user' => $enrollment->user,
                'program' => $enrollment->program,
                'attendances' => $enrollment->attendances->map(function($attendance) {
                    // Get payment information for this attendance if it exists
                    $referenceNumber = null;
                    $paymentMethod = null;

                    // Get the first payment for this student that has a transaction_id
                    $payment = \App\Models\Payment::where('student_id', $attendance->enrollment->student_id)
                        ->where('status', 'completed')
                        ->whereNotNull('transaction_id')
                        ->orderBy('payment_date', 'desc')
                        ->first();

                    if ($payment) {
                        // Check if the or_number matches this payment's transaction_id (online payment)
                        if ($attendance->or_number && $attendance->or_number === $payment->transaction_id) {
                            $referenceNumber = $payment->transaction_id;
                            $paymentMethod = $payment->payment_method;
                        } else {
                            // For manual OR numbers or if no exact match, still show the transaction_id
                            // This will show the reference number for any completed payment with transaction_id
                            $referenceNumber = $payment->transaction_id;
                            $paymentMethod = $payment->payment_method;
                        }
                    }

                    return [
                        'id' => $attendance->id,
                        'session_number' => $attendance->session_number,
                        'session_date' => $attendance->session_date,
                        'created_at' => $attendance->created_at,
                        'status' => $attendance->status,
                        'or_number' => $attendance->or_number,
                        'reference_number' => $referenceNumber,
                        'payment_method' => $paymentMethod,
                        'marked_by_user' => $attendance->markedByUser ? [
                            'id' => $attendance->markedByUser->id,
                            'name' => $attendance->markedByUser->name,
                        ] : null,
                    ];
                }),
            ]);
        }

        return view('Instructor.student_details', compact('enrollment'));
    }

    /**
     * Show attendance management page.
     */
    public function attendance(Request $request)
    {
        $instructorId = Auth::id();

        // Get programs assigned to the instructor using the helper method
        $programIds = $this->getInstructorProgramIds($instructorId);
        $programs = Program::whereIn('id', $programIds)->get();

        // Get current session from request or default to 1
        $currentSession = $request->get('session', 1);

        // Get today's sessions for the instructor based on the day of the week
        $today = Carbon::today();
        $dayOfWeek = $today->format('l'); // Get full day name (e.g., "Monday")

        $todaysSessions = Schedule::whereIn('program_id', $programIds)
            ->when(
                Schema::hasColumn('schedules', 'instructor_id'),
                function ($query) use ($instructorId) {
                    return $query->where('instructor_id', $instructorId);
                },
                function ($query) {
                    return $query->where('instructor', Auth::user()->name);
                }
            )
            ->where('day', $dayOfWeek)
            ->with(['program'])
            ->get();

        // Get all students with enrolled enrollments in instructor's programs
        $enrolledStudents = \App\Models\User::where('role', 'student')
            ->whereHas('enrollments', function($query) use ($programIds) {
                $query->whereIn('program_id', $programIds)
                      ->where('status', Enrollment::STATUS_ENROLLED);
            })
            ->with(['payments' => function($query) {
                $query->where('status', 'completed')->orderBy('payment_date', 'desc');
            }, 'enrollments' => function($query) use ($programIds) {
                $query->whereIn('program_id', $programIds)
                      ->where('status', Enrollment::STATUS_ENROLLED)
                      ->with('program');
            }, 'enrollments.attendances'])
            ->get()
            ->map(function($student) use ($currentSession) {
                // Get the student's enrolled enrollment
                $enrollment = $student->enrollments->firstWhere('status', 'enrolled');

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
                    // Session 1: Show all students with enrolled enrollments who haven't been marked present yet
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

                // Calculate which session this student actually belongs to
                $studentSession = 1; // Default to session 1

                if ($enrollment) {
                    // Get the highest session number the student has attended
                    $lastAttendedSession = \App\Models\Attendance::where('enrollment_id', $enrollment->id)
                        ->where('status', 'present')
                        ->max('session_number') ?? 0;

                    // The student belongs to the next session after their last attended session
                    $studentSession = $lastAttendedSession + 1;

                    // If student has paid sessions, they can't go beyond their paid limit
                    if ($paidSessions > 0 && $studentSession > $paidSessions) {
                        $studentSession = $paidSessions;
                    }

                    // Ensure session doesn't exceed program duration
                    $programDuration = $enrollment->program->duration ?? 1;
                    if (is_numeric($programDuration)) {
                        $maxSession = (int)$programDuration;
                    } else {
                        // Extract number from string like "10 weeks"
                        $matches = [];
                        preg_match('/(\d+)/', $programDuration, $matches);
                        $maxSession = $matches[1] ?? 1;
                    }

                    if ($studentSession > $maxSession) {
                        $studentSession = $maxSession;
                    }

                    // Ensure minimum session is 1
                    $studentSession = max(1, $studentSession);
                }

                $student->session_eligibility = [
                    'current_session' => $currentSession,
                    'student_session' => $studentSession, // The session this student actually belongs to
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

        return view('Instructor.attendance', compact('programs', 'enrolledStudents', 'currentSession', 'todaysSessions', 'session1StudentCount'));
    }

    /**
     * Save attendance for a session.
     */
    public function saveAttendance(Request $request)
    {

        try {
            $request->validate([
                'session_number' => 'required|integer|min:1',
                'attendance' => 'sometimes|array',
                'attendance.*' => 'integer|exists:users,id',
                'selected_time_slot' => 'required|string',
                'schedule_id' => 'required|string', // Changed from integer to string to handle potential string values
            ]);

            $sessionNumber = $request->input('session_number');
            $attendanceIds = $request->input('attendance', []);
            $selectedTimeSlot = $request->input('selected_time_slot');
            $scheduleId = $request->input('schedule_id');
            $savedCount = 0;
            $errors = [];

            Log::info("Instructor attendance save request received:");
            Log::info("Session number: {$sessionNumber}");
            Log::info("Selected time slot: {$selectedTimeSlot}");
            Log::info("Schedule ID: {$scheduleId}");
            Log::info("Attendance IDs: " . json_encode($attendanceIds));

            // Parse the time slot (format: "start_time-end_time")
            $timeParts = explode('-', $selectedTimeSlot);
            $startTime = $timeParts[0] ?? null;
            $endTime = $timeParts[1] ?? null;

            // Get schedule details for additional validation
            $schedule = Schedule::find((int)$scheduleId);
            if (!$schedule) {
                Log::error("Schedule not found for ID: {$scheduleId}");
                return redirect()->back()->with('error', 'Invalid schedule selected.');
            }

            // Get instructor's programs using the same method as other methods
            $instructorId = Auth::id();
            $programIds = $this->getInstructorProgramIds($instructorId);

            Log::info("Processing {$sessionNumber} students for session {$sessionNumber} by instructor {$instructorId}");
            Log::info("Time slot: {$selectedTimeSlot} (Start: {$startTime}, End: {$endTime})");
            Log::info("Instructor program IDs: " . json_encode($programIds->toArray()));

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

                // Query enrollment by student_id and ensure it's in instructor's programs
                $enrollment = Enrollment::where('student_id', $user->id)
                    ->whereIn('program_id', $programIds)
                    ->where('status', Enrollment::STATUS_ENROLLED)
                    ->first();

                if (!$enrollment) {
                    Log::error("No approved enrollment found for user ID: {$user->id} in instructor's programs");
                    Log::error("User ID: {$user->id}, Program IDs: " . json_encode($programIds->toArray()));
                    
                    // Check if user has any enrollments at all
                    $allEnrollments = Enrollment::where('student_id', $user->id)->get();
                    Log::error("All enrollments for user {$user->id}: " . json_encode($allEnrollments->toArray()));
                    
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
                    // Get OR number from enrollment or from online payment transaction
                    $orNumber = $enrollment->or_number;

                    // If no OR number in enrollment, check for online payment transaction ID
                    if (!$orNumber) {
                        $payment = \App\Models\Payment::where('student_id', $user->id)
                            ->where('status', 'completed')
                            ->where('session_count', '>=', $sessionNumber)
                            ->orderBy('payment_date', 'desc')
                            ->first();

                        if ($payment && $payment->transaction_id) {
                            $orNumber = $payment->transaction_id;
                        }
                    }
                    
                    // If still no OR number, use a default value for testing
                    if (!$orNumber) {
                        $orNumber = 'TEST-' . $sessionNumber . '-' . $user->id;
                        Log::info("Using test OR number for user {$user->id}: {$orNumber}");
                    }

                    $attendance = Attendance::create([
                        'enrollment_id' => $enrollment->id,
                        'session_number' => $sessionNumber,
                        'session_date' => now()->toDateString(),
                        'start_time' => $startTime,
                        'end_time' => $endTime,
                        'status' => 'present',
                        'or_number' => $orNumber,
                        'marked_by_user_id' => $instructorId, // Track who marked the attendance
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

            Log::info("Instructor attendance save completed. Saved: {$savedCount}, Errors: " . count($errors));

            // Always redirect to next session (same as admin)
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

            return redirect()->route('instructor.attendance', ['session' => $nextSession])->with('success', $message);

        } catch (\Exception $e) {
            Log::error('Exception in instructor saveAttendance: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return redirect()->back()->with('error', 'Error saving attendance: ' . $e->getMessage());
        }
    }

    /**
     * Show certificates page for instructor.
     */
    public function certificates()
    {
        $instructorId = Auth::id();

        // Get programs assigned to the instructor using the helper method
        $programIds = $this->getInstructorProgramIds($instructorId);
        $assignedPrograms = Program::whereIn('id', $programIds)->get();

        // Get eligible students who have completed all sessions in their assigned programs
        $eligibleStudents = Enrollment::whereIn('enrollments.program_id', $programIds)
            ->where('enrollments.status', Enrollment::STATUS_ENROLLED)
            ->join('programs', 'enrollments.program_id', '=', 'programs.id')
            ->leftJoin('attendances', function($join) {
                $join->on('enrollments.id', '=', 'attendances.enrollment_id')
                     ->where('attendances.status', '=', 'present');
            })
            ->select('enrollments.id', 'enrollments.student_id', 'enrollments.program_id', 'enrollments.status', 'enrollments.created_at', 'enrollments.updated_at', 'enrollments.completion_date', 'programs.duration', DB::raw('COUNT(attendances.id) as attendance_count'))
            ->groupBy('enrollments.id', 'enrollments.student_id', 'enrollments.program_id', 'enrollments.status', 'enrollments.created_at', 'enrollments.updated_at', 'enrollments.completion_date', 'programs.duration')
            ->havingRaw('COUNT(attendances.id) = CASE
                WHEN programs.duration REGEXP "^[0-9]+$" THEN CAST(programs.duration AS UNSIGNED)
                ELSE CAST(REGEXP_SUBSTR(programs.duration, "[0-9]+") AS UNSIGNED)
            END')
            ->with(['user', 'program'])
            ->latest()
            ->paginate(10);

        return view('Instructor.certificate', compact('eligibleStudents', 'assignedPrograms'));
    }

    /**
     * Show program details.
     */
    public function programDetails($id)
    {
        $instructorId = Auth::id();

        // Get program if assigned to instructor
        $program = Program::where('id', $id)
            ->whereHas('schedules', function ($query) use ($instructorId) {
                $query->where('instructor_id', $instructorId);
            })
            ->with(['schedules', 'enrollments.user'])
            ->first();

        // If program not found or not assigned to instructor, redirect with error
        if (!$program) {
            return redirect()->route('instructor.dashboard')
                ->with('error', 'Program not found or you do not have access to this program.');
        }

        return view('Instructor.program_details', compact('program'));
    }

    /**
     * Update OR number for enrollment (scoped to instructor's programs).
     */
    public function updateOrNumber(Request $request, $enrollmentId)
    {
        $request->validate([
            'or_number' => 'nullable|string|max:255',
        ]);

        try {
            $instructorId = Auth::id();

            // Get programs assigned to the instructor using the helper method
            $programIds = $this->getInstructorProgramIds($instructorId);

            // Find enrollment that belongs to instructor's programs
            $enrollment = Enrollment::where('id', $enrollmentId)
                ->whereIn('program_id', $programIds)
                ->where('status', Enrollment::STATUS_ENROLLED)
                ->firstOrFail();

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
            if ($isNewOrNumber) {
                $enrollment->increment('paid_sessions');

                // If paid_sessions > 0, change status to 'enrolled' (should already be enrolled)
                if ($enrollment->paid_sessions > 0) {
                    $enrollment->update(['status' => 'enrolled']);
                }
            }

            // If OR number is being cleared, decrement paid_sessions by 1 but not below 0
            if ($isClearingOrNumber) {
                // Decrement paid_sessions by 1 but not below 0 to properly reflect payment status
                // Keep status as 'enrolled' so student still appears in attendance.blade.php and student.blade.php
                if ($enrollment->paid_sessions > 0) {
                    $enrollment->decrement('paid_sessions');
                }

                // Log the OR number clearing for audit purposes
                Log::info("OR number cleared for enrollment {$enrollmentId}, paid_sessions decremented by 1 (not below 0), attendance adjusted but student remains enrolled");
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

    /**
     * Download attendance template Excel file.
     */
    public function downloadAttendanceTemplate(Request $request)
    {
        $instructorId = Auth::id();

        // Get programs assigned to the instructor using the helper method
        $programIds = $this->getInstructorProgramIds($instructorId);

        // Get current session from request or default to 1
        $currentSession = $request->get('session', 1);

        // Get all students with enrolled enrollments in instructor's programs (matching dashboard logic)
        $enrolledStudents = \App\Models\User::where('role', 'student')
            ->whereHas('enrollments', function($query) use ($programIds) {
                $query->whereIn('program_id', $programIds)
                      ->where('status', Enrollment::STATUS_ENROLLED);
            })
            ->with(['payments' => function($query) {
                $query->where('status', 'completed')->orderBy('payment_date', 'desc');
            }, 'enrollments' => function($query) use ($programIds) {
                $query->whereIn('program_id', $programIds)
                      ->where('status', Enrollment::STATUS_ENROLLED)
                      ->with('program');
            }, 'enrollments.attendances'])
            ->get();

        // Filter students based on session eligibility
        $eligibleStudents = $enrolledStudents->filter(function($student) use ($currentSession) {
            // Get the student's enrolled enrollment
            $enrollment = $student->enrollments->firstWhere('status', 'enrolled');

            // Get OR number from enrollment
            $orNumber = $enrollment ? $enrollment->or_number : null;

            // Get total paid sessions from enrollment
            $paidSessions = $enrollment ? ($enrollment->paid_sessions ?? 0) : 0;

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

            return $showInSession;
        });

        // Generate Excel file
        $sessionDate = now()->toDateString();
        $fileName = "attendance_template_session_{$currentSession}_{$sessionDate}.xlsx";

        return Excel::download(new InstructorAttendanceExport($eligibleStudents, $currentSession, $sessionDate), $fileName);
    }

    /**
     * Upload and process attendance template Excel file.
     */
    public function uploadAttendanceTemplate(Request $request)
    {
        $request->validate([
            'attendance_file' => 'required|file|mimes:xlsx,xls|max:10240', // 10MB max
            'session_number' => 'required|integer|min:1',
        ]);

        try {
            $instructorId = Auth::id();
            $sessionNumber = $request->input('session_number');

            // Get programs assigned to the instructor using the helper method
            $programIds = $this->getInstructorProgramIds($instructorId);

            // Import the Excel file
            $rows = Excel::toArray([], $request->file('attendance_file'))[0];

            // Remove header row
            array_shift($rows);

            $savedCount = 0;
            $errors = [];
            $logs = [];
            $processedOrNumbers = []; // Track OR numbers for uniqueness validation
            $hasErrors = false; // Flag to track if any errors occurred

            foreach ($rows as $index => $row) {
                $rowNumber = $index + 2; // +2 because we removed header and array is 0-indexed

                try {
                    // Validate row data
                    $studentId = $row[0] ?? null;
                    $studentName = $row[1] ?? null;
                    $rowSessionNumber = $row[2] ?? null;
                    $rowSessionDate = $row[3] ?? null;
                    $paymentStatus = $row[4] ?? null;
                    $orNumber = $row[5] ?? null;
                    $status = $row[6] ?? null;

                    // Skip empty rows
                    if (empty($studentId) && empty($studentName)) {
                        continue;
                    }

                    // Validate required fields
                    if (empty($studentId)) {
                        $errors[] = "Row {$rowNumber}: Student ID is required";
                        $logs[] = "Row {$rowNumber}: Skipped - Student ID missing";
                        $hasErrors = true;
                        continue;
                    }

                    // Validate status - allow blank (defaults to absent) or valid values
                    if (!empty($status) && !in_array(strtolower($status), ['present', 'absent'])) {
                        $errors[] = "Row {$rowNumber}: Status must be 'Present', 'Absent', or left blank";
                        $logs[] = "Row {$rowNumber}: Skipped - Invalid status '{$status}'";
                        $hasErrors = true;
                        continue;
                    }

                    // Default blank status to 'absent'
                    if (empty($status)) {
                        $status = 'absent';
                    }

                    // Validate session number matches
                    if ((int)$rowSessionNumber !== (int)$sessionNumber) {
                        $errors[] = "Row {$rowNumber}: Session number mismatch (expected {$sessionNumber}, got {$rowSessionNumber})";
                        $logs[] = "Row {$rowNumber}: Skipped - Session number mismatch";
                        $hasErrors = true;
                        continue;
                    }

                    // Find user by ID
                    $user = User::find($studentId);
                    if (!$user) {
                        $errors[] = "Row {$rowNumber}: Student not found (ID: {$studentId})";
                        $logs[] = "Row {$rowNumber}: Skipped - Student ID {$studentId} not found";
                        $hasErrors = true;
                        continue;
                    }

                    // Find enrollment in instructor's programs
                    $enrollment = Enrollment::where('student_id', $user->id)
                        ->whereIn('program_id', $programIds)
                        ->where('status', Enrollment::STATUS_ENROLLED)
                        ->first();

                    if (!$enrollment) {
                        $errors[] = "Row {$rowNumber}: No valid enrollment found for student {$user->name}";
                        $logs[] = "Row {$rowNumber}: Skipped - No valid enrollment for student {$user->name}";
                        $hasErrors = true;
                        continue;
                    }

                    // Validate that session number belongs to student's program
                    $program = $enrollment->program;
                    if ($program && $program->duration) {
                        $maxSessions = is_numeric($program->duration) ? (int)$program->duration : (int)preg_replace('/\D/', '', $program->duration);
                        if ($sessionNumber > $maxSessions) {
                            $errors[] = "Row {$rowNumber}: Session {$sessionNumber} exceeds program duration ({$maxSessions} sessions)";
                            $logs[] = "Row {$rowNumber}: Skipped - Invalid session number for program";
                            $hasErrors = true;
                            continue;
                        }
                    }

                    // Check if attendance already exists
                    $existingAttendance = Attendance::where('enrollment_id', $enrollment->id)
                        ->where('session_number', $sessionNumber)
                        ->first();

                    if ($existingAttendance) {
                        $logs[] = "Row {$rowNumber}: Skipped - Attendance already exists for student {$user->name}";
                        continue;
                    }

                    // Validate OR number based on payment status and status
                    if (strtolower($status) === 'present') {
                        if ($paymentStatus === 'Pending Onsite' && empty($orNumber)) {
                            $errors[] = "Row {$rowNumber}: OR number is required for onsite payment when status is Present";
                            $logs[] = "Row {$rowNumber}: Skipped - OR number required for onsite payment";
                            $hasErrors = true;
                            continue;
                        }

                        // Validate OR number uniqueness if provided
                        if (!empty($orNumber)) {
                            if (in_array($orNumber, $processedOrNumbers)) {
                                $errors[] = "Row {$rowNumber}: OR number '{$orNumber}' is not unique";
                                $logs[] = "Row {$rowNumber}: Skipped - Duplicate OR number";
                                $hasErrors = true;
                                continue;
                            }

                            // Check if OR number already exists in database for this session
                            $existingOrNumber = Attendance::where('or_number', $orNumber)
                                ->where('session_number', $sessionNumber)
                                ->exists();

                            if ($existingOrNumber) {
                                $errors[] = "Row {$rowNumber}: OR number '{$orNumber}' already used in this session";
                                $logs[] = "Row {$rowNumber}: Skipped - OR number already exists";
                                $hasErrors = true;
                                continue;
                            }

                            $processedOrNumbers[] = $orNumber;
                        }
                    }

                    // For online payments, ignore OR number field
                    if ($paymentStatus === 'Paid Online' && !empty($orNumber)) {
                        $logs[] = "Row {$rowNumber}: OR number ignored for online payment";
                        $orNumber = null; // Clear OR number for online payments
                    }

                    // Create attendance record
                    $attendance = Attendance::create([
                        'enrollment_id' => $enrollment->id,
                        'session_number' => $sessionNumber,
                        'session_date' => $rowSessionDate ?: now()->toDateString(),
                        'status' => strtolower($status),
                        'or_number' => $orNumber,
                        'marked_by_user_id' => $instructorId,
                    ]);

                    // Handle OR number and paid sessions for present status
                    if (strtolower($status) === 'present') {
                        if ($enrollment->or_number) {
                            $enrollment->update([
                                'or_number' => null,
                                'paid_sessions' => ($enrollment->paid_sessions ?? 0) + 1
                            ]);
                        } elseif ($orNumber) {
                            $enrollment->update([
                                'paid_sessions' => ($enrollment->paid_sessions ?? 0) + 1
                            ]);
                        }
                    }

                    $savedCount++;
                    $logs[] = "Row {$rowNumber}: Successfully saved attendance for {$user->name}";

                } catch (\Exception $e) {
                    $errors[] = "Row {$rowNumber}: " . $e->getMessage();
                    $logs[] = "Row {$rowNumber}: Error - " . $e->getMessage();
                    $hasErrors = true;
                }
            }

            // Create comprehensive audit log entry
            Log::info('Attendance template upload audit', [
                'instructor_id' => $instructorId,
                'instructor_name' => Auth::user()->name,
                'session_number' => $sessionNumber,
                'total_rows_processed' => count($rows),
                'successful_records' => $savedCount,
                'error_count' => count($errors),
                'has_errors' => $hasErrors,
                'file_name' => $request->file('attendance_file')->getClientOriginalName(),
                'file_size' => $request->file('attendance_file')->getSize(),
                'uploaded_at' => now(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            // Prepare response data
            $responseData = [
                'success' => $savedCount > 0 && !$hasErrors,
                'saved_count' => $savedCount,
                'errors' => $errors,
                'logs' => $logs,
                'total_processed' => count($rows),
                'has_errors' => $hasErrors,
            ];

            if ($savedCount > 0 && !$hasErrors) {
                $responseData['message'] = "Successfully processed all {$savedCount} attendance records.";
            } elseif ($savedCount > 0 && $hasErrors) {
                $responseData['message'] = "Partially processed: {$savedCount} records saved, " . count($errors) . " errors found. Please correct the errors and re-upload.";
            } elseif ($savedCount === 0 && $hasErrors) {
                $responseData['message'] = "Processing failed: " . count($errors) . " errors found. Please correct the file and re-upload.";
            } else {
                $responseData['message'] = "No attendance records were processed.";
            }

            return response()->json($responseData);

        } catch (\Exception $e) {
            Log::error('Exception in uploadAttendanceTemplate: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error processing file: ' . $e->getMessage(),
                'errors' => ['File processing failed'],
                'logs' => []
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

    // Get schedules for a specific program
    public function getSchedulesForProgram($programId)
    {
        try {
            $instructorId = Auth::id();
            
            // Verify the instructor has access to this program
            $programIds = $this->getInstructorProgramIds($instructorId);
            
            if (!$programIds->contains($programId)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Access denied to this program'
                ], 403);
            }
            
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
}
