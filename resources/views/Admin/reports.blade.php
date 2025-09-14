<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Reports/Monitoring</title>
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
            <!-- Enhanced Top Bar -->
            <div class="bg-white shadow-md p-4 flex justify-between items-center border-b border-gray-100">
                <div class="flex items-center space-x-4">
                    <button id="mobileMenuButton" class="text-gray-500 hover:text-gray-700 md:hidden transition-colors">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    <h2 class="text-xl font-bold text-gray-800 bg-gradient-to-r from-blue-500 to-blue-600 bg-clip-text text-transparent">
                        Reports & Monitoring
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

            <!-- Hidden logout form -->
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                @csrf
            </form>

            <!-- Reports Content -->
            <div class="p-6">
                <!-- Enhanced Filters Section -->
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-xl shadow-sm mb-8">
                    <div class="p-6">
                        <div class="flex items-center mb-4">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-filter text-blue-600"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-800">Report Filters & Controls</h3>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Date Range</label>
                                <select id="dateRangeSelect" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                    <option value="7" {{ request('date_range') == '7' ? 'selected' : '' }}>Last 7 days</option>
                                    <option value="30" {{ request('date_range') == '30' ? 'selected' : '' }}>Last 30 days</option>
                                    <option value="90" {{ request('date_range') == '90' ? 'selected' : '' }}>Last 90 days</option>
                                    <option value="180" {{ request('date_range') == '180' ? 'selected' : '' }}>Last 6 months</option>
                                    <option value="365" {{ request('date_range') == '365' ? 'selected' : '' }}>Last year</option>
                                    <option value="all" {{ request('date_range') == 'all' || !request('date_range') ? 'selected' : '' }}>All time</option>
                                    <option value="custom" {{ request('date_range') == 'custom' ? 'selected' : '' }}>Custom range</option>
                                </select>
                            </div>
                            <div id="customDateRange" class="{{ request('date_range') == 'custom' ? '' : 'hidden' }}">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Custom Date Range</label>
                                <div class="flex items-center space-x-2">
                                    <input type="date" id="startDate" value="{{ request('start_date') }}" class="flex-1 px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                    <span class="text-gray-500">to</span>
                                    <input type="date" id="endDate" value="{{ request('end_date') }}" class="flex-1 px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                    <button id="applyCustomRange" class="px-4 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200 shadow-lg hover:shadow-xl">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Enhanced Quick Stats -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-6 mb-8">
                    <!-- Total Enrollments -->
                    <div class="bg-gradient-to-br from-blue-500 to-blue-600 text-white p-6 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-sm font-medium opacity-90">Total Enrollments</h3>
                                <p class="text-3xl font-bold mt-2">{{ number_format($totalEnrollments) }}</p>
                                <p class="text-xs opacity-75 mt-1">All time enrollments</p>
                            </div>
                            <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                                <i class="fas fa-users text-2xl"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Total Users -->
                    <div class="bg-gradient-to-br from-green-500 to-green-600 text-white p-6 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-sm font-medium opacity-90">Total Users</h3>
                                <p class="text-3xl font-bold mt-2">{{ number_format($totalUsers) }}</p>
                                <p class="text-xs opacity-75 mt-1">System users</p>
                            </div>
                            <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                                <i class="fas fa-user-friends text-2xl"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Most Popular Program -->
                    <div class="bg-gradient-to-br from-purple-500 to-purple-600 text-white p-6 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                        <div class="flex items-start justify-between">
                            <div class="flex-1 min-w-0">
                                <h3 class="text-sm font-medium opacity-90">Most Popular</h3>
                                <p class="text-sm font-bold mt-2 leading-tight break-words" title="{{ $mostPopularProgram ? $mostPopularProgram->program->name : 'N/A' }}">
                                    {{ $mostPopularProgram ? $mostPopularProgram->program->name : 'N/A' }}
                                </p>
                                <p class="text-xs opacity-75 mt-1">{{ $mostPopularProgram ? $mostPopularProgram->count : 0 }} students</p>
                            </div>
                            <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center flex-shrink-0 ml-2">
                                <i class="fas fa-trophy text-2xl"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Total Certificates -->
                    <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 text-white p-6 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-sm font-medium opacity-90">Certificates</h3>
                                <p class="text-3xl font-bold mt-2">{{ number_format($totalCertificates) }}</p>
                                <p class="text-xs opacity-75 mt-1">Issued certificates</p>
                            </div>
                            <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                                <i class="fas fa-certificate text-2xl"></i>
                            </div>
                        </div>
                    </div>

                    <!-- QR Code Statistics -->
                    <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 text-white p-6 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-sm font-medium opacity-90">QR Codes</h3>
                                <p class="text-3xl font-bold mt-2">{{ number_format($totalQrCodes) }}</p>
                                <p class="text-xs opacity-75 mt-1">{{ $usedQrCodes }} used ({{ $qrCodeUsageRate }}%)</p>
                            </div>
                            <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                                <i class="fas fa-qrcode text-2xl"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Total Online Payments -->
                    <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 text-white p-6 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                        <div class="flex items-start justify-between">
                            <div class="flex-1 min-w-0">
                                <h3 class="text-sm font-medium opacity-90">Online Payments</h3>
                                <p class="text-2xl font-bold mt-2">₱{{ number_format($totalOnlinePayments, 0) }}</p>
                                <div class="text-xs opacity-75 mt-2 space-y-1">
                                    <div class="flex justify-between">
                                        <span>Registration:</span>
                                        <span class="font-medium">₱{{ number_format($registrationPayments, 2) }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span>Session Fee:</span>
                                        <span class="font-medium">₱{{ number_format($sessionPayments, 2) }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center flex-shrink-0 ml-2">
                                <i class="fas fa-credit-card text-2xl"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Enhanced Charts Section -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                    <!-- Enrollment Over Time Chart -->
                    <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-4 border-b border-gray-100">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                        <i class="fas fa-chart-line text-blue-600"></i>
                                    </div>
                                    <h3 class="text-lg font-semibold text-gray-800">Enrollment Trends</h3>
                                </div>
                                <div class="text-sm text-gray-500">
                                    <i class="fas fa-calendar-alt mr-1"></i>Weekly Overview
                                </div>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="h-80">
                                <canvas id="enrollmentChart" width="400" height="320"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Enrollments by Program Chart -->
                    <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                        <div class="bg-gradient-to-r from-green-50 to-emerald-50 px-6 py-4 border-b border-gray-100">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                        <i class="fas fa-chart-pie text-green-600"></i>
                                    </div>
                                    <h3 class="text-lg font-semibold text-gray-800">Program Distribution</h3>
                                </div>
                                <div class="text-sm text-gray-500">
                                    <i class="fas fa-users mr-1"></i>Student Enrollment
                                </div>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="h-80">
                                <canvas id="programChart" width="400" height="320"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const enrollmentCtx = document.getElementById('enrollmentChart').getContext('2d');
                        const programCtx = document.getElementById('programChart').getContext('2d');

                        // Enrollment Over Time Chart (Weekly)
                        const enrollmentData = @json($enrollmentByWeek);
                        const enrollmentLabels = enrollmentData.map(item => item.week);
                        const enrollmentCounts = enrollmentData.map(item => item.count);

                        new Chart(enrollmentCtx, {
                            type: 'line',
                            data: {
                                labels: enrollmentLabels,
                                datasets: [{
                                    label: 'Enrollments',
                                    data: enrollmentCounts,
                                    borderColor: 'rgb(59, 130, 246)',
                                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                                    tension: 0.1,
                                    fill: true
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: {
                                        display: false
                                    },
                                    tooltip: {
                                        callbacks: {
                                            title: function(context) {
                                                return 'Week: ' + context[0].label;
                                            },
                                            label: function(context) {
                                                return 'Enrollments: ' + context.parsed.y;
                                            }
                                        }
                                    }
                                },
                                scales: {
                                    x: {
                                        title: {
                                            display: true,
                                            text: 'Week'
                                        }
                                    },
                                    y: {
                                        beginAtZero: true,
                                        title: {
                                            display: true,
                                            text: 'Number of Enrollments'
                                        },
                                        ticks: {
                                            stepSize: 1
                                        }
                                    }
                                }
                            }
                        });

                        // Enrollments by Program Chart
                        const programData = @json($programEnrollments);
                        const programLabels = programData.map(item => {
                            // Get program name from the program_id
                            const programNames = @json($programs->pluck('name', 'id'));
                            return programNames[item.program_id] || 'Unknown Program';
                        });
                        const programCounts = programData.map(item => item.count);

                        // Generate colors for programs
                        const programColors = [
                            '#10B981', '#F59E0B', '#EF4444', '#8B5CF6', '#06B6D4', 
                            '#84CC16', '#F97316', '#EC4899', '#6366F1', '#14B8A6'
                        ];

                        new Chart(programCtx, {
                            type: 'pie',
                            data: {
                                labels: programLabels,
                                datasets: [{
                                    data: programCounts,
                                    backgroundColor: programColors.slice(0, programLabels.length),
                                    borderWidth: 2,
                                    borderColor: '#ffffff'
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: {
                                        position: 'bottom',
                                        labels: {
                                            padding: 20,
                                            usePointStyle: true
                                        }
                                    },
                                    tooltip: {
                                        callbacks: {
                                            label: function(context) {
                                                const label = context.label || '';
                                                const value = context.parsed;
                                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                                const percentage = ((value / total) * 100).toFixed(1);
                                                return `${label}: ${value} (${percentage}%)`;
                                            }
                                        }
                                    }
                                }
                            }
                        });
                    });
                </script>

                <!-- Enhanced User Activity Logs -->
                <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden mb-8">
                    <div class="bg-gradient-to-r from-purple-50 to-pink-50 px-6 py-4 border-b border-gray-100">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-user-clock text-purple-600"></i>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-800">User Activity Logs</h3>
                            </div>
                            <button class="bg-gradient-to-r from-purple-600 to-purple-700 text-white px-4 py-2 rounded-lg text-sm hover:from-purple-700 hover:to-purple-800 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition-all duration-200 shadow-lg hover:shadow-xl">
                                <i class="fas fa-download mr-2"></i>Export to Excel
                            </button>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <div class="flex items-center">
                                            <i class="fas fa-user mr-2"></i>User
                                        </div>
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <div class="flex items-center">
                                            <i class="fas fa-user-tag mr-2"></i>Role
                                        </div>
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <div class="flex items-center">
                                            <i class="fas fa-sign-in-alt mr-2"></i>Last Login
                                        </div>
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <div class="flex items-center">
                                            <i class="fas fa-clock mr-2"></i>Last Activity
                                        </div>
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <div class="flex items-center">
                                            <i class="fas fa-circle mr-2"></i>Status
                                        </div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($userActivities as $activity)
                                <tr class="hover:bg-gray-50 transition-colors duration-200">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-blue-600 rounded-full flex items-center justify-center mr-3">
                                                <span class="text-white text-sm font-medium">{{ substr($activity->name, 0, 1) }}</span>
                                            </div>
                                            <span class="font-medium text-gray-900">{{ $activity->name }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ ucfirst($activity->role) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $activity->created_at->format('M d, Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $activity->updated_at->format('M d, Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $activity->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                            <div class="w-1.5 h-1.5 rounded-full {{ $activity->status === 'active' ? 'bg-green-400' : 'bg-yellow-400' }} mr-1.5"></div>
                                            {{ ucfirst($activity->status) }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-16 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                                <i class="fas fa-users text-gray-300 text-2xl"></i>
                                            </div>
                                            <p class="text-gray-500 text-lg font-medium mb-2">No user activities found</p>
                                            <p class="text-gray-400 text-sm">User activity logs will appear here when available.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                        @if($userActivities->hasPages())
                        <div class="bg-gray-50 px-6 py-3 border-t border-gray-200">
                            {{ $userActivities->links() }}
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Enhanced Payment Logs -->
                <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                    <div class="bg-gradient-to-r from-emerald-50 to-teal-50 px-6 py-4 border-b border-gray-100">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-file-invoice-dollar text-emerald-600"></i>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-800">Payment Transaction Logs</h3>
                            </div>
                            <button class="bg-gradient-to-r from-emerald-600 to-emerald-700 text-white px-4 py-2 rounded-lg text-sm hover:from-emerald-700 hover:to-emerald-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition-all duration-200 shadow-lg hover:shadow-xl">
                                <i class="fas fa-file-pdf mr-2"></i>Export to PDF
                            </button>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <div class="flex items-center">
                                            <i class="fas fa-user-graduate mr-2"></i>Student Name
                                        </div>
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <div class="flex items-center">
                                            <i class="fas fa-money-bill-wave mr-2"></i>Amount
                                        </div>
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <div class="flex items-center">
                                            <i class="fas fa-tag mr-2"></i>Type
                                        </div>
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <div class="flex items-center">
                                            <i class="fas fa-calendar mr-2"></i>Date
                                        </div>
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <div class="flex items-center">
                                            <i class="fas fa-receipt mr-2"></i>Receipt
                                        </div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($paymentLogs as $payment)
                                <tr class="hover:bg-gray-50 transition-colors duration-200">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 bg-gradient-to-r from-emerald-500 to-emerald-600 rounded-full flex items-center justify-center mr-3">
                                                <span class="text-white text-sm font-medium">{{ substr($payment->student->name ?? 'N', 0, 1) }}</span>
                                            </div>
                                            <span class="font-medium text-gray-900">{{ $payment->student->name ?? 'N/A' }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-lg font-semibold text-emerald-600">₱{{ number_format($payment->amount, 2) }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="space-y-1">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $payment->session_count > 0 ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                                <i class="fas {{ $payment->session_count > 0 ? 'fa-calendar-check' : 'fa-user-plus' }} mr-1"></i>
                                                {{ $payment->session_count > 0 ? 'Session Payment' : 'Registration Fee' }}
                                            </span>
                                            <div class="text-xs text-gray-500">
                                                <i class="fas fa-credit-card mr-1"></i>{{ ucfirst($payment->payment_method) }}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $payment->payment_date->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($payment->transaction_id)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                <i class="fas fa-hashtag mr-1"></i>
                                                {{ $payment->transaction_id }}
                                            </span>
                                        @else
                                            <span class="text-gray-400 text-sm">N/A</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-16 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                                <i class="fas fa-file-invoice-dollar text-gray-300 text-2xl"></i>
                                            </div>
                                            <p class="text-gray-500 text-lg font-medium mb-2">No payment logs found</p>
                                            <p class="text-gray-400 text-sm">Payment logs will appear here when available.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                        @if($paymentLogs->hasPages())
                        <div class="bg-gray-50 px-6 py-3 border-t border-gray-200">
                            {{ $paymentLogs->links() }}
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
    <script>
        // Admin dropdown toggle
        const adminDropdown = document.getElementById('adminDropdown');
        if (adminDropdown) {
            adminDropdown.addEventListener('click', function(e) {
                e.stopPropagation();
                const dropdownMenu = document.getElementById('dropdownMenu');
                if (dropdownMenu) {
                    dropdownMenu.classList.toggle('hidden');
                }
            });
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function() {
            const dropdownMenu = document.getElementById('dropdownMenu');
            if (dropdownMenu) {
                dropdownMenu.classList.add('hidden');
            }
        });
        // Toggle sidebar collapse
        const toggleSidebar = document.getElementById('toggleSidebar');
        if (toggleSidebar) {
            toggleSidebar.addEventListener('click', function() {
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
        }

        // Mobile menu toggle
        const mobileMenuButton = document.getElementById('mobileMenuButton');
        if (mobileMenuButton) {
            mobileMenuButton.addEventListener('click', function() {
                const sidebar = document.querySelector('.sidebar');
                if (sidebar) {
                    sidebar.classList.toggle('hidden');
                }
            });
        }

        // Set active nav item
        const navItems = document.querySelectorAll('.nav-item');
        navItems.forEach(item => {
            item.addEventListener('click', function() {
                const url = this.getAttribute('data-url');
                if (url) {
                    // Only navigate if it's a different page
                    if (window.location.pathname !== url) {
                        // Show loading animation before navigation
                        // Use the standard loading system
                        if (window.loadingManager) {
                            window.loadingManager.show();
                        }
                        
                        // Navigate after a short delay
                        setTimeout(() => {
                            window.location.href = url;
                        }, 300);
                    }
                }
            });
        });

        // Function to set active nav item based on current URL
        function setActiveNavItem() {
            const currentPath = window.location.pathname;
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

        // Date Range Filtering Functionality
        const dateRangeSelect = document.getElementById('dateRangeSelect');
        const customDateRange = document.getElementById('customDateRange');
        const startDate = document.getElementById('startDate');
        const endDate = document.getElementById('endDate');
        const applyCustomRange = document.getElementById('applyCustomRange');

        // Initialize custom date range visibility on page load
        if (dateRangeSelect && customDateRange) {
            if (dateRangeSelect.value === 'custom') {
                customDateRange.classList.remove('hidden');
            } else {
                customDateRange.classList.add('hidden');
            }
        }

        // Handle date range selection
        if (dateRangeSelect) {
            dateRangeSelect.addEventListener('change', function() {
                if (this.value === 'custom') {
                    customDateRange.classList.remove('hidden');
                } else {
                    customDateRange.classList.add('hidden');
                    // Auto-apply filter for predefined ranges (including 'all')
                    applyDateFilter(this.value);
                }
            });
        }

        // Handle custom date range application
        if (applyCustomRange) {
            applyCustomRange.addEventListener('click', function() {
                if (startDate.value && endDate.value) {
                    if (new Date(startDate.value) <= new Date(endDate.value)) {
                        applyCustomDateFilter(startDate.value, endDate.value);
                    } else {
                        alert('Start date must be before or equal to end date.');
                    }
                } else {
                    alert('Please select both start and end dates.');
                }
            });
        }

        // Function to apply predefined date filters
        function applyDateFilter(days) {
            const url = new URL(window.location);
            url.searchParams.set('date_range', days);
            
            if (days === 'all') {
                // For 'all time', remove start_date and end_date parameters
                url.searchParams.delete('start_date');
                url.searchParams.delete('end_date');
            } else {
                // For specific day ranges, set the date parameters
                const endDate = new Date();
                const startDate = new Date();
                startDate.setDate(endDate.getDate() - parseInt(days));
                
                url.searchParams.set('start_date', startDate.toISOString().split('T')[0]);
                url.searchParams.set('end_date', endDate.toISOString().split('T')[0]);
            }
            
            window.location.href = url.toString();
        }

        // Function to apply custom date filter
        function applyCustomDateFilter(start, end) {
            const url = new URL(window.location);
            url.searchParams.set('date_range', 'custom');
            url.searchParams.set('start_date', start);
            url.searchParams.set('end_date', end);
            window.location.href = url.toString();
        }

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
                contentArea.classList.remove('ml-1');

                // Set the correct icon direction
                if (toggleIcon.classList.contains('fa-chevron-left')) {
                    toggleIcon.classList.remove('fa-chevron-left');
                    toggleIcon.classList.add('fa-chevron-right');
                }
            } else {
                sidebar.classList.remove('sidebar-collapsed');
                contentArea.classList.add('ml-1');
                if (toggleIcon.classList.contains('fa-chevron-right')) {
                    toggleIcon.classList.remove('fa-chevron-right');
                    toggleIcon.classList.add('fa-chevron-left');
                }
            }
        });

    </script>
    @stack('scripts')

    <!-- Include Admin Profile Modal -->
    @include('Admin.Top.profile')
    
    <!-- Loading System Integration -->
    @include('partials.loading-integration')
    
</body>
</html>
