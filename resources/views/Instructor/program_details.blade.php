<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Program Details - Instructor</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100 font-sans">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <div class="sidebar bg-blue-900 text-white w-56 flex flex-col">
            @include('Instructor.partials.navigation')
        </div>

        <!-- Main Content -->
        <div class="content-area flex-1 overflow-y-auto">
            <!-- Top Bar -->
            <div class="bg-white shadow-md p-4 flex justify-between items-center border-b border-gray-100">
                <div class="flex items-center space-x-4">
                    <button id="mobileMenuButton" class="text-gray-500 hover:text-gray-700 md:hidden transition-colors">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    <h2 class="text-xl font-bold text-gray-800">Program Details</h2>
                </div>
                <div class="flex items-center space-x-6">
                    <div class="border-l h-8 border-gray-200"></div>
                    <div class="relative group">
                        <button id="adminDropdown" class="flex items-center focus:outline-none space-x-2">
                            <div class="h-9 w-9 rounded-full bg-gradient-to-r from-blue-500 to-blue-600 flex items-center justify-center shadow">
                                <i class="fas fa-user text-white"></i>
                            </div>
                            <div class="hidden md:block text-left">
                                <p class="text-sm font-medium text-gray-700">Instructor</p>
                            </div>
                            <i class="fas fa-chevron-down text-xs text-gray-500 hidden md:block transition-transform group-hover:rotate-180"></i>
                        </button>
                        <div id="dropdownMenu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                            <a href="{{ route('instructor.dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Back to Dashboard</a>
                            <a href="#" id="logoutButton" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 border-t border-gray-100">Log Out</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Program Details Content -->
            <div class="p-6">
                <div class="bg-white rounded-lg shadow">
                    <div class="p-6">
                        <!-- Program Header -->
                        <div class="mb-6">
                            <h3 class="text-2xl font-bold text-gray-900">{{ $program ? $program->name : 'Program Not Found' }}</h3>
                            <p class="text-gray-600 mt-1">{{ $program ? ($program->description ?? 'No description available.') : 'This program could not be found or you do not have access to it.' }}</p>
                        </div>

                        @if($program)
                        <!-- Program Statistics -->
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                            <div class="bg-blue-50 p-4 rounded-lg">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-calendar-alt text-blue-600 text-2xl"></i>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm font-medium text-gray-500">Total Sessions</p>
                                        <p class="text-2xl font-bold text-gray-900">{{ $program->schedules->count() }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-green-50 p-4 rounded-lg">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-users text-green-600 text-2xl"></i>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm font-medium text-gray-500">Total Students</p>
                                        <p class="text-2xl font-bold text-gray-900">{{ $program->enrollments->count() }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-yellow-50 p-4 rounded-lg">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-clock text-yellow-600 text-2xl"></i>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm font-medium text-gray-500">Duration</p>
                                        <p class="text-2xl font-bold text-gray-900">{{ $program->duration ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-purple-50 p-4 rounded-lg">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-dollar-sign text-purple-600 text-2xl"></i>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm font-medium text-gray-500">Price</p>
                                        <p class="text-2xl font-bold text-gray-900">${{ number_format($program->price ?? 0, 2) }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        @if($program)
                        <!-- Sessions Schedule -->
                        <div class="mb-6">
                            <h4 class="text-lg font-semibold text-gray-800 mb-4">Session Schedule</h4>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Session</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @forelse ($program->schedules->sortBy('day') as $schedule)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $schedule->session_name ?? 'Session ' . $schedule->id }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $schedule->day ?? 'N/A' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $schedule->start_time ?? 'N/A' }} - {{ $schedule->end_time ?? 'N/A' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @php
                                                    $today = \Carbon\Carbon::today();
                                                    $dayOfWeek = $today->format('l');
                                                @endphp
                                                @if($schedule->day === $dayOfWeek)
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Today</span>
                                                @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Scheduled</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="4" class="px-6 py-4 text-center text-gray-500">No sessions scheduled.</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Enrolled Students -->
                        <div>
                            <h4 class="text-lg font-semibold text-gray-800 mb-4">Enrolled Students</h4>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Enrollment Date</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @forelse ($program->enrollments as $enrollment)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-full flex items-center justify-center">
                                                        @if($enrollment->user && $enrollment->user->photo)
                                                        <img src="{{ asset('storage/' . $enrollment->user->photo) }}" alt="Photo" class="w-10 h-10 rounded-full object-cover">
                                                        @else
                                                        <i class="fas fa-user text-blue-600"></i>
                                                        @endif
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900">{{ $enrollment->full_name }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $enrollment->email }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                    {{ $enrollment->status === 'approved' ? 'bg-green-100 text-green-800' :
                                                       ($enrollment->status === 'enrolled' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800') }}">
                                                    {{ ucfirst($enrollment->status) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $enrollment->created_at->format('F d, Y') }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="4" class="px-6 py-4 text-center text-gray-500">No students enrolled in this program.</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @else
                        <div class="text-center py-8">
                            <i class="fas fa-exclamation-triangle text-yellow-500 text-4xl mb-4"></i>
                            <h4 class="text-lg font-medium text-gray-900 mb-2">Program Not Available</h4>
                            <p class="text-gray-600">The requested program could not be found or you do not have access to view it.</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
        @csrf
    </form>

    <script>
        // Dropdown toggle
        document.getElementById('adminDropdown').addEventListener('click', function(e) {
            e.stopPropagation();
            document.getElementById('dropdownMenu').classList.toggle('hidden');
        });

        document.addEventListener('click', function(e) {
            if (!document.getElementById('adminDropdown').contains(e.target)) {
                document.getElementById('dropdownMenu').classList.add('hidden');
            }
        });

        // Logout functionality
        document.getElementById('logoutButton').addEventListener('click', function(e) {
            e.preventDefault();
            document.getElementById('logout-form').submit();
        });
    </script>
</body>
</html>
