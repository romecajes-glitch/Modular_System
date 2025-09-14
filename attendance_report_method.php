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
