<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instructor Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .sidebar {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            scrollbar-width: thin;
            scrollbar-color: rgba(255, 255, 255, 0.2) transparent;
            background: linear-gradient(180deg, #1e3a8a 0%, #1e40af 50%, #1d4ed8 100%);
            box-shadow: 4px 0 20px rgba(0, 0, 0, 0.1);
        }

        /* For Webkit browsers (Chrome, Safari) */
        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: transparent;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background-color: rgba(255, 255, 255, 0.2);
            border-radius: 3px;
        }

        .sidebar::-webkit-scrollbar-thumb:hover {
            background-color: rgba(255, 255, 255, 0.3);
        }

        .sidebar-collapsed {
            width: 80px !important;
        }

        .sidebar-collapsed #toggleSidebar .collapse-icon {
            display: none;
        }

        .sidebar-collapsed .nav-text,
        .sidebar-collapsed .logo-text,
        .sidebar-collapsed .user-profile .user-details {
            display: none;
        }

        .sidebar-collapsed .nav-item {
            justify-content: center;
        }

        .content-area {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            margin-left: 0.5rem;
        }

        .active-nav {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.15) 0%, rgba(255, 255, 255, 0.1) 100%);
            border-left: 4px solid #60a5fa;
            box-shadow: 0 4px 12px rgba(96, 165, 250, 0.2);
            transform: translateX(2px);
        }

        .nav-item {
            position: relative;
            overflow: hidden;
        }

        .nav-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            transition: left 0.5s;
        }

        .nav-item:hover::before {
            left: 100%;
        }

        .nav-item:hover {
            transform: translateX(4px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .nav-icon-container {
            transition: all 0.3s ease;
        }

        .nav-item:hover .nav-icon-container {
            transform: scale(1.1);
            box-shadow: 0 4px 12px rgba(96, 165, 250, 0.3);
        }

        .user-profile {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0.05) 100%);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .user-profile:hover {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.15) 0%, rgba(255, 255, 255, 0.1) 100%);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        #toggleSidebar {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0.05) 100%);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        #toggleSidebar:hover {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.15) 0%, rgba(255, 255, 255, 0.1) 100%);
            transform: translateY(-1px);
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 72px !important;
            }

            .nav-text,
            .logo-text {
                display: none;
            }

            .nav-item {
                justify-content: center;
            }
        }

    </style>
