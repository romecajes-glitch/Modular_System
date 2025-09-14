<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
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

        /* Enhanced navigation hover effects */
        .nav-item {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .nav-item:hover {
            background-color: rgba(59, 130, 246, 0.2);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            transform: scale(1.02);
        }

        .nav-icon-container {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .nav-icon-container:hover {
            background-color: rgba(59, 130, 246, 0.8);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        .nav-text {
            transition: color 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .nav-item:hover .nav-text {
            color: white;
        }

        .nav-item:hover .nav-icon-container i {
            color: white;
        }

        .nav-item:hover .ml-auto {
            opacity: 1;
            transform: translateX(4px);
        }

        /* Enhanced logo and user profile hover effects */
        #toggleSidebar:hover {
            background-color: rgba(59, 130, 246, 0.2);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        #toggleSidebar:hover .bg-gradient-to-r {
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
            transform: scale(1.05);
        }

        #toggleSidebar:hover .logo-text {
            color: rgb(219, 234, 254);
        }

        #toggleSidebar:hover .collapse-icon {
            transform: scale(1.1);
        }

        #toggleSidebar:hover .collapse-icon i {
            color: white;
        }

        .user-profile:hover {
            background-color: rgba(59, 130, 246, 0.2);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .user-profile:hover .bg-gradient-to-r {
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
            transform: scale(1.05);
        }

        .user-profile:hover .font-semibold {
            color: rgb(219, 234, 254);
        }

        .user-profile:hover .text-xs {
            color: rgb(147, 197, 253);
        }

        .content-area {
            transition: all 0.3s ease;
            margin-left: 0.5rem;
        }

        .active-nav {
            background-color: rgba(255, 255, 255, 0.1);
            border-left: 4px solid white;
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
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <div class="sidebar bg-blue-900 text-white w-56 flex flex-col">
            
            @include('Admin.partials.navigation')
            
        </div>

        <!-- Main Content -->
        <div class="content-area flex-1 overflow-y-auto">
            <!-- Top Bar -->
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
                                <p class="text-xs opacity-75">Administrator</p>
                            </div>
                            <i class="fas fa-chevron-down text-xs opacity-75 hidden md:block transition-transform group-hover:rotate-180"></i>
                        </button>
                        <div id="dropdownMenu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-xl py-2 z-50 border border-gray-100">
                            <a href="#" onclick="openProfileModal(); return false;" class="block px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">
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
                    <h2 class="text-2xl font-bold mb-2">Welcome back, {{ ucfirst(explode(' ', $adminUser->name)[0]) }}!</h2>
                    <p class="text-blue-100">Here's what's happening with your account today.</p>
                </div>
                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                                <i class="fas fa-users text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Total Students</p>
                                <p class="text-2xl font-semibold text-gray-800">{{ $totalStudents }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-green-100 text-green-600">
                                <i class="fas fa-user-graduate text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Active Enrollments</p>
                                <p class="text-2xl font-semibold text-gray-800">{{ $activeEnrollments }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                                <i class="fas fa-chalkboard-teacher text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Instructors</p>
                                <p class="text-2xl font-semibold text-gray-800">{{ $instructors }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                                <i class="fas fa-certificate text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Certificates Issued</p>
                                <p class="text-2xl font-semibold text-gray-800">{{ $certificatesIssued }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity and Quick Actions -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Recent Activity -->
                    <div class="lg:col-span-2 bg-white rounded-lg shadow p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-lg font-semibold text-gray-800">Recent Activity</h2>
                        </div>
                        <div class="space-y-4">
                            @foreach ($recentEnrollments as $enroll)
                            @if($enroll->user && $enroll->user->status === 'inactive')
                                @continue
                            @endif
                            <div class="flex items-start">
                                <div class="p-2 rounded-full {{ $enroll->is_re_enrollment ? 'bg-orange-100 text-orange-600' : 'bg-blue-100 text-blue-600' }}">
                                    <i class="fas {{ $enroll->is_re_enrollment ? 'fa-redo' : 'fa-user-plus' }}"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium">
                                        {{ $enroll->is_re_enrollment ? 'Re-enrollment request received' : 'New enrollment received' }}
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        {{ ucfirst($enroll->first_name) }} {{ ucfirst($enroll->last_name) }} {{  $enroll->suffix_name }} {{ $enroll->is_re_enrollment ? 're-applied for' : 'enrolled in' }} {{ $enroll->program->name ?? 'No Program' }}
                                    </p>
                                    <p class="text-xs text-gray-400 mt-1">
                                        {{ $enroll->created_at->diffForHumans() }}
                                    </p>
                                </div>
                            </div>
                            @endforeach

                            @foreach ($recentCertificates as $cert)
                            @if($cert->user && $cert->user->status === 'inactive')
                                @continue
                            @endif
                            <div class="flex items-start">
                                <div class="p-2 rounded-full bg-green-100 text-green-600">
                                    <i class="fas fa-certificate"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium">Certificate generated</p>
                                    <p class="text-xs text-gray-500">
                                        Certificate for {{ $cert->user->name ?? 'Unknown' }} in {{ $cert->program }}
                                    </p>
                                    <p class="text-xs text-gray-400 mt-1">
                                        {{ $cert->created_at->diffForHumans() }}
                                    </p>
                                </div>
                            </div>
                            @endforeach

                            @foreach ($recentPayments as $payment)
                            @if($payment->student && $payment->student->status === 'inactive')
                                @continue
                            @endif
                            <div class="flex items-start">
                                <div class="p-2 rounded-full bg-green-100 text-green-600">
                                    <i class="fas fa-money-bill-wave"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium">Payment received</p>
                                    <p class="text-xs text-gray-500">
                                    {{ $payment->student->name ?? 'Unknown Student' }} paid ₱{{ number_format($payment->amount, 2) }} for
                                    @if($payment->payment_type === 'registration')
                                        Registration Fee
                                    @else
                                        {{ $payment->session_count }} session{{ $payment->session_count != 1 ? 's' : '' }}
                                    @endif
                                    </p>
                                    <p class="text-xs text-gray-400 mt-1">
                                        {{ $payment->payment_date->diffForHumans() }}
                                    </p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4">Quick Actions</h2>
                        <div class="grid grid-cols-2 gap-4">

                            <!-- New Enrollment -->
                            <a href="{{ route('admin.enrollments') }}" class="p-4 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-600 flex flex-col items-center">
                                <i class="fas fa-user-plus text-xl mb-2"></i>
                                <span class="text-sm">New Enrollment</span>
                            </a>

                            <!-- Edit User -->
                            <a href="{{ route('admin.users') }}" class="p-4 rounded-lg bg-green-50 hover:bg-green-100 text-green-600 flex flex-col items-center">
                                <i class="fas fa-user-edit text-xl mb-2"></i>
                                <span class="text-sm">Edit User</span>
                            </a>

                            <!-- Generate Certificate -->
                            <a href="{{ route('admin.certificates') }}" class="p-4 rounded-lg bg-purple-50 hover:bg-purple-100 text-purple-600 flex flex-col items-center">
                                <i class="fas fa-file-certificate text-xl mb-2"></i>
                                <span class="text-sm">Generate Cert</span>
                            </a>

                            <!-- View Reports -->
                            <a href="{{ route('admin.reports') }}" class="p-4 rounded-lg bg-yellow-50 hover:bg-yellow-100 text-yellow-600 flex flex-col items-center">
                                <i class="fas fa-chart-pie text-xl mb-2"></i>
                                <span class="text-sm">View Reports</span>
                            </a>
                        </div>
                    </div>
                </div>
                <!-- Recent Enrollments -->
                <div class="mt-6 bg-white rounded-lg shadow overflow-hidden">
                    <div class="flex justify-between items-center p-6 border-b">
                        <h2 class="text-lg font-semibold text-gray-800">Recent Enrollments</h2>
                        <a href="{{ route('admin.enrollments') }}" class="text-sm text-blue-600 hover:underline">
                            View All
                        </a>
                    </div>

                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Student</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Program</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($recentEnrollments as $enrollment)
                            @if($enrollment->user && $enrollment->user->status === 'inactive')
                                @continue
                            @endif
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                            @if($enrollment->photo)
                                            <img src="{{ asset('storage/' . $enrollment->photo) }}" alt="Photo" class="w-12 h-12 rounded-full object-cover">
                                            @elseif($enrollment->user && $enrollment->user->photo)
                                            <img src="{{ asset('storage/' . $enrollment->user->photo) }}" alt="Photo" class="w-12 h-12 rounded-full object-cover">
                                            @else
                                            <img src="{{ asset('pictures/default-user.png') }}" alt="Default Photo" class="w-12 h-12 rounded-full object-cover">
                                            @endif
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ ucfirst($enrollment->last_name) }}, {{ ucfirst($enrollment->first_name) }} {{ ucfirst($enrollment->middle_name) }} {{ $enrollment->suffix_name }}</div>
                                            <div class="text-sm text-gray-500">{{ $enrollment->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $enrollment->program->name ?? 'No Program' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $enrollment->created_at->format('F d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($enrollment->status === 'pending')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            Pending Review
                                        </span>
                                    @elseif($enrollment->status === 'approved')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Approved
                                        </span>
                                    @elseif($enrollment->status === 'enrolled')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                            Enrolled
                                        </span>
                                    @elseif($enrollment->status === 'rejected')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            Rejected
                                        </span>
                                    @elseif($enrollment->status === 'completed')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                                            Completed
                                        </span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                            {{ ucfirst($enrollment->status) }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('admin.enrollments') }}" class="text-blue-600 hover:text-blue-900">View</a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-gray-500">No recent enrollments.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
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
            sidebar.classList.toggle('sidebar-collapsed');
            contentArea.classList.toggle('ml-1');

            // Save collapsed state
            if (sidebar.classList.contains('sidebar-collapsed')) {
                localStorage.setItem('sidebar-collapsed', 'true');
            } else {
                localStorage.setItem('sidebar-collapsed', 'false');
            }

            // Rotate the icon
            const icon = this.querySelector('i');
            icon.classList.toggle('fa-chevron-left');
            icon.classList.toggle('fa-chevron-right');
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

        // Function to set active nav item based on current URL
        function setActiveNavItem() {
            const currentPath = window.location.pathname;
            const navItems = document.querySelectorAll('.nav-item');
            navItems.forEach(item => {
                const url = item.getAttribute('data-url');
                if (url) {
                    // Special handling for attendance URLs - check if current path starts with attendance
                    if (url.includes('/admin/attendance') && currentPath.startsWith('/admin/attendance')) {
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

    <!-- Admin Dashboard Loading Functionality -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Wait for loading manager to be available
            const waitForLoadingManager = () => {
                if (typeof window.loadingManager !== 'undefined') {
                    initAdminDashboardLoading();
                } else {
                    setTimeout(waitForLoadingManager, 100);
                }
            };
            
            waitForLoadingManager();
        });

        function initAdminDashboardLoading() {
            // Add loading to Quick Action links
            const quickActionLinks = document.querySelectorAll('.grid.grid-cols-2 a');
            quickActionLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    const actionText = this.querySelector('span').textContent;
                    window.loadingManager.show('Loading ' + actionText, 'Please wait while we navigate to the ' + actionText.toLowerCase() + ' page');
                });
            });

            // Profile modal button should NOT trigger loading (it's a modal, not navigation)
            // Removed loading trigger for profile modal

            // Add loading to logout button
            const logoutButton = document.getElementById('logoutButton');
            if (logoutButton) {
                logoutButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    window.loadingManager.show('Logging Out', 'Please wait while we log you out');
                    
                    // Submit the logout form after showing loading
                    setTimeout(() => {
                        document.getElementById('logout-form').submit();
                    }, 1000);
                });
            }

            // Add loading to navigation links in sidebar
            const navLinks = document.querySelectorAll('.nav-link, .sidebar-link');
            navLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    const linkText = this.textContent.trim();
                    if (linkText && !this.href.includes('#')) {
                        window.loadingManager.show('Loading ' + linkText, 'Please wait while we navigate to ' + linkText);
                    }
                });
            });
        }
    </script>

    <!-- Include Admin Profile Modal -->
    @include('Admin.Top.profile')

    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
        @csrf
    </form>
    @stack('scripts')
    
    <!-- Loading System Integration -->
    @include('partials.loading-integration')
</body>
</html>
