@extends('Admin.layout')

@section('title', 'Attendance Report')

@section('content')
<!-- Header -->
<div class="bg-white shadow-sm border-b mb-6">
    <div class="flex justify-between items-center py-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Attendance Report</h1>
            <p class="text-sm text-gray-600 mt-1">Complete attendance overview across all sessions</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.attendance') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Mark Attendance
            </a>
            <a href="{{ route('admin.attendance.records') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                Session Records
            </a>
        </div>
    </div>
</div>

<!-- Program Filter -->
<div class="bg-white rounded-lg shadow-sm p-6 mb-6">
    <form method="GET" action="{{ route('admin.attendance.report') }}" class="flex flex-col sm:flex-row gap-4">
        <div class="flex-1">
            <label for="program_id" class="block text-sm font-medium text-gray-700 mb-1">Select Program</label>
            <select name="program_id" id="program_id" onchange="this.form.submit()"
                    class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                <option value="">All Programs</option>
                @foreach($programs as $program)
                    <option value="{{ $program->id }}" {{ $selectedProgram == $program->id ? 'selected' : '' }}>
                        {{ $program->name }}
                    </option>
                @endforeach
            </select>
        </div>
    </form>
</div>

@if($selectedProgram)
    <!-- Program Summary -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="text-center">
                <div class="text-2xl font-bold text-blue-600">{{ $totalStudents }}</div>
                <div class="text-sm text-gray-500">Total Students</div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-bold text-green-600">{{ $totalSessions }}</div>
                <div class="text-sm text-gray-500">Total Sessions</div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-bold text-purple-600">{{ $totalAttendance }}</div>
                <div class="text-sm text-gray-500">Total Attendance</div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-bold text-orange-600">{{ $attendancePercentage }}%</div>
                <div class="text-sm text-gray-500">Attendance Rate</div>
            </div>
        </div>
    </div>

    <!-- Attendance Matrix -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Attendance Matrix - {{ $selectedProgramName }}</h3>
            <p class="text-sm text-gray-600">Session attendance overview for all enrolled students</p>
        </div>

        @if($attendanceMatrix->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider sticky left-0 bg-gray-50 z-10">
                                Student Name
                            </th>
                            @for($i = 1; $i <= $maxSessions; $i++)
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider min-w-[80px]">
                                    Session {{ $i }}
                                </th>
                            @endfor
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Total
                            </th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Rate
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($attendanceMatrix as $student)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 sticky left-0 bg-white">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-8 w-8 rounded-full overflow-hidden mr-3">
                                            @if($student['photo'])
                                                <img src="{{ asset('storage/' . $student['photo']) }}" class="h-8 w-8 object-cover rounded-full" />
                                            @else
                                                <div class="h-8 w-8 bg-blue-100 flex items-center justify-center rounded-full">
                                                    <i class="fas fa-user text-blue-600 text-xs"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $student['name'] }}</div>
                                            <div class="text-xs text-gray-500">{{ $student['email'] }}</div>
                                        </div>
                                    </div>
                                </td>
                                @for($i = 1; $i <= $maxSessions; $i++)
                                    <td class="px-4 py-4 text-center">
                                        @if(isset($student['sessions'][$i]))
                                            @if($student['sessions'][$i]['status'] === 'present')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    Present
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    Absent
                                                </span>
                                            @endif
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                -
                                            </span>
                                        @endif
                                    </td>
                                @endfor
                                <td class="px-4 py-4 text-center text-sm font-medium text-gray-900">
                                    {{ $student['total_present'] }}
                                </td>
                                <td class="px-4 py-4 text-center">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($student['attendance_rate'] >= 80) bg-green-100 text-green-800
                                        @elseif($student['attendance_rate'] >= 60) bg-yellow-100 text-yellow-800
                                        @else bg-red-100 text-red-800 @endif">
                                        {{ $student['attendance_rate'] }}%
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No attendance data found</h3>
                <p class="mt-1 text-sm text-gray-500">
                    No students are enrolled in this program or no attendance has been recorded yet.
                </p>
            </div>
        @endif
    </div>

    <!-- Session Summary -->
    @if($sessionSummary->count() > 0)
    <div class="bg-white rounded-lg shadow-sm p-6 mt-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Session Summary</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($sessionSummary as $session)
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h4 class="text-sm font-medium text-gray-900">Session {{ $session['session_number'] }}</h4>
                            <p class="text-xs text-gray-500">{{ $session['date'] ? $session['date']->format('M d, Y') : 'No date' }}</p>
                        </div>
                        <div class="text-right">
                            <div class="text-lg font-semibold text-blue-600">{{ $session['present_count'] }}</div>
                            <div class="text-xs text-gray-500">Present</div>
                        </div>
                    </div>
                    <div class="mt-2">
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-gray-500">Attendance Rate:</span>
                            <span class="font-medium {{ $session['attendance_rate'] >= 80 ? 'text-green-600' : ($session['attendance_rate'] >= 60 ? 'text-yellow-600' : 'text-red-600') }}">
                                {{ $session['attendance_rate'] }}%
                            </span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif
@else
    <!-- No Program Selected -->
    <div class="bg-white rounded-lg shadow-sm p-12 text-center">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
        </svg>
        <h3 class="mt-2 text-sm font-medium text-gray-900">Select a Program</h3>
        <p class="mt-1 text-sm text-gray-500">
            Choose a program from the dropdown above to view the complete attendance report.
        </p>
    </div>
@endif

<script>
// Auto-submit form when program is selected
document.getElementById('program_id').addEventListener('change', function() {
    this.form.submit();
});
</script>
@endsection