</head>
<body class="bg-gray-100 font-sans">
    <!-- Loading System Integration -->
    @include('Instructor.partials.loading-integration')
    
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <div class="sidebar bg-blue-900 text-white w-56 flex flex-col">
            
            @include('Instructor.partials.navigation')
            
        </div>

        <!-- Main Content -->
        <div class="content-area flex-1 overflow-y-auto">
            <!-- Enhanced Top Bar -->
            <div class="bg-white shadow-md p-4 flex justify-between items-center border-b border-gray-100">
                <div class="flex items-center space-x-4">
                    <button id="mobileMenuButton" class="text-gray-500 hover:text-gray-700 md:hidden transition-colors">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    <h2 class="text-xl font-bold text-gray-800 bg-gradient-to-r from-blue-500 to-blue-600 bg-clip-text text-transparent">
                        Dashboard
                    </h2>
                </div>
                <div class="flex items-center space-x-6">
                    <div class="border-l h-8 border-gray-200"></div>
                    <div class="relative group">
                        <button id="adminDropdown" class="flex items-center focus:outline-none space-x-3 bg-gradient-to-r from-blue-500 to-purple-600 text-white px-4 py-2 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300">
                            <div class="h-8 w-8 rounded-full bg-white bg-opacity-20 flex items-center justify-center">
                                @if(auth()->user()->photo)
                                    <img src="{{ asset('storage/' . auth()->user()->photo) }}" alt="Profile Photo" class="w-8 h-8 rounded-full object-cover">
                                @else
                                    <i class="fas fa-user text-white text-sm"></i>
                                @endif
                            </div>
                            <div class="hidden md:block text-left">
                                <p class="text-sm font-medium">{{ auth()->user()->name }}</p>
                                <p class="text-xs opacity-75">Instructor</p>
                            </div>
                            <i class="fas fa-chevron-down text-xs opacity-75 hidden md:block transition-transform group-hover:rotate-180"></i>
                        </button>
                        <div id="dropdownMenu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-xl py-2 z-50 border border-gray-100">
                            <a href="#" onclick="openProfileModal()" class="block px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                                <i class="fas fa-user mr-3"></i>Profile
                            </a>
                            <a href="#" id="logoutButton" class="block px-4 py-3 text-sm text-gray-700 hover:bg-red-50 hover:text-red-600 transition-colors border-t border-gray-100">
                                <i class="fas fa-sign-out-alt mr-3"></i>Log Out
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dashboard Content -->
            <div class="p-6">
                <!-- Welcome Banner -->
                <div class="bg-gradient-to-r from-blue-500 to-blue-700 rounded-xl p-6 text-white mb-6 shadow-lg">
                    <h2 class="text-2xl font-bold mb-2">Welcome back, {{ explode(' ', $userName)[0] }}!</h2>
                    <p class="text-blue-100">Manage your students and track attendance for your program.</p>
                </div>

                <!-- Program Details -->
                <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-xl shadow-lg p-6 mb-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold mb-1">Program Details</h3>
                            @if($assignedPrograms->count() > 0)
                                <p class="text-indigo-100 mb-2">You are assigned to {{ $totalPrograms }} program{{ $totalPrograms !== 1 ? 's' : '' }}:</p>
                                <div class="space-y-3">
                                    @foreach($assignedPrograms as $program)
                                        <div class="bg-white/20 p-3 rounded-lg">
                                            <h4 class="font-semibold">{{ $program->name }}</h4>
                                            <p class="text-sm">{{ $program->description }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-indigo-100">You are assigned to {{ $totalPrograms }} program{{ $totalPrograms !== 1 ? 's' : '' }}</p>
                            @endif
                            <div class="mt-2 text-sm">
                                <span class="bg-white/20 px-2 py-1 rounded">Total Sessions Today: {{ $totalSessionsToday }}</span>
                            </div>
                        </div>
                        <div class="bg-white/20 p-3 rounded-full">
                            <i class="fas fa-graduation-cap text-xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Instructor Dashboard Sections -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6 items-stretch">
                    <!-- Enrolled Students Card -->
                    <div class="bg-white rounded-xl shadow-md p-6 flex flex-col h-full">
                        <div class="flex-grow">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-semibold text-gray-800">Enrolled Students</h3>
                                <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full">Active</span>
                            </div>
                            <div class="space-y-4">
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-600">Total Enrolled</span>
                                    <span class="font-medium">{{ $enrolledStudentsCount }}</span>
                                </div>
                            </div>
                        </div>
                        <button onclick="window.location.href='{{ route('instructor.students') }}'" class="mt-4 w-full bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg transition-colors">
                            View All Students
                        </button>
                    </div>

                    <!-- Attendance Status Card -->
                    <div class="bg-white rounded-xl shadow-md p-6 flex flex-col h-full">
                        <div class="flex-grow">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-semibold text-gray-800">Attendance Status</h3>
                                <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full">Live</span>
                            </div>
                            <div class="space-y-4">
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-600">Last Session</span>
                                    <span class="font-medium">{{ $lastSessionDate }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-600">Present</span>
                                    <span class="font-medium text-green-600">{{ $presentToday }}</span>
                                </div>
                            </div>
                        </div>
                        <button onclick="window.location.href='{{ route('instructor.attendance') }}'" class="mt-4 w-full bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-lg transition-colors">
                            Mark Attendance
                        </button>
                    </div>

                    <!-- Upcoming Session Card -->
                    <div class="bg-white rounded-xl shadow-md p-6 flex flex-col h-full">
                        <div class="flex-grow">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-semibold text-gray-800">Upcoming Session</h3>
                                <span class="bg-purple-100 text-purple-800 text-xs font-medium px-2.5 py-0.5 rounded-full">Next</span>
                            </div>
                            <div class="space-y-4">
                                @if($upcomingSession)
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-600">Date</span>
                                    <span class="font-medium">{{ $upcomingSession['date'] }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-600">Time</span>
                                    <span class="font-medium">{{ $upcomingSession['time'] }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-600">Program</span>
                                    <span class="font-medium">{{ $upcomingSession['program'] }}</span>
                                </div>
                                @else
                                <div class="text-center text-gray-500 py-4">
                                    <i class="fas fa-calendar-times text-2xl mb-2"></i>
                                    <p>No upcoming sessions</p>
                                </div>
                                @endif
                            </div>
                        </div>
                        @if($upcomingSession)
                        <button onclick="window.location.href='{{ route('instructor.attendance') }}'" class="mt-4 w-full bg-purple-600 hover:bg-purple-700 text-white py-2 px-4 rounded-lg transition-colors">
                            View Schedule
                        </button>
                        @else
                        <button disabled class="mt-4 w-full bg-gray-400 text-white py-2 px-4 rounded-lg cursor-not-allowed">
                            No Sessions Scheduled
                        </button>
                        @endif
                    </div>
                </div>

                    <!-- Recent Students Table -->
                    <div class="bg-white rounded-xl shadow-md overflow-hidden mb-6">
                        <div class="p-4 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-800">Recent Enrolled Students</h3>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Program</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Enrollment Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($recentEnrolledStudents as $enrollment)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-full flex items-center justify-center">
                                                    @if($enrollment->user && $enrollment->user->photo)
                                                    <img src="{{ asset('storage/' . $enrollment->user->photo) }}" alt="Photo" class="w-12 h-12 rounded-full object-cover">
                                                    @else
                                                    <i class="fas fa-user text-blue-600"></i>
                                                    @endif
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">{{ ucfirst($enrollment->user->name) }}</div>
                                                    <div class="text-sm text-gray-500">{{ $enrollment->user->email }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $enrollment->program->name ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $enrollment->created_at->format('F d, Y') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Enrolled</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('instructor.students.view', $enrollment->id) }}" class="text-blue-600 hover:text-blue-900">View</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
            </div>
        </div>
    </div>

    <script>
        // Admin dropdown toggle
        document.getElementById('adminDropdown').addEventListener('click', function(e) {
            e.stopPropagation();
            document.getElementById('dropdownMenu').classList.toggle('hidden');
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function() {
            document.getElementById('dropdownMenu').classList.add('hidden');
        });
        // Toggle sidebar collapse
        document.getElementById('toggleSidebar').addEventListener('click', function() {
            const sidebar = document.querySelector('.sidebar');
            const contentArea = document.querySelector('.content-area');
            const icon = this.querySelector('i');
            
            sidebar.classList.toggle('sidebar-collapsed');
            contentArea.classList.toggle('ml-1');

            // Save collapsed state
            if (sidebar.classList.contains('sidebar-collapsed')) {
                localStorage.setItem('sidebar-collapsed', 'true');
                icon.classList.remove('fa-chevron-left');
                icon.classList.add('fa-chevron-right');
            } else {
                localStorage.setItem('sidebar-collapsed', 'false');
                icon.classList.remove('fa-chevron-right');
                icon.classList.add('fa-chevron-left');
            }
        });

        // Mobile menu toggle
        document.getElementById('mobileMenuButton').addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('hidden');
        });

        // Set active nav item and handle navigation
        const navItems = document.querySelectorAll('.nav-item');
        navItems.forEach(item => {
            item.addEventListener('click', function() {
                // Remove active class from all items
                navItems.forEach(nav => nav.classList.remove('active-nav'));
                // Add active class to clicked item
                this.classList.add('active-nav');
                
                // Auto-expand sidebar if collapsed
                const sidebar = document.querySelector('.sidebar');
                if (sidebar.classList.contains('sidebar-collapsed')) {
                    sidebar.classList.remove('sidebar-collapsed');
                    document.querySelector('.content-area').classList.remove('ml-1');
                    localStorage.setItem('sidebar-collapsed', 'false');
                    
                    // Update toggle icon
                    const toggleIcon = document.querySelector('#toggleSidebar i');
                    if (toggleIcon) {
                        toggleIcon.classList.remove('fa-chevron-right');
                        toggleIcon.classList.add('fa-chevron-left');
                    }
                }
                
                const url = this.getAttribute('data-url');
                if (url) {
                    // Show loading overlay before navigation
                    // Use the standard loading system
                    if (window.loadingManager) {
                        window.loadingManager.show();
                    }
                    
                    // Navigate after a short delay
                    setTimeout(() => {
                        window.location.href = url;
                    }, 300);
                }
            });
        });

        // Responsive adjustments
        function handleResize() {
            if (window.innerWidth < 768) {
                document.querySelector('.sidebar').classList.add('sidebar-collapsed');
                document.querySelector('.content-area').classList.add('ml-1');
            } else {
                document.querySelector('.sidebar').classList.remove('sidebar-collapsed');
                document.querySelector('.content-area').classList.add('ml-1');
            }
        }

        window.addEventListener('resize', handleResize);
        handleResize(); // Run once on load

        // Function to set active nav item based on current URL
        function setActiveNavItem() {
            const currentPath = window.location.pathname;
            const navItems = document.querySelectorAll('.nav-item');
            navItems.forEach(item => {
                const url = item.getAttribute('data-url');
                if (url) {
                    // Special handling for attendance URLs - check if current path starts with attendance
                    if (url.includes('/instructor/attendance') && currentPath.startsWith('/instructor/attendance')) {
                        // Remove active class from all items
                        navItems.forEach(nav => nav.classList.remove('active-nav'));
                        // Add active class to attendance item
                        item.classList.add('active-nav');
                    } else if (url === currentPath) {
                        // Remove active class from all items
                        navItems.forEach(nav => nav.classList.remove('active-nav'));
                        // Add active class to current page item
                        item.classList.add('active-nav');
                    }
                }
            });
        }

        // Set active nav item on page load
        setActiveNavItem();

        // Logout functionality
        document.getElementById('logoutButton').addEventListener('click', function(e) {
            e.preventDefault();
            document.getElementById('logout-form').submit();
        });

        window.addEventListener('DOMContentLoaded', () => {
            const sidebar = document.querySelector('.sidebar');
            const contentArea = document.querySelector('.content-area');
            const toggleIcon = document.querySelector('#toggleSidebar i');

            if (localStorage.getItem('sidebar-collapsed') === 'true') {
                sidebar.classList.add('sidebar-collapsed');
                contentArea.classList.add('ml-1');

                // Set the correct icon direction
                if (toggleIcon.classList.contains('fa-chevron-left')) {
                    toggleIcon.classList.remove('fa-chevron-left');
                    toggleIcon.classList.add('fa-chevron-right');
                }
            } else {
                sidebar.classList.remove('sidebar-collapsed');
                contentArea.classList.remove('ml-1');

                // Set the correct icon direction
                if (toggleIcon.classList.contains('fa-chevron-right')) {
                    toggleIcon.classList.remove('fa-chevron-right');
                    toggleIcon.classList.add('fa-chevron-left');
                }
            }
        });

    </script>

    <!-- Profile Modal -->
    @include('Instructor.partials.profile-modal')

    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
        @csrf
    </form>
    @stack('scripts')
</body>
</html>
