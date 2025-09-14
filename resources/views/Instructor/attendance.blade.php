<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Attendance Management - Instructor</title>
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
            border-left: 4px solid white;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
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

        .user-profile:hover {
            background-color: rgba(59, 130, 246, 0.2);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            transform: scale(1.02);
        }

        /* Session tabs horizontal scrolling */
        .session-tabs-container {
            overflow-x: auto;
            overflow-y: hidden;
            scrollbar-width: thin;
            scrollbar-color: rgba(59, 130, 246, 0.3) transparent;
        }

        .session-tabs-container::-webkit-scrollbar {
            height: 4px;
        }

        .session-tabs-container::-webkit-scrollbar-track {
            background: transparent;
        }

        .session-tabs-container::-webkit-scrollbar-thumb {
            background-color: rgba(59, 130, 246, 0.3);
            border-radius: 2px;
        }

        .session-tabs-container::-webkit-scrollbar-thumb:hover {
            background-color: rgba(59, 130, 246, 0.5);
        }

        .session-tabs-nav {
            display: flex;
            flex-wrap: nowrap;
            min-width: max-content;
            padding: 0 1rem;
        }

        .session-tab {
            flex-shrink: 0;
            white-space: nowrap;
        }

        /* Scroll hint indicators */
        .scroll-hint-left,
        .scroll-hint-right {
            position: absolute;
            top: 0;
            bottom: 0;
            width: 20px;
            background: linear-gradient(to right, rgba(249, 250, 251, 0.9), transparent);
            pointer-events: none;
            opacity: 0;
            transition: opacity 0.3s ease;
            z-index: 10;
        }

        .scroll-hint-right {
            right: 0;
            background: linear-gradient(to left, rgba(249, 250, 251, 0.9), transparent);
        }

        .session-tabs-container.has-scroll-left .scroll-hint-left {
            opacity: 1;
        }

        .session-tabs-container.has-scroll-right .scroll-hint-right {
            opacity: 1;
        }

        /* Floating notification styles */
        .floating-notification {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
            min-width: 300px;
            max-width: 500px;
            padding: 16px 20px;
            border-radius: 8px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1), 0 4px 10px rgba(0, 0, 0, 0.05);
            animation: slideInRight 0.3s ease-out;
            font-weight: 500;
            font-size: 14px;
            line-height: 1.4;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .floating-notification.success {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
            border-color: rgba(16, 185, 129, 0.3);
        }

        .floating-notification.error {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
            border-color: rgba(239, 68, 68, 0.3);
        }

        .floating-notification.fade-out {
            animation: fadeOut 0.5s ease-out forwards;
        }

        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes fadeOut {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(100%);
                opacity: 0;
            }
        }

        .notification-icon {
            margin-right: 12px;
            font-size: 16px;
        }

        .notification-close {
            position: absolute;
            top: 8px;
            right: 8px;
            background: none;
            border: none;
            color: rgba(255, 255, 255, 0.8);
            cursor: pointer;
            padding: 4px;
            border-radius: 4px;
            transition: all 0.2s ease;
            font-size: 12px;
        }

        .notification-close:hover {
            color: white;
            background: rgba(255, 255, 255, 0.1);
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

            .floating-notification {
                left: 20px;
                right: 20px;
                min-width: auto;
                max-width: none;
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
            <!-- Top Bar -->
            <div class="bg-white shadow-md p-4 flex justify-between items-center border-b border-gray-100">
                <div class="flex items-center space-x-4">
                    <button id="mobileMenuButton" class="text-gray-500 hover:text-gray-700 md:hidden transition-colors">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    <h2 class="text-xl font-bold text-gray-800 bg-gradient-to-r from-blue-500 to-blue-600 bg-clip-text text-transparent">
                        Attendance Management
                    </h2>
                </div>
                <div class="flex items-center space-x-6">
                    <div class="border-l h-8 border-gray-200"></div>
                    <div class="relative group">
                        <button id="adminDropdown" class="flex items-center focus:outline-none space-x-3 bg-gradient-to-r from-blue-500 to-purple-600 text-white px-4 py-2 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105">
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

            <!-- Attendance Content -->
            <div class="p-6">


                <!-- Download Attendance Template Button -->
                <form method="GET" action="{{ route('instructor.attendance.template.download') }}" class="mb-3 inline-block">
                    <input type="hidden" name="session" value="{{ $currentSession }}">
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-download mr-2"></i>Download Attendance Template
                    </button>
                </form>

                <!-- Upload Attendance Template Form -->
                <form id="uploadAttendanceForm" enctype="multipart/form-data" class="mb-3 inline-block ml-4">
                    @csrf
                    <input type="hidden" name="session_number" value="{{ $currentSession }}">
                    <div class="flex items-center space-x-2">
                        <input type="file" name="attendance_file" id="attendance_file" class="hidden" accept=".xlsx,.xls" required>
                        <label for="attendance_file" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 cursor-pointer">
                            <i class="fas fa-upload mr-2"></i>Choose File
                        </label>
                        <button type="button" id="uploadAttendanceBtn" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            <i class="fas fa-file-upload mr-2"></i>Upload Attendance
                        </button>
                        <span id="file-name" class="text-sm text-gray-500"></span>
                    </div>
                </form>

                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <!-- Program Tabs -->
                    <div class="border-b border-gray-200">
                        @if($programs->count() > 0)
                        <nav class="flex -mb-px">
                            @foreach($programs as $index => $program)
                            <button class="program-tab py-4 px-6 text-center border-b-2 font-medium text-sm
                                {{ $index === 0 ? 'active-tab border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}" data-program-id="{{ $program->id }}" data-duration="{{ $program->duration }}">
                                {{ $program->name }}
                            </button>
                            @endforeach
                        </nav>
                        @else
                        <div class="p-6 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <i class="fas fa-graduation-cap text-4xl text-gray-300 mb-4"></i>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">No Programs Assigned</h3>
                                <p class="text-sm text-gray-500">You are currently not assigned to any programs. Please contact your administrator to assign programs.</p>
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- Time Selection -->
                    <div id="timeSelectionContainer" class="border-b border-gray-200 bg-blue-50 px-6 py-4 hidden">
                        <div class="flex items-center space-x-4">
                            <div class="flex items-center">
                                <i class="fas fa-clock text-blue-600 mr-2"></i>
                                <span class="text-sm font-medium text-gray-700">Select Time Slot:</span>
                            </div>
                            <select id="timeSlotSelect" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white">
                                <option value="">Choose a time slot...</option>
                            </select>
                            <div id="selectedTimeInfo" class="text-sm text-gray-600 hidden">
                                <span class="font-medium">Selected:</span>
                                <span id="selectedTimeText"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Session Tabs -->
                    <div class="border-b border-gray-200 bg-gray-50">
                        <div class="session-tabs-container">
                            <nav class="session-tabs-nav" id="sessionTabs">
                                <!-- Session tabs will be generated by JavaScript -->
                            </nav>
                        </div>
                    </div>

                    <!-- Attendance Table -->
                    <form id="attendanceForm" action="{{ route('instructor.attendance.save') }}" method="POST">
                        @csrf
                        <input type="hidden" name="session_number" id="currentSession" value="{{ $currentSession }}">
                        <input type="hidden" name="selected_time_slot" id="selectedTimeSlot" value="">
                        <input type="hidden" name="schedule_id" id="selectedScheduleId" value="">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Student Name
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Program
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Reference Number
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            OR Number
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Amount Paid
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Payment Status
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Session Eligibility
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Mark as Present
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @php
                                        $hasEligibleStudents = false;
                                    @endphp
                                    @foreach($enrolledStudents as $student)
                                    @php
                                        // Get the student's enrolled enrollment
                                        $enrollment = $student->enrollments->firstWhere('status', 'enrolled');
                                        $eligibility = $student->session_eligibility;
                                        if ($enrollment && $eligibility['eligible']) {
                                            $hasEligibleStudents = true;
                                        }
                                    @endphp
                                    @if($enrollment && $eligibility['eligible'])
                                    <tr class="hover:bg-gray-50" data-program-id="{{ $enrollment->program_id }}">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10 rounded-full overflow-hidden">
                                                    @if($enrollment->photo)
                                                    <img src="{{ asset('storage/' . $enrollment->photo) }}" class="h-10 w-10 object-cover rounded-full" />
                                                    @elseif($enrollment->user && $enrollment->user->photo)
                                                    <img src="{{ asset('storage/' . $enrollment->user->photo) }}" class="h-10 w-10 object-cover rounded-full" />
                                                    @else
                                                    <div class="h-10 w-10 bg-blue-100 flex items-center justify-center rounded-full">
                                                        <i class="fas fa-user text-blue-600"></i>
                                                    </div>
                                                    @endif
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">{{ $student->name }}</div>
                                                    <div class="text-sm text-gray-500">{{ $student->email }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $enrollment->program->name ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center space-x-2">
                                                @php
                                                    $referenceNumber = null;
                                                    $referenceType = null;

                                                    // Find payment with payment_type = 'sessions'
                                                    $sessionsPayment = $student->payments->where('payment_type', 'sessions')->first();

                                                    if ($sessionsPayment && $sessionsPayment->transaction_id && $currentSession <= $eligibility['paid_sessions']) {
                                                        $referenceNumber = $sessionsPayment->transaction_id;
                                                        $referenceType = 'Transaction ID (Sessions)';
                                                    }
                                                @endphp
                                                <div class="flex flex-col">
                                                    <span class="text-sm text-gray-900">{{ $referenceNumber ?? 'Auto-generated on payment' }}</span>
                                                    @if($referenceNumber)
                                                        <span class="text-xs text-gray-500">{{ $referenceType }}</span>
                                                    @else
                                                        <span class="text-xs text-gray-400 italic">Auto-generated on payment</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center space-x-2">
                                                @php
                                                    $orNumber = $enrollment->or_number;
                                                    $hasReferenceNumber = $sessionsPayment && $sessionsPayment->transaction_id && $currentSession <= $eligibility['paid_sessions'];
                                                @endphp
                                                <div class="flex flex-col">
                                                    <span id="or-number-{{ $enrollment->id }}" class="text-sm text-gray-900">{{ $orNumber ?? 'Not set' }}</span>
                                                    @if($orNumber)
                                                        <span class="text-xs text-gray-500">OR Number</span>
                                                    @endif
                                                </div>
                                                @if(!$hasReferenceNumber)
                                                <button type="button" onclick="editOrNumber({{ $enrollment->id }}, '{{ $orNumber ?? '' }}')" class="text-blue-600 hover:text-blue-800 text-xs">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                $amountPaid = null;
                                                $isEditable = false;
                                                
                                                // First check if there's an existing attendance record with amount_paid
                                                $existingAttendance = \App\Models\Attendance::where('enrollment_id', $enrollment->id)
                                                    ->where('session_number', $currentSession)
                                                    ->first();
                                                
                                                if ($existingAttendance) {
                                                    // Use saved amount from database if available, otherwise use 0
                                                    $amountPaid = $existingAttendance->amount_paid ?? 0;
                                                    $isEditable = true;
                                                } else {
                                                    // Check if student has online payment for this session
                                                    $sessionsPayment = $student->payments->where('payment_type', 'sessions')->first();
                                                    if ($sessionsPayment && $sessionsPayment->transaction_id && $currentSession <= $eligibility['paid_sessions']) {
                                                        // Online payment - show individual session amount
                                                        $program = $enrollment->program;
                                                        $amountPaid = $program ? $program->price_per_session : 500;
                                                    } elseif ($orNumber) {
                                                        // Onsite payment with OR number - make editable
                                                        $isEditable = true;
                                                        $amountPaid = 0; // Default value, will be editable
                                                    }
                                                }
                                            @endphp
                                            
                                            @if($isEditable)
                                                <div class="flex items-center">
                                                    <span class="text-sm text-gray-600 mr-1">₱</span>
                                                    <input type="number" 
                                                           class="w-24 px-2 py-1 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" 
                                                           value="{{ $amountPaid }}" 
                                                           min="0" 
                                                           step="0.01"
                                                           data-student-id="{{ $student->id }}"
                                                           data-session="{{ $currentSession }}"
                                                           onchange="updateAmountPaid(this)">
                                                </div>
                                            @elseif($amountPaid)
                                                <span class="text-sm font-medium text-gray-900">₱{{ number_format($amountPaid, 2) }}</span>
                                            @else
                                                <span class="text-sm text-gray-400">-</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($eligibility['payment_status'] === 'paid')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Paid</span>
                                            @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Payment Pending</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            Session {{ $currentSession }} of {{ $eligibility['paid_sessions'] }} paid
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            @php
                                                // Get reference number from session payment only (exclude registration fees)
                                                $referenceNumber = null;
                                                $sessionsPayment = $student->payments->where('payment_type', 'session')->first();
                                                if ($sessionsPayment && $sessionsPayment->transaction_id && $currentSession <= $eligibility['paid_sessions']) {
                                                    $referenceNumber = $sessionsPayment->transaction_id;
                                                }
                                                
                                                // For manual OR numbers, we need to check if the student has paid sessions available
                                                // If they have an OR number and paid_sessions > 0, they can mark attendance
                                                $hasManualPayment = $orNumber && $eligibility['paid_sessions'] > 0;
                                                
                                                // Same logic as admin - check payment verification OR manual OR number with paid sessions
                                                $canMarkAttendance = ($referenceNumber || $hasManualPayment) && $amountPaid && $amountPaid > 0;
                                                
                                                // Debug: Log the values
                                                \Log::info("Student {$student->id}: canMarkAttendance = " . ($canMarkAttendance ? 'true' : 'false'));
                                                \Log::info("Student {$student->id}: referenceNumber = " . ($referenceNumber ?? 'null'));
                                                \Log::info("Student {$student->id}: orNumber = " . ($orNumber ?? 'null'));
                                                \Log::info("Student {$student->id}: amountPaid = " . ($amountPaid ?? 'null'));
                                            @endphp
                                            @if($canMarkAttendance)
                                                <input type="checkbox" class="form-checkbox h-5 w-5 text-blue-600" name="attendance[]" value="{{ $student->id }}">
                                                @if($referenceNumber)
                                                <span class="text-xs text-gray-400 ml-2">Online payment verified</span>
                                                @else
                                                <span class="text-xs text-gray-400 ml-2">Manual payment verified</span>
                                                @endif
                                            @else
                                                <input type="checkbox" class="form-checkbox h-5 w-5 text-gray-400 opacity-50 cursor-not-allowed" name="attendance[]" value="{{ $student->id }}" disabled>
                                                @if(!$referenceNumber && !$orNumber)
                                                    <span class="text-xs text-gray-400 ml-2">Payment verification required</span>
                                                @elseif(!$amountPaid || $amountPaid <= 0)
                                                    <span class="text-xs text-gray-400 ml-2">Amount paid required</span>
                                                @else
                                                    <span class="text-xs text-gray-400 ml-2">Payment verification required</span>
                                                @endif
                                            @endif
                                        </td>
                                    </tr>
                                    @endif
                                    @endforeach

                                    @if(!$hasEligibleStudents)
                                    <tr>
                                        <td colspan="8" class="px-6 py-8 text-center text-gray-500">
                                            <div class="flex flex-col items-center justify-center">
                                                <i class="fas fa-users-slash text-4xl text-gray-300 mb-4"></i>
                                                <p class="text-lg font-medium text-gray-400">No students available for attendance</p>
                                                <p class="text-sm text-gray-500 mt-1">Students may not be enrolled, eligible, or have provided OR numbers for this session.</p>
                                            </div>
                                        </td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>

                        <!-- Save Button -->
                        <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
                            <div id="dateDisplay" class="text-sm text-gray-500 hidden">
                                Today's Date: <span id="currentDate"></span>
                            </div>
                            <button type="submit" id="saveAttendanceBtn" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 hidden">
                                Save Attendance
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Today's Sessions Section -->
                @if($todaysSessions->count() > 0)
                <div class="bg-white rounded-lg shadow mt-6">
                    <div class="p-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-800">Today's Sessions</h3>
                    </div>
                    <div class="p-4">
                        <div class="space-y-4">
                            @foreach($todaysSessions as $session)
                            <div class="border border-gray-200 rounded-lg p-4">
                                <div class="flex justify-between items-center mb-4">
                                    <div>
                                        <h4 class="text-lg font-medium text-gray-900">{{ $session->program->name ?? 'N/A' }}</h4>
                                        <p class="text-sm text-gray-600">{{ $session->session_name ?? 'Session' }}</p>
                                        <p class="text-sm text-gray-500">{{ $session->day ?? 'N/A' }} at {{ $session->start_time ?? 'N/A' }} - {{ $session->end_time ?? 'N/A' }}</p>
                                    </div>
                                    <button class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                                        <i class="fas fa-calendar-check mr-2"></i>Mark Attendance
                                    </button>
                                </div>

                                <!-- Students for this session -->
                                <div class="mt-4">
                                    <h5 class="text-sm font-medium text-gray-700 mb-2">Enrolled Students</h5>
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                        @php
                                            $enrollments = \App\Models\Enrollment::where('program_id', $session->program_id)
                                                ->where('status', \App\Models\Enrollment::STATUS_ENROLLED)
                                                ->with('user')
                                                ->get();
                                        @endphp

                                        @forelse($enrollments as $enrollment)
                                        <div class="flex items-center justify-between bg-gray-50 p-3 rounded-md">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-8 w-8 bg-blue-100 rounded-full flex items-center justify-center">
                                                    @if($enrollment->user && $enrollment->user->photo)
                                                    <img src="{{ asset('storage/' . $enrollment->user->photo) }}" alt="Photo" class="w-8 h-8 rounded-full object-cover">
                                                    @else
                                                    <i class="fas fa-user text-blue-600 text-xs"></i>
                                                    @endif
                                                </div>
                                                <div class="ml-3">
                                                    <p class="text-sm font-medium text-gray-900">{{ $enrollment->full_name }}</p>
                                                </div>
                                            </div>
                                            <div class="flex space-x-2">
                                                <button class="text-green-600 hover:text-green-800" title="Mark Present">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                                <button class="text-red-600 hover:text-red-800" title="Mark Absent">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </div>
                                        @empty
                                        <p class="text-gray-500 text-sm">No students enrolled in this program.</p>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Success/Error Modal -->
    <div id="uploadModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-center mb-4">
                    <div id="modalIcon" class="text-4xl"></div>
                </div>
                <h3 id="modalTitle" class="text-lg font-medium text-gray-900 text-center mb-2"></h3>
                <p id="modalMessage" class="text-sm text-gray-500 text-center mb-4"></p>
                <div class="flex justify-center">
                    <button id="modalCloseBtn" class="px-4 py-2 bg-blue-500 text-white text-base font-medium rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-300">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Profile Modal -->
    @include('Instructor.partials.profile-modal')

    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
        @csrf
    </form>

    @php
        // Prepare student data for JavaScript with session eligibility
        // Include ALL students regardless of enrollment status for accurate session counting
        $studentData = [];
        foreach($enrolledStudents as $student) {
            // Get the student's enrolled enrollment
            $enrollment = $student->enrollments->firstWhere('status', 'enrolled');
            if ($enrollment) {
                // Use the calculated student session from the controller
                $eligibility = $student->session_eligibility;
                $studentSession = $eligibility['student_session'] ?? 1;

                // Get attendance records for this student
                $attendanceRecords = [];
                if ($enrollment) {
                    $attendances = \App\Models\Attendance::where('enrollment_id', $enrollment->id)
                        ->where('status', 'present')
                        ->get();
                    foreach ($attendances as $attendance) {
                        $attendanceRecords[$attendance->session_number] = true;
                    }
                }

                $studentData[] = [
                    'program_id' => $enrollment->program_id,
                    'student_session' => $studentSession,
                    'paid_sessions' => $student->session_eligibility['paid_sessions'] ?? 0,
                    'or_number' => $enrollment->or_number,
                    'eligible' => $eligibility['eligible'],
                    'attendance_records' => $attendanceRecords
                ];
            }
        }

        // Debug: Log student data
        \Log::info('Attendance Student Data', [
            'total_enrolled_students' => $enrolledStudents->count(),
            'student_data_count' => count($studentData),
            'current_session' => $currentSession,
            'student_data' => $studentData
        ]);
        
        // Debug: Log each student's session assignment for verification
        foreach($studentData as $index => $student) {
            \Log::info("Student {$index}: Program {$student['program_id']}, student_session: {$student['student_session']}, paid_sessions: {$student['paid_sessions']}, eligible: " . ($student['eligible'] ? 'true' : 'false') . ", or_number: " . ($student['or_number'] ?? 'null'));
        }
    @endphp

    <script>
        // Load time slots for a specific program
        function loadTimeSlotsForProgram(programId) {
            const timeSlotSelect = document.getElementById('timeSlotSelect');
            const timeSelectionContainer = document.getElementById('timeSelectionContainer');
            const selectedTimeInfo = document.getElementById('selectedTimeInfo');
            
            // Clear existing options
            timeSlotSelect.innerHTML = '<option value="">Choose a time slot...</option>';
            selectedTimeInfo.classList.add('hidden');
            
            // Show time selection container
            timeSelectionContainer.classList.remove('hidden');
            
            // Fetch schedules for the program
            fetch(`/instructor/schedules/program/${programId}`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success && data.schedules && data.schedules.length > 0) {
                        // Add time slot options
                        data.schedules.forEach(schedule => {
                            const startTime = new Date(`2000-01-01T${schedule.start_time}`).toLocaleTimeString('en-US', {
                                hour: 'numeric',
                                minute: '2-digit',
                                hour12: true
                            });
                            const endTime = new Date(`2000-01-01T${schedule.end_time}`).toLocaleTimeString('en-US', {
                                hour: 'numeric',
                                minute: '2-digit',
                                hour12: true
                            });
                            
                            const option = document.createElement('option');
                            option.value = `${schedule.start_time}-${schedule.end_time}`;
                            option.textContent = `${startTime} - ${endTime} (${schedule.day})`;
                            option.dataset.scheduleId = schedule.id;
                            timeSlotSelect.appendChild(option);
                        });
                    } else {
                        // No schedules found, hide time selection
                        timeSelectionContainer.classList.add('hidden');
                    }
                })
                .catch(error => {
                    console.error('Error loading time slots:', error);
                    timeSelectionContainer.classList.add('hidden');
                });
        }

        // Handle time slot selection
        function handleTimeSlotSelection() {
            const timeSlotSelect = document.getElementById('timeSlotSelect');
            const selectedTimeInfo = document.getElementById('selectedTimeInfo');
            const selectedTimeText = document.getElementById('selectedTimeText');
            
            timeSlotSelect.addEventListener('change', function() {
                if (this.value) {
                    selectedTimeText.textContent = this.options[this.selectedIndex].textContent;
                    selectedTimeInfo.classList.remove('hidden');
                    
                    // Store selected time slot
                    localStorage.setItem('selectedTimeSlot', this.value);
                    localStorage.setItem('selectedScheduleId', this.options[this.selectedIndex].dataset.scheduleId);
                    
                    // Update hidden form fields
                    document.getElementById('selectedTimeSlot').value = this.value;
                    document.getElementById('selectedScheduleId').value = this.options[this.selectedIndex].dataset.scheduleId;
                } else {
                    selectedTimeInfo.classList.add('hidden');
                    localStorage.removeItem('selectedTimeSlot');
                    localStorage.removeItem('selectedScheduleId');
                    
                    // Clear hidden form fields
                    document.getElementById('selectedTimeSlot').value = '';
                    document.getElementById('selectedScheduleId').value = '';
                }
            });
        }

        // Student data for calculating counts per program
        const studentData = @json($studentData ?? []);
        console.log('Student data from PHP:', studentData);

        // Function to calculate student counts for a specific program
        function calculateStudentCountsForProgram(programId, maxSessions) {
            const counts = {};
            for (let i = 1; i <= maxSessions; i++) {
                counts[i] = 0;
            }

            // Count students who are NOT YET marked as present in each session
            // This counts students who are assigned to each session but haven't been marked present yet
            studentData.forEach(student => {
                if (student.program_id == programId) { // Use == for type coercion
                    const studentSession = student.student_session;
                    const attendanceRecords = student.attendance_records || {};
                    
                    // Count students who are assigned to each session but haven't been marked present
                    // We need to check each session to see if the student should be counted there
                    
                    for (let session = 1; session <= maxSessions; session++) {
                        // Check if this student should be counted in this session
                        let shouldCountInSession = false;
                        
                        if (session === 1) {
                            // For Session 1: count students who haven't been marked present in Session 1
                            shouldCountInSession = !attendanceRecords[session];
                        } else {
                            // For Session N: count students who attended Session N-1 but haven't been marked present in Session N
                            const attendedPreviousSession = attendanceRecords[session - 1];
                            const notMarkedPresentInCurrentSession = !attendanceRecords[session];
                            shouldCountInSession = attendedPreviousSession && notMarkedPresentInCurrentSession;
                        }
                        
                        if (shouldCountInSession) {
                            counts[session]++;
                        }
                    }
                }
            });

            // The counts object keys are session numbers, values are counts
            // To ensure correct order, create a new object with keys sorted ascending
            const sortedCounts = {};
            Object.keys(counts).sort((a, b) => a - b).forEach(key => {
                sortedCounts[key] = counts[key];
            });

            console.log('Student data:', studentData);
            console.log('Program ID:', programId);
            console.log('Calculated counts (students NOT YET marked present in each session):', sortedCounts);
            
            // Debug: Log each student's session assignment and attendance
            studentData.forEach((student, index) => {
                if (student.program_id == programId) {
                    const attendanceRecords = student.attendance_records || {};
                    const attendedSessions = Object.keys(attendanceRecords).filter(session => attendanceRecords[session]).join(', ');
                    console.log(`Student ${index + 1}: Program ${student.program_id}, Assigned to Session ${student.student_session}, Paid Sessions: ${student.paid_sessions}, Eligible: ${student.eligible}, OR Number: ${student.or_number}, Attended Sessions: [${attendedSessions}]`);
                }
            });
            
            // Debug: Show the actual counts being calculated
            console.log('Raw counts before sorting:', counts);
            console.log('Final sorted counts:', sortedCounts);

            return sortedCounts;
        }

        // Function to generate session tabs and highlight current session
        function generateSessionTabs(sessionCount, currentSession, programId) {
            console.log('generateSessionTabs called with:', { sessionCount, currentSession, programId });
            const sessionTabs = document.getElementById('sessionTabs');
            const studentCounts = calculateStudentCountsForProgram(programId, sessionCount);
            console.log('Student counts for program', programId, ':', studentCounts);
            sessionTabs.innerHTML = '';
            for (let i = 1; i <= sessionCount; i++) {
                const isActive = (i === currentSession);
                const studentCount = studentCounts[i] || 0;
                const sessionText = `Session ${i} (${studentCount})`;
                sessionTabs.innerHTML += `<button class="session-tab ${isActive ? 'active-tab border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'} py-3 px-4 text-center border-b-2 font-medium text-xs" data-session="${i}">${sessionText}</button>`;
            }
        }

        // Function to handle session tab clicks
        function handleSessionTabClick(e) {
            if (e.target.classList.contains('session-tab')) {
                // Remove active class from all session tabs
                document.querySelectorAll('.session-tab').forEach(t => {
                    t.classList.remove('active-tab', 'border-blue-500', 'text-blue-600');
                    t.classList.add('border-transparent', 'text-gray-500');
                });

                // Add active class to clicked tab
                e.target.classList.add('active-tab', 'border-blue-500', 'text-blue-600');
                e.target.classList.remove('border-transparent', 'text-gray-500');

                // Update current session number and reload page
                const sessionNumber = e.target.getAttribute('data-session') || e.target.textContent.match(/\d+/)[0];
                document.getElementById('currentSession').value = sessionNumber;

                // Reload page with session parameter
                const baseUrl = window.location.origin + '/instructor/attendance';
                window.location.href = baseUrl + '?session=' + sessionNumber;
            }
        }

        // Tab switching functionality for Program Tabs
        document.querySelectorAll('.program-tab').forEach(tab => {
            tab.addEventListener('click', function() {
                // Remove active class from all program tabs
                document.querySelectorAll('.program-tab').forEach(t => {
                    t.classList.remove('active-tab', 'border-blue-500', 'text-blue-600');
                    t.classList.add('border-transparent', 'text-gray-500');
                });

                // Add active class to clicked tab
                this.classList.add('active-tab', 'border-blue-500', 'text-blue-600');
                this.classList.remove('border-transparent', 'text-gray-500');

                // Get selected program ID and duration
                const selectedProgramId = this.getAttribute('data-program-id');
                let sessionCount = 1; // Default fallback
                const durationString = this.getAttribute('data-duration');

                console.log('Program:', selectedProgramId, 'Duration string:', durationString);

                if (durationString && !isNaN(durationString)) {
                    sessionCount = parseInt(durationString);
                } else if (durationString) {
                    // Try to extract number from string like "10 weeks" or "2 months"
                    const matches = durationString.match(/(\d+)/);
                    if (matches && matches[1]) {
                        sessionCount = parseInt(matches[1]);
                    }
                }
                sessionCount = Math.max(1, sessionCount);

                console.log('Calculated session count:', sessionCount);

                // Store selected program in localStorage
                localStorage.setItem('selectedProgramId', selectedProgramId);

                // Load time slots for the selected program
                loadTimeSlotsForProgram(selectedProgramId);
                
                // Restore selected time slot from localStorage if available
                const storedTimeSlot = localStorage.getItem('selectedTimeSlot');
                const storedScheduleId = localStorage.getItem('selectedScheduleId');
                if (storedTimeSlot && storedScheduleId) {
                    // Wait a bit for the time slots to load, then restore selection
                    setTimeout(() => {
                        const timeSlotSelect = document.getElementById('timeSlotSelect');
                        if (timeSlotSelect) {
                            timeSlotSelect.value = storedTimeSlot;
                            // Also restore the schedule_id
                            document.getElementById('selectedScheduleId').value = storedScheduleId;
                            // Trigger change event to update UI
                            timeSlotSelect.dispatchEvent(new Event('change'));
                        }
                    }, 500);
                }

                // Show/hide table rows based on selected program
                document.querySelectorAll('tr[data-program-id]').forEach(row => {
                    if (row.getAttribute('data-program-id') === selectedProgramId) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });

                // Get current session from URL parameters
                const urlParams = new URLSearchParams(window.location.search);
                let currentSession = parseInt(urlParams.get('session')) || 1;

                // If current session exceeds sessionCount, reset to 1
                if (currentSession > sessionCount) currentSession = 1;

                // Generate session tabs with current session highlighted
                generateSessionTabs(sessionCount, currentSession, selectedProgramId);
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

        // On initial page load
        window.addEventListener('DOMContentLoaded', function() {
            // Initialize time slot selection handler
            handleTimeSlotSelection();
            
            // Show flash messages as notifications
            @if(session('success'))
                showNotification('{{ session('success') }}', 'success');
            @endif

            @if(session('error'))
                showNotification('{{ session('error') }}', 'error');
            @endif

            // Handle file selection display
            const fileInput = document.getElementById('attendance_file');
            const fileNameSpan = document.getElementById('file-name');

            if (fileInput && fileNameSpan) {
                fileInput.addEventListener('change', function(e) {
                    if (e.target.files.length > 0) {
                        fileNameSpan.textContent = e.target.files[0].name;
                        fileNameSpan.classList.remove('text-gray-500');
                        fileNameSpan.classList.add('text-blue-600');
                    } else {
                        fileNameSpan.textContent = '';
                        fileNameSpan.classList.remove('text-blue-600');
                        fileNameSpan.classList.add('text-gray-500');
                    }
                });
            }

            // Get current session from URL or default to 1
            const urlParams = new URLSearchParams(window.location.search);
            let currentSession = parseInt(urlParams.get('session')) || 1;

            // Get stored program ID or default to first
            const storedProgramId = localStorage.getItem('selectedProgramId');
            let selectedTab = document.querySelector('.program-tab');
            if (storedProgramId) {
                const storedTab = document.querySelector(`.program-tab[data-program-id="${storedProgramId}"]`);
                if (storedTab) {
                    selectedTab = storedTab;
                }
            }

            // Initialize the selected program tab and generate session tabs
            if (selectedTab) {
                // Set the selected tab as active
                document.querySelectorAll('.program-tab').forEach(t => {
                    t.classList.remove('active-tab', 'border-blue-500', 'text-blue-600');
                    t.classList.add('border-transparent', 'text-gray-500');
                });
                selectedTab.classList.add('active-tab', 'border-blue-500', 'text-blue-600');
                selectedTab.classList.remove('border-transparent', 'text-gray-500');

                // Calculate session count and generate session tabs
                const selectedProgramId = selectedTab.getAttribute('data-program-id');
                let sessionCount = 1; // Default fallback
                const durationString = selectedTab.getAttribute('data-duration');

                if (durationString && !isNaN(durationString)) {
                    sessionCount = parseInt(durationString);
                } else if (durationString) {
                    // Try to extract number from string like "10 weeks" or "2 months"
                    const matches = durationString.match(/(\d+)/);
                    if (matches && matches[1]) {
                        sessionCount = parseInt(matches[1]);
                    }
                }
                sessionCount = Math.max(1, sessionCount);

                // Store selected program in localStorage
                localStorage.setItem('selectedProgramId', selectedProgramId);

                // Load time slots for the selected program
                loadTimeSlotsForProgram(selectedProgramId);
                
                // Restore selected time slot from localStorage if available
                const storedTimeSlot = localStorage.getItem('selectedTimeSlot');
                const storedScheduleId = localStorage.getItem('selectedScheduleId');
                if (storedTimeSlot && storedScheduleId) {
                    // Wait a bit for the time slots to load, then restore selection
                    setTimeout(() => {
                        const timeSlotSelect = document.getElementById('timeSlotSelect');
                        if (timeSlotSelect) {
                            timeSlotSelect.value = storedTimeSlot;
                            // Also restore the schedule_id
                            document.getElementById('selectedScheduleId').value = storedScheduleId;
                            // Trigger change event to update UI
                            timeSlotSelect.dispatchEvent(new Event('change'));
                        }
                    }, 500);
                }

                // Show/hide table rows based on selected program
                document.querySelectorAll('tr[data-program-id]').forEach(row => {
                    if (row.getAttribute('data-program-id') === selectedProgramId) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });

                // If current session exceeds sessionCount, reset to 1
                if (currentSession > sessionCount) currentSession = 1;

                // Generate session tabs with current session highlighted
                generateSessionTabs(sessionCount, currentSession, selectedProgramId);
            }

            // Add session tab click event listener
            const sessionTabs = document.getElementById('sessionTabs');
            sessionTabs.addEventListener('click', handleSessionTabClick);

            // Add checkbox change event listeners
            document.querySelectorAll('input[name="attendance[]"]').forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const dateDisplay = document.getElementById('dateDisplay');
                    const currentDateSpan = document.getElementById('currentDate');
                    const saveBtn = document.getElementById('saveAttendanceBtn');

                    const anyChecked = Array.from(document.querySelectorAll('input[name="attendance[]"]')).some(cb => cb.checked);

                    if (anyChecked) {
                        // Show today's date and save button
                        const today = new Date();
                        const options = {
                            year: 'numeric',
                            month: 'long',
                            day: 'numeric'
                        };
                        currentDateSpan.textContent = today.toLocaleDateString(undefined, options);
                        dateDisplay.classList.remove('hidden');
                        saveBtn.classList.remove('hidden');
                    } else {
                        // Hide date display and save button if no checkboxes are checked
                        dateDisplay.classList.add('hidden');
                        saveBtn.classList.add('hidden');
                    }
                });
            });

            // Handle attendance form submission
            document.getElementById('attendanceForm').addEventListener('submit', function(e) {
                console.log('Form submission started');

                // Check if time slot is selected
                const selectedTimeSlot = document.getElementById('selectedTimeSlot').value;
                const selectedScheduleId = document.getElementById('selectedScheduleId').value;
                const timeSlotSelect = document.getElementById('timeSlotSelect');
                
                console.log('Selected time slot:', selectedTimeSlot);
                console.log('Selected schedule ID:', selectedScheduleId);
                
                if (!selectedTimeSlot || selectedTimeSlot === '') {
                    e.preventDefault();
                    alert('Please select a time slot before marking attendance.');
                    timeSlotSelect.focus();
                    return false;
                }
                
                if (!selectedScheduleId || selectedScheduleId === '') {
                    e.preventDefault();
                    alert('Please select a time slot before marking attendance. (Schedule ID missing)');
                    timeSlotSelect.focus();
                    return false;
                }

                // Get all checked attendance checkboxes
                const checkedBoxes = document.querySelectorAll('input[name="attendance[]"]:checked');
                console.log('Checked attendance boxes:', checkedBoxes.length);
                
                // Debug: Log all checkboxes and their values
                const allCheckboxes = document.querySelectorAll('input[name="attendance[]"]');
                console.log('Total checkboxes found:', allCheckboxes.length);
                allCheckboxes.forEach((checkbox, index) => {
                    console.log(`Checkbox ${index}: value=${checkbox.value}, checked=${checkbox.checked}, disabled=${checkbox.disabled}`);
                });

                if (checkedBoxes.length === 0) {
                    e.preventDefault();
                    alert('Please select at least one student to mark as present.');
                    return false;
                }

                // Show loading state
                const submitBtn = document.getElementById('saveAttendanceBtn');
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Saving...';
                }

                // Let the form submit normally - the checkboxes will be sent automatically
                console.log('Form submitting normally');
                console.log('Form action:', this.action);
                console.log('Form method:', this.method);
                
                // Debug: Log form data
                const formData = new FormData(this);
                console.log('Form data:');
                for (let [key, value] of formData.entries()) {
                    console.log(`${key}: ${value}`);
                }
            });

            // Handle upload attendance button click
            document.getElementById('uploadAttendanceBtn').addEventListener('click', function(e) {
                e.preventDefault();

                const fileInput = document.getElementById('attendance_file');
                const file = fileInput.files[0];

                if (!file) {
                    showModal('Error', 'Please select a file to upload.', 'error');
                    return;
                }

                // Check file extension
                const allowedExtensions = ['.xlsx', '.xls'];
                const fileName = file.name.toLowerCase();
                const isValidExtension = allowedExtensions.some(ext => fileName.endsWith(ext));

                if (!isValidExtension) {
                    showModal('Error', 'Please select a valid Excel file (.xlsx or .xls).', 'error');
                    return;
                }

                // Show loading state
                const uploadBtn = document.getElementById('uploadAttendanceBtn');
                uploadBtn.disabled = true;
                uploadBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Uploading...';

                // Create FormData object
                const formData = new FormData();
                formData.append('attendance_file', file);
                formData.append('session_number', document.getElementById('currentSession').value);
                formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content') || '{{ csrf_token() }}');

                // Send AJAX request
                fetch('/instructor/attendance/template/upload', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showModal('Success', data.message || 'Attendance uploaded successfully!', 'success');
                        // Clear file input
                        fileInput.value = '';
                        document.getElementById('file-name').textContent = '';
                        document.getElementById('file-name').classList.remove('text-blue-600');
                        document.getElementById('file-name').classList.add('text-gray-500');
                        // Reload page after success
                        setTimeout(() => {
                            window.location.reload();
                        }, 2000);
                    } else {
                        showModal('Error', data.message || 'Failed to upload attendance.', 'error');
                    }
                })
                .catch(error => {
                    console.error('Upload error:', error);
                    showModal('Error', 'Network error occurred. Please try again.', 'error');
                })
                .finally(() => {
                    // Reset button state
                    uploadBtn.disabled = false;
                    uploadBtn.innerHTML = '<i class="fas fa-file-upload mr-2"></i>Upload Attendance';
                });
            });
        });

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
                if (toggleIcon && toggleIcon.classList.contains('fa-chevron-left')) {
                    toggleIcon.classList.remove('fa-chevron-left');
                    toggleIcon.classList.add('fa-chevron-right');
                }
            } else {
                sidebar.classList.remove('sidebar-collapsed');
                contentArea.classList.remove('ml-1');

                // Set the correct icon direction
                if (toggleIcon && toggleIcon.classList.contains('fa-chevron-right')) {
                    toggleIcon.classList.remove('fa-chevron-right');
                    toggleIcon.classList.add('fa-chevron-left');
                }
            }
        });

        // Function to edit reference number inline
        function editReferenceNumber(enrollmentId, currentValue, referenceType) {
            const spanElement = document.getElementById('reference-number-' + enrollmentId);
            const originalText = spanElement.textContent;

            // Create input field
            const input = document.createElement('input');
            input.type = 'text';
            input.value = currentValue || '';
            input.className = 'px-2 py-1 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500';
            input.style.width = '120px';

            // Create save button
            const saveBtn = document.createElement('button');
            saveBtn.innerHTML = '<i class="fas fa-check text-green-600"></i>';
            saveBtn.className = 'ml-2 px-2 py-1 text-sm hover:bg-green-50 rounded';
            saveBtn.onclick = function() {
                saveReferenceNumber(enrollmentId, input.value, referenceType);
            };

            // Create cancel button
            const cancelBtn = document.createElement('button');
            cancelBtn.innerHTML = '<i class="fas fa-times text-red-600"></i>';
            cancelBtn.className = 'ml-1 px-2 py-1 text-sm hover:bg-red-50 rounded';
            cancelBtn.onclick = function() {
                spanElement.innerHTML = originalText;
                const editBtn = document.createElement('button');
                editBtn.type = 'button';
                editBtn.onclick = function() { editReferenceNumber(enrollmentId, originalText === 'Not set' ? '' : originalText, referenceType); };
                editBtn.className = 'text-blue-600 hover:text-blue-800 text-xs ml-2';
                editBtn.innerHTML = '<i class="fas fa-edit"></i>';
                spanElement.appendChild(editBtn);
            };

            // Replace span content
            spanElement.innerHTML = '';
            spanElement.appendChild(input);
            spanElement.appendChild(saveBtn);
            spanElement.appendChild(cancelBtn);

            // Focus on input
            input.focus();
            input.select();

            // Handle Enter key
            input.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    saveReferenceNumber(enrollmentId, input.value, referenceType);
                } else if (e.key === 'Escape') {
                    cancelBtn.click();
                }
            });
        }

        // Function to save reference number
        function saveReferenceNumber(enrollmentId, newValue, referenceType) {
            const spanElement = document.getElementById('reference-number-' + enrollmentId);

            // Show loading state
            spanElement.innerHTML = '<i class="fas fa-spinner fa-spin text-blue-600"></i> Saving...';

            // Send AJAX request
            fetch('/admin/enrollments/' + enrollmentId + '/update-or-number', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') || '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    or_number: newValue
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update display
                    const displayText = newValue || 'Not set';
                    spanElement.innerHTML = displayText;

                    // Add edit button back
                    const editBtn = document.createElement('button');
                    editBtn.type = 'button';
                    editBtn.onclick = function() { editReferenceNumber(enrollmentId, newValue, referenceType); };
                    editBtn.className = 'text-blue-600 hover:text-blue-800 text-xs ml-2';
                    editBtn.innerHTML = '<i class="fas fa-edit"></i>';
                    spanElement.appendChild(editBtn);

                    // Show success notification
                    showNotification('Reference number updated successfully!', 'success');

                    // Refresh the page to update student eligibility
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                } else {
                    // Show error
                    spanElement.innerHTML = data.message || 'Error updating reference number';
                    setTimeout(() => {
                        const originalValue = data.original_value || 'Not set';
                        spanElement.innerHTML = originalValue;
                        const editBtn = document.createElement('button');
                        editBtn.type = 'button';
                        editBtn.onclick = function() { editReferenceNumber(enrollmentId, originalValue === 'Not set' ? '' : originalValue, referenceType); };
                        editBtn.className = 'text-blue-600 hover:text-blue-800 text-xs ml-2';
                        editBtn.innerHTML = '<i class="fas fa-edit"></i>';
                        spanElement.appendChild(editBtn);
                    }, 2000);
                    showNotification('Failed to update reference number', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                spanElement.innerHTML = 'Error updating reference number';
                setTimeout(() => {
                    const originalValue = newValue || 'Not set';
                    spanElement.innerHTML = originalValue;
                    const editBtn = document.createElement('button');
                    editBtn.type = 'button';
                    editBtn.onclick = function() { editReferenceNumber(enrollmentId, originalValue === 'Not set' ? '' : originalValue, referenceType); };
                    editBtn.className = 'text-blue-600 hover:text-blue-800 text-xs ml-2';
                    editBtn.innerHTML = '<i class="fas fa-edit"></i>';
                    spanElement.appendChild(editBtn);
                }, 2000);
                showNotification('Network error occurred', 'error');
            });
        }

        // Function to edit OR number inline
        function editOrNumber(enrollmentId, currentValue) {
            const spanElement = document.getElementById('or-number-' + enrollmentId);
            const originalText = spanElement.textContent;

            // Create input field
            const input = document.createElement('input');
            input.type = 'text';
            input.value = currentValue || '';
            input.className = 'px-2 py-1 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500';
            input.style.width = '120px';

            // Create save button
            const saveBtn = document.createElement('button');
            saveBtn.type = 'button';
            saveBtn.innerHTML = '<i class="fas fa-check text-green-600"></i>';
            saveBtn.className = 'ml-2 px-2 py-1 text-sm hover:bg-green-50 rounded';
            saveBtn.onclick = function() {
                saveOrNumber(enrollmentId, input.value);
            };

            // Create cancel button
            const cancelBtn = document.createElement('button');
            cancelBtn.type = 'button';
            cancelBtn.innerHTML = '<i class="fas fa-times text-red-600"></i>';
            cancelBtn.className = 'ml-1 px-2 py-1 text-sm hover:bg-red-50 rounded';
            cancelBtn.onclick = function() {
                spanElement.innerHTML = originalText;
                const editBtn = document.createElement('button');
                editBtn.type = 'button';
                editBtn.onclick = function() { editOrNumber(enrollmentId, originalText === 'Not set' ? '' : originalText); };
                editBtn.className = 'text-blue-600 hover:text-blue-800 text-xs ml-2';
                editBtn.innerHTML = '<i class="fas fa-edit"></i>';
                spanElement.appendChild(editBtn);
            };

            // Replace span content
            spanElement.innerHTML = '';
            spanElement.appendChild(input);
            spanElement.appendChild(saveBtn);
            spanElement.appendChild(cancelBtn);

            // Focus on input
            input.focus();
            input.select();

            // Handle Enter key
            input.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    saveOrNumber(enrollmentId, input.value);
                } else if (e.key === 'Escape') {
                    cancelBtn.click();
                }
            });
        }

        // Function to save OR number
        function saveOrNumber(enrollmentId, newValue) {
            const spanElement = document.getElementById('or-number-' + enrollmentId);

            // Show loading state
            spanElement.innerHTML = '<i class="fas fa-spinner fa-spin text-blue-600"></i> Saving...';

            // Get current session number
            const currentSession = document.getElementById('currentSession').value;

            // Send AJAX request
            fetch('/instructor/enrollments/' + enrollmentId + '/update-or-number', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') || '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    or_number: newValue,
                    session_number: currentSession
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update display
                    const displayText = newValue || 'Not set';
                    spanElement.innerHTML = displayText;

                    // Add edit button back
                    const editBtn = document.createElement('button');
                    editBtn.type = 'button';
                    editBtn.onclick = function() { editOrNumber(enrollmentId, newValue); };
                    editBtn.className = 'text-blue-600 hover:text-blue-800 text-xs ml-2';
                    editBtn.innerHTML = '<i class="fas fa-edit"></i>';
                    spanElement.appendChild(editBtn);

                    // Show success notification
                    showNotification('OR number updated successfully!', 'success');

                    // Refresh the page to update student eligibility
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                } else {
                    // Show error
                    spanElement.innerHTML = data.message || 'Error updating OR number';
                    setTimeout(() => {
                        const originalValue = data.original_value || 'Not set';
                        spanElement.innerHTML = originalValue;
                        const editBtn = document.createElement('button');
                        editBtn.type = 'button';
                        editBtn.onclick = function() { editOrNumber(enrollmentId, originalValue === 'Not set' ? '' : originalValue); };
                        editBtn.className = 'text-blue-600 hover:text-blue-800 text-xs ml-2';
                        editBtn.innerHTML = '<i class="fas fa-edit"></i>';
                        spanElement.appendChild(editBtn);
                    }, 2000);
                    showNotification('Failed to update OR number', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                spanElement.innerHTML = 'Error updating OR number';
                setTimeout(() => {
                    const originalValue = newValue || 'Not set';
                    spanElement.innerHTML = originalValue;
                    const editBtn = document.createElement('button');
                    editBtn.type = 'button';
                    editBtn.onclick = function() { editOrNumber(enrollmentId, originalValue === 'Not set' ? '' : originalValue); };
                    editBtn.className = 'text-blue-600 hover:text-blue-800 text-xs ml-2';
                    editBtn.innerHTML = '<i class="fas fa-edit"></i>';
                    spanElement.appendChild(editBtn);
                }, 2000);
                showNotification('Network error occurred', 'error');
            });
        }

            // Function to update checkbox state based on amount paid
            function updateCheckboxState(studentId, amount) {
                const checkbox = document.querySelector(`input[name="attendance[]"][value="${studentId}"]`);
                if (checkbox) {
                    const amountValue = parseFloat(amount) || 0;
                    const hasPayment = checkbox.closest('tr').querySelector('input[data-student-id]')?.value || 
                                     checkbox.closest('tr').textContent.includes('Online payment verified') ||
                                     checkbox.closest('tr').textContent.includes('Manual payment verified');
                    
                    if (hasPayment && amountValue > 0) {
                        // Enable checkbox
                        checkbox.disabled = false;
                        checkbox.classList.remove('text-gray-400', 'opacity-50', 'cursor-not-allowed');
                        checkbox.classList.add('text-blue-600');
                        
                        // Update status text
                        const statusSpan = checkbox.nextElementSibling;
                        if (statusSpan) {
                            if (checkbox.closest('tr').textContent.includes('Online payment verified')) {
                                statusSpan.textContent = 'Online payment verified';
                            } else {
                                statusSpan.textContent = 'Manual payment verified';
                            }
                        }
                    } else {
                        // Disable checkbox
                        checkbox.disabled = true;
                        checkbox.classList.remove('text-blue-600');
                        checkbox.classList.add('text-gray-400', 'opacity-50', 'cursor-not-allowed');
                        
                        // Update status text
                        const statusSpan = checkbox.nextElementSibling;
                        if (statusSpan) {
                            if (amountValue <= 0) {
                                statusSpan.textContent = 'Amount paid required';
                            } else {
                                statusSpan.textContent = 'Payment verification required';
                            }
                        }
                    }
                }
            }

            // Function to update amount paid for onsite payments
            function updateAmountPaid(inputElement) {
                const studentId = inputElement.getAttribute('data-student-id');
                const session = inputElement.getAttribute('data-session');
                const amount = inputElement.value;
                
                // Update checkbox state based on amount
                updateCheckboxState(studentId, amount);

            // Validate amount
            if (amount < 0) {
                inputElement.value = 0;
                showNotification('Amount cannot be negative', 'error');
                return;
            }

            // Send update to server
            fetch('{{ route("instructor.attendance.update-amount") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    student_id: studentId,
                    session: session,
                    amount: amount
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('Amount updated successfully!', 'success');
                } else {
                    showNotification('Failed to update amount', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Network error occurred', 'error');
            });
        }

        // Function to show modal
        function showModal(title, message, type) {
            const modal = document.getElementById('uploadModal');
            const modalTitle = document.getElementById('modalTitle');
            const modalMessage = document.getElementById('modalMessage');
            const modalIcon = document.getElementById('modalIcon');
            const modalCloseBtn = document.getElementById('modalCloseBtn');

            // Set modal content
            modalTitle.textContent = title;
            modalMessage.textContent = message;

            // Set icon based on type
            if (type === 'success') {
                modalIcon.className = 'text-4xl text-green-600 fas fa-check-circle';
            } else if (type === 'error') {
                modalIcon.className = 'text-4xl text-red-600 fas fa-exclamation-circle';
            } else {
                modalIcon.className = 'text-4xl text-blue-600 fas fa-info-circle';
            }

            // Show modal
            modal.classList.remove('hidden');

            // Close modal when close button is clicked
            modalCloseBtn.onclick = function() {
                modal.classList.add('hidden');
            };

            // Close modal when clicking outside
            modal.onclick = function(e) {
                if (e.target === modal) {
                    modal.classList.add('hidden');
                }
            };
        }

        // Function to show floating notification
        function showNotification(message, type) {
            // Remove existing notifications
            const existingNotifications = document.querySelectorAll('.floating-notification');
            existingNotifications.forEach(notification => notification.remove());

            // Create notification element
            const notification = document.createElement('div');
            notification.className = `floating-notification ${type}`;
            notification.innerHTML = `
                <div class="flex items-center">
                    <i class="notification-icon fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'}"></i>
                    <span>${message}</span>
                    <button class="notification-close" onclick="this.parentElement.parentElement.remove()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;

            // Add to page
            document.body.appendChild(notification);

            // Auto remove after 5 seconds
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.classList.add('fade-out');
                    setTimeout(() => notification.remove(), 500);
                }
            }, 5000);
        }
    </script>
</body>
</html>
