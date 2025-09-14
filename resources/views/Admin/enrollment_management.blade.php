<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Enrollment Management</title>
    <script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.1/build/qrcode.min.js"></script>
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

        /* Modal scrollbar styles - matching navigation scrollbar */
        .modal-scrollbar {
            scrollbar-width: thin;
            scrollbar-color: rgba(255, 255, 255, 0.2) transparent;
        }

        .modal-scrollbar::-webkit-scrollbar {
            width: 6px;
        }

        .modal-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .modal-scrollbar::-webkit-scrollbar-thumb {
            background-color: rgba(255, 255, 255, 0.2);
            border-radius: 3px;
        }

        .modal-scrollbar::-webkit-scrollbar-thumb:hover {
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

        .content-area {
            transition: all 0.3s ease;
            margin-left: 0.5rem;
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
                        Enrollment Management
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
            
<!-- Enrollment Management Content -->
<div class="p-6" id="enrollmentContent">
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="p-6 border-b">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold text-gray-800">Enrollment Requests</h2>
                <div class="flex space-x-2">
                    <button onclick="openQrGeneratorModal()" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 text-sm">
                        <i class="fas fa-qrcode mr-2"></i>Generate QR Codes
                    </button>
                    <button onclick="openQrHistoryModal()" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm">
                        <i class="fas fa-history mr-2"></i>View All QR Codes
                    </button>
                </div>
            </div>
            
            <!--  Enrollment Requests Tabs -->
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex space-x-8">
                    <button id="pendingTab" class="tab-button border-b-2 border-blue-500 text-blue-600 whitespace-nowrap py-4 px-1 font-medium text-sm">
                        Pending Review
                    </button>
                    <button id="approvedTab" class="tab-button border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 font-medium text-sm">
                        Approved
                    </button>
                    <button id="rejectedTab" class="tab-button border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 font-medium text-sm">
                        Rejected
                    </button>
                </nav>
            </div>
        </div>
        <!-- Table for Enrollment Requests -->
        <div class="overflow-x-auto">
            <!-- Pending Enrollments Table -->
            <table id="pendingTable" class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th id="pendingStudentHeader" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">Student<span class="sort-arrow"></span></th>
                        <th id="pendingProgramHeader" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">Program<span class="sort-arrow"></span></th>
                        <th id="pendingDateHeader" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">Date Submitted<span class="sort-arrow"></span></th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($pendingEnrollments as $enrollment)
                    <tr data-enrollment-id="{{ $enrollment->id }}">
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
                                    <div class="text-sm font-medium text-gray-900">{{ \Illuminate\Support\Str::title($enrollment->last_name) }}, {{ \Illuminate\Support\Str::title($enrollment->first_name) }} {{ \Illuminate\Support\Str::title($enrollment->middle_name) }} {{ \Illuminate\Support\Str::title($enrollment->suffix_name) }}</div>
                                    <div class="text-sm text-gray-500">{{ $enrollment->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $enrollment->program->name ?? 'No Program' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            @if($enrollment->is_re_enrollment && $enrollment->re_enrollment_date)
                                {{ \Carbon\Carbon::parse($enrollment->re_enrollment_date)->format('F d, Y') }}
                                <span class="text-xs text-blue-600">(Re-enrolled)</span>
                            @else
                                {{ \Carbon\Carbon::parse($enrollment->created_at)->format('F d, Y') }}
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center space-x-2">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                Pending Review
                            </span>
                                @if($enrollment->is_re_enrollment)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        <i class="fas fa-redo mr-1"></i>Re-Enrollment
                                    </span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <button onclick="openModal({{ $enrollment->id }})" class="text-blue-600 hover:text-blue-900">View Details</button>
                        </td>
                    </tr>
                    <!-- Details Modal -->
                    <div id="studentDetailsModal-{{ $enrollment->id }}" class="fixed inset-0 bg-black bg-opacity-50 hidden overflow-y-auto h-full w-full z-50" onclick="closeStudentDetailsModalOnOutside(event, '{{ $enrollment->id }}')">
                        <div class="relative top-10 mx-auto p-0 border w-full max-w-4xl shadow-lg rounded-lg bg-white max-h-[90vh] overflow-y-auto modal-scrollbar" onclick="event.stopPropagation()">
                            <!-- Simple Header -->
                            <div class="bg-gray-50 border-b border-gray-200 px-6 py-4">
                                <div class="flex justify-between items-center">
                                    <h3 class="text-lg font-semibold text-gray-900">
                                        Student Details
                                        @if($enrollment->is_re_enrollment)
                                            <span class="ml-2 px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                                <i class="fas fa-redo mr-1"></i>Re-Enrollment
                                            </span>
                                        @endif
                                    </h3>
                                    <button onclick="closeModal({{ $enrollment->id }})" class="text-gray-400 hover:text-gray-600">
                                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            
                            <div class="p-6">
                                <!-- Student Photo and Basic Info Box -->
                                <div class="bg-gray-50 border border-gray-200 rounded-lg p-6 mb-6">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 mr-6">
                                                    @if($enrollment->photo)
                                                <img src="{{ asset('storage/' . $enrollment->photo) }}" alt="Student Photo" class="w-20 h-20 rounded-full object-cover border-2 border-gray-200">
                                                    @elseif($enrollment->user && $enrollment->user->photo)
                                                <img src="{{ asset('storage/' . $enrollment->user->photo) }}" alt="User Photo" class="w-20 h-20 rounded-full object-cover border-2 border-gray-200">
                                                    @else
                                                <div class="w-20 h-20 bg-gray-200 rounded-full flex items-center justify-center">
                                                    <i class="fas fa-user text-gray-400 text-2xl"></i>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="flex-1">
                                            <h2 class="text-2xl font-semibold text-gray-900 mb-1">
                                                {{ \Illuminate\Support\Str::title($enrollment->last_name) }}, {{ \Illuminate\Support\Str::title($enrollment->first_name) }} {{ \Illuminate\Support\Str::title($enrollment->middle_name) }} {{ \Illuminate\Support\Str::title($enrollment->suffix_name) }}
                                            </h2>
                                            <p class="text-gray-600 mb-1">{{ $enrollment->email }}</p>
                                            <p class="text-sm text-gray-500">{{ $enrollment->program->name ?? 'No Program' }}</p>
                                                </div>
                                            </div>
                                        </div>

                                <!-- Basic Information -->
                                <div class="mb-6">
                                    <h3 class="text-lg font-medium text-gray-900 mb-4">Basic Information</h3>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div class="flex items-center py-2 border-b border-gray-100">
                                            <span class="text-sm font-medium text-gray-500 mr-3">Phone Number:</span>
                                            <span class="text-sm text-gray-900">{{ $enrollment->phone ?? 'N/A' }}</span>
                                                </div>
                                        <div class="flex items-center py-2 border-b border-gray-100">
                                            <span class="text-sm font-medium text-gray-500 mr-3">Status:</span>
                                            <span class="text-sm text-gray-900">{{ $enrollment->status ?? 'Pending Review' }}</span>
                                                </div>
                                            </div>
                                        </div>

                                <!-- Personal Details -->
                                <div class="mb-6">
                                    <h3 class="text-lg font-medium text-gray-900 mb-4">Personal Details</h3>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div class="flex items-center py-2 border-b border-gray-100">
                                            <span class="text-sm font-medium text-gray-500 mr-3">Birthdate:</span>
                                            <span class="text-sm text-gray-900">{{ $enrollment->birthdate }}</span>
                                                </div>
                                        <div class="flex items-center py-2 border-b border-gray-100">
                                            <span class="text-sm font-medium text-gray-500 mr-3">Age:</span>
                                            <span class="text-sm text-gray-900">{{ $enrollment->age }}</span>
                                                </div>
                                        <div class="flex items-center py-2 border-b border-gray-100">
                                            <span class="text-sm font-medium text-gray-500 mr-3">Gender:</span>
                                            <span class="text-sm text-gray-900">{{ $enrollment->gender }}</span>
                                            </div>
                                        <div class="flex items-center py-2 border-b border-gray-100">
                                            <span class="text-sm font-medium text-gray-500 mr-3">Citizenship:</span>
                                            <span class="text-sm text-gray-900">{{ $enrollment->citizenship ?? 'N/A' }}</span>
                                        </div>
                                        <div class="flex items-center py-2 border-b border-gray-100">
                                            <span class="text-sm font-medium text-gray-500 mr-3">Religion:</span>
                                            <span class="text-sm text-gray-900">{{ $enrollment->religion ?? 'N/A' }}</span>
                                    </div>
                                        <div class="flex items-center py-2 border-b border-gray-100">
                                            <span class="text-sm font-medium text-gray-500 mr-3">Submission Date:</span>
                                            <span class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($enrollment->created_at)->format('M d, Y') }}</span>
                                </div>
                                        </div>
                                    </div>
                                    
                                <!-- Family Information -->
                                <div class="mb-6">
                                    <h3 class="text-lg font-medium text-gray-900 mb-4">Family Information</h3>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div class="flex items-center py-2 border-b border-gray-100">
                                            <span class="text-sm font-medium text-gray-500 mr-3">Place of Birth:</span>
                                            <span class="text-sm text-gray-900">{{ $enrollment->place_of_birth ?? 'N/A' }}</span>
                                                </div>
                                        <div class="flex items-center py-2 border-b border-gray-100">
                                            <span class="text-sm font-medium text-gray-500 mr-3">Civil Status:</span>
                                            <span class="text-sm text-gray-900">{{ $enrollment->civil_status ?? 'N/A' }}</span>
                                                </div>
                                        <div class="flex items-center py-2 border-b border-gray-100">
                                            <span class="text-sm font-medium text-gray-500 mr-3">Father's Name:</span>
                                            <span class="text-sm text-gray-900">{{ $enrollment->father_name ?? 'N/A' }}</span>
                                            </div>
                                        <div class="flex items-center py-2 border-b border-gray-100">
                                            <span class="text-sm font-medium text-gray-500 mr-3">Mother's Name:</span>
                                            <span class="text-sm text-gray-900">{{ $enrollment->mother_name ?? 'N/A' }}</span>
                                        </div>
                                        <div class="flex items-center py-2 border-b border-gray-100">
                                            <span class="text-sm font-medium text-gray-500 mr-3">Guardian:</span>
                                            <span class="text-sm text-gray-900">{{ $enrollment->guardian ?? 'N/A' }}</span>
                                                </div>
                                        <div class="flex items-center py-2 border-b border-gray-100">
                                            <span class="text-sm font-medium text-gray-500 mr-3">Guardian Contact:</span>
                                            <span class="text-sm text-gray-900">{{ $enrollment->guardian_contact ?? 'N/A' }}</span>
                                                </div>
                                        <div class="flex items-center py-2 border-b border-gray-100">
                                            <span class="text-sm font-medium text-gray-500 mr-3">Parent Consent:</span>
                                            <span class="text-sm text-gray-900">
                                                    @if($enrollment->parent_consent)
                                                    <a href="{{ asset('storage/' . $enrollment->parent_consent) }}" target="_blank" class="text-blue-600 hover:text-blue-800 hover:underline">
                                                        View Document
                                                        </a>
                                                    @else
                                                    <span class="text-red-600">No document uploaded</span>
                                                    @endif
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="mb-8 pt-6 border-t border-gray-200">
                                    <h3 class="text-lg font-medium text-gray-900 mb-4 px-4">Review Actions</h3>
                                    <div class="flex space-x-4 px-4">
                                        <button onclick="confirmApprove({{ $enrollment->id }})" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors">
                                            Approve Enrollment
                                        </button>
                                        <button onclick="showRejectReason({{ $enrollment->id }})" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors">
                                            Reject Enrollment
                                        </button>
                                    </div>
                                    
                                    <div id="rejectReasonBox-{{ $enrollment->id }}" class="mt-4 hidden">
                                        <div class="bg-red-50 border border-red-200 rounded-md p-4">
                                            <label for="rejectReasonInput-{{ $enrollment->id }}" class="block text-sm font-medium text-red-700 mb-2">Rejection Reason</label>
                                            <textarea id="rejectReasonInput-{{ $enrollment->id }}" rows="3" class="w-full border border-red-300 rounded-md py-2 px-3 focus:ring-2 focus:ring-red-500 focus:border-red-500" placeholder="Please provide a reason for rejecting this enrollment..."></textarea>
                                            <button onclick="confirmReject({{ $enrollment->id }})" class="mt-3 px-4 py-2 bg-red-700 text-white rounded-md hover:bg-red-800 transition-colors">
                                                Submit Rejection
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </tbody>
            </table>

            <!-- Approved Enrollments Table -->
            <table id="approvedTable" class="min-w-full divide-y divide-gray-200 hidden">
                <thead class="bg-gray-50">
                    <tr>
                        <th id="approvedStudentHeader" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">Student<span class="sort-arrow"></span></th>
                        <th id="approvedProgramHeader" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">Program & Batch<span class="sort-arrow"></span></th>
                        <th id="approvedDateHeader" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">Date Submitted<span class="sort-arrow"></span></th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th id="approvedApproveHeader" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">Approved Date<span class="sort-arrow"></span></th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($approvedEnrollments as $enrollment)
                    <tr>
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
                                    <div class="text-sm font-medium text-gray-900">{{ \Illuminate\Support\Str::title($enrollment->last_name) }}, {{ \Illuminate\Support\Str::title($enrollment->first_name) }} {{ \Illuminate\Support\Str::title($enrollment->middle_name) }} {{ \Illuminate\Support\Str::title($enrollment->suffix_name) }}</div>
                                    <div class="text-sm text-gray-500">{{ $enrollment->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $enrollment->program->name ?? 'No Program' }}</div>
                            <div class="text-sm text-gray-500">Batch #{{ $enrollment->batch_number }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ \Carbon\Carbon::parse($enrollment->created_at)->format('F d, Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center space-x-2">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                Approved
                            </span>
                                @if($enrollment->is_re_enrollment)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        <i class="fas fa-redo mr-1"></i>Re-Enrollment
                                    </span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $enrollment->approved_at ? \Carbon\Carbon::parse($enrollment->approved_at)->format('F d, Y') : 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <button onclick="openModal({{ $enrollment->id }}, 'approved')" class="text-blue-600 hover:text-blue-900">View Details</button>
                        </td>
                    </tr>
                    <!-- Approved Details Modal -->
                    <div id="studentDetailsModal-approved-{{ $enrollment->id }}" class="fixed inset-0 bg-black bg-opacity-50 hidden overflow-y-auto h-full w-full z-50" onclick="closeStudentDetailsModalOnOutside(event, 'approved-{{ $enrollment->id }}')">
                        <div class="relative top-10 mx-auto p-0 border w-full max-w-4xl shadow-lg rounded-lg bg-white max-h-[90vh] overflow-y-auto modal-scrollbar" onclick="event.stopPropagation()">
                            <!-- Simple Header -->
                            <div class="bg-gray-50 border-b border-gray-200 px-6 py-4">
                                <div class="flex justify-between items-center">
                                    <h3 class="text-lg font-semibold text-gray-900">
                                        Student Details - Approved
                                        @if($enrollment->is_re_enrollment)
                                            <span class="ml-2 px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                                <i class="fas fa-redo mr-1"></i>Re-Enrollment
                                            </span>
                                        @endif
                                    </h3>
                                    <button onclick="closeModal({{ $enrollment->id }}, 'approved')" class="text-gray-400 hover:text-gray-600">
                                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            
                            <div class="p-6">
                                <!-- Student Photo and Basic Info Box -->
                                <div class="bg-gray-50 border border-gray-200 rounded-lg p-6 mb-6">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 mr-6">
                                    @if($enrollment->photo)
                                                <img src="{{ asset('storage/' . $enrollment->photo) }}" alt="Student Photo" class="w-20 h-20 rounded-full object-cover border-2 border-gray-200">
                                    @elseif($enrollment->user && $enrollment->user->photo)
                                                <img src="{{ asset('storage/' . $enrollment->user->photo) }}" alt="User Photo" class="w-20 h-20 rounded-full object-cover border-2 border-gray-200">
                                    @else
                                                <div class="w-20 h-20 bg-gray-200 rounded-full flex items-center justify-center">
                                                    <i class="fas fa-user text-gray-400 text-2xl"></i>
                                        </div>
                                    @endif
                                </div>
                                        <div class="flex-1">
                                            <h2 class="text-2xl font-semibold text-gray-900 mb-1">
                                                {{ \Illuminate\Support\Str::title($enrollment->last_name) }}, {{ \Illuminate\Support\Str::title($enrollment->first_name) }} {{ \Illuminate\Support\Str::title($enrollment->middle_name) }} {{ \Illuminate\Support\Str::title($enrollment->suffix_name) }}
                                            </h2>
                                            <p class="text-gray-600 mb-1">{{ $enrollment->email }}</p>
                                            <p class="text-sm text-gray-500">{{ $enrollment->program->name ?? 'No Program' }}</p>
                                        </div>
                                        </div>
                                    </div>

                                <!-- Basic Information -->
                                <div class="mb-6">
                                    <h3 class="text-lg font-medium text-gray-900 mb-4">Basic Information</h3>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div class="flex items-center py-2 border-b border-gray-100">
                                            <span class="text-sm font-medium text-gray-500 mr-3">Phone Number:</span>
                                            <span class="text-sm text-gray-900">{{ $enrollment->phone ?? 'N/A' }}</span>
                                        </div>
                                        <div class="flex items-center py-2 border-b border-gray-100">
                                            <span class="text-sm font-medium text-gray-500 mr-3">Status:</span>
                                            <select id="statusSelect-approved-{{ $enrollment->id }}" class="text-sm text-gray-900 border-none bg-transparent focus:ring-0 focus:border-none">
                                                <option value="approved" {{ ($enrollment->status ?? 'approved') == 'approved' ? 'selected' : '' }}>Approved</option>
                                                <option value="enrolled" {{ ($enrollment->status ?? 'approved') == 'enrolled' ? 'selected' : '' }}>Enrolled</option>
                                            </select>
                                        </div>
                                    </div>
                                        </div>

                                <!-- Personal Details -->
                                <div class="mb-6">
                                    <h3 class="text-lg font-medium text-gray-900 mb-4">Personal Details</h3>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div class="flex items-center py-2 border-b border-gray-100">
                                            <span class="text-sm font-medium text-gray-500 mr-3">Birthdate:</span>
                                            <span class="text-sm text-gray-900">{{ $enrollment->birthdate }}</span>
                                        </div>
                                        <div class="flex items-center py-2 border-b border-gray-100">
                                            <span class="text-sm font-medium text-gray-500 mr-3">Age:</span>
                                            <span class="text-sm text-gray-900">{{ $enrollment->age }}</span>
                                    </div>
                                        <div class="flex items-center py-2 border-b border-gray-100">
                                            <span class="text-sm font-medium text-gray-500 mr-3">Gender:</span>
                                            <span class="text-sm text-gray-900">{{ $enrollment->gender }}</span>
                                        </div>
                                        <div class="flex items-center py-2 border-b border-gray-100">
                                            <span class="text-sm font-medium text-gray-500 mr-3">Citizenship:</span>
                                            <span class="text-sm text-gray-900">{{ $enrollment->citizenship ?? 'N/A' }}</span>
                                        </div>
                                        <div class="flex items-center py-2 border-b border-gray-100">
                                            <span class="text-sm font-medium text-gray-500 mr-3">Religion:</span>
                                            <span class="text-sm text-gray-900">{{ $enrollment->religion ?? 'N/A' }}</span>
                                    </div>
                                        <div class="flex items-center py-2 border-b border-gray-100">
                                            <span class="text-sm font-medium text-gray-500 mr-3">Submission Date:</span>
                                            <span class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($enrollment->created_at)->format('M d, Y') }}</span>
                                        </div>
                                        </div>
                                    </div>

                                <!-- Family Information -->
                                <div class="mb-6">
                                    <h3 class="text-lg font-medium text-gray-900 mb-4">Family Information</h3>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div class="flex items-center py-2 border-b border-gray-100">
                                            <span class="text-sm font-medium text-gray-500 mr-3">Place of Birth:</span>
                                            <span class="text-sm text-gray-900">{{ $enrollment->place_of_birth ?? 'N/A' }}</span>
                                        </div>
                                        <div class="flex items-center py-2 border-b border-gray-100">
                                            <span class="text-sm font-medium text-gray-500 mr-3">Civil Status:</span>
                                            <span class="text-sm text-gray-900">{{ $enrollment->civil_status ?? 'N/A' }}</span>
                                        </div>
                                        <div class="flex items-center py-2 border-b border-gray-100">
                                            <span class="text-sm font-medium text-gray-500 mr-3">Father's Name:</span>
                                            <span class="text-sm text-gray-900">{{ $enrollment->father_name ?? 'N/A' }}</span>
                                    </div>
                                        <div class="flex items-center py-2 border-b border-gray-100">
                                            <span class="text-sm font-medium text-gray-500 mr-3">Mother's Name:</span>
                                            <span class="text-sm text-gray-900">{{ $enrollment->mother_name ?? 'N/A' }}</span>
                                        </div>
                                        <div class="flex items-center py-2 border-b border-gray-100">
                                            <span class="text-sm font-medium text-gray-500 mr-3">Guardian:</span>
                                            <span class="text-sm text-gray-900">{{ $enrollment->guardian ?? 'N/A' }}</span>
                                        </div>
                                        <div class="flex items-center py-2 border-b border-gray-100">
                                            <span class="text-sm font-medium text-gray-500 mr-3">Guardian Contact:</span>
                                            <span class="text-sm text-gray-900">{{ $enrollment->guardian_contact ?? 'N/A' }}</span>
                                        </div>
                                        <div class="flex items-center py-2 border-b border-gray-100">
                                            <span class="text-sm font-medium text-gray-500 mr-3">Parent Consent:</span>
                                            <span class="text-sm text-gray-900">
                                            @if($enrollment->parent_consent)
                                                    <a href="{{ asset('storage/' . $enrollment->parent_consent) }}" target="_blank" class="text-blue-600 hover:text-blue-800 hover:underline">
                                                        View Document
                                                </a>
                                            @else
                                                    <span class="text-red-600">No document uploaded</span>
                                            @endif
                                            </span>
                                        </div>
                                        <div class="flex justify-between py-2 border-b border-gray-100">
                                            <span class="text-sm font-medium text-gray-500">Approved Date:</span>
                                            <span class="text-sm text-gray-900">{{ $enrollment->approved_at ? \Carbon\Carbon::parse($enrollment->approved_at)->format('M d, Y') : 'N/A' }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Save Button -->
                                <div class="flex justify-end space-x-4 py-4">
                                    <button onclick="saveStatus({{ $enrollment->id }}, 'approved')" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Save Changes</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </tbody>
            </table>

            <!-- Rejected Enrollments Table -->
            <table id="rejectedTable" class="min-w-full divide-y divide-gray-200 hidden">
                <thead class="bg-gray-50">
                    <tr>
                        <th id="rejectedStudentHeader" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">Student<span class="sort-arrow"></span></th>
                        <th id="rejectedProgramHeader" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">Program<span class="sort-arrow"></span></th>
                        <th id="rejectedDateHeader" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">Date Submitted<span class="sort-arrow"></span></th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th id="rejectedRejectedHeader" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">Rejected Date<span class="sort-arrow"></span></th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reason</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($rejectedEnrollments as $enrollment)
                    <tr>
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
                                    <div class="text-sm font-medium text-gray-900">{{ \Illuminate\Support\Str::title($enrollment->last_name) }}, {{ \Illuminate\Support\Str::title($enrollment->first_name) }} {{ \Illuminate\Support\Str::title($enrollment->middle_name) }} {{ \Illuminate\Support\Str::title($enrollment->suffix_name) }}</div>
                                    <div class="text-sm text-gray-500">{{ $enrollment->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $enrollment->program->name ?? 'No Program' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ \Carbon\Carbon::parse($enrollment->created_at)->format('F d, Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center space-x-2">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                Rejected
                            </span>
                                @if($enrollment->is_re_enrollment)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        <i class="fas fa-redo mr-1"></i>Re-Enrollment
                                    </span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $enrollment->rejected_at ? \Carbon\Carbon::parse($enrollment->rejected_at)->format('F d, Y') : 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $enrollment->rejection_reason ?? 'No reason provided' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <button onclick="openModal({{ $enrollment->id }}, 'rejected')" class="text-blue-600 hover:text-blue-900">View Details</button>
                        </td>
                    </tr>
                    <!-- Rejected Details Modal -->
                    <div id="studentDetailsModal-rejected-{{ $enrollment->id }}" class="fixed inset-0 bg-black bg-opacity-50 hidden overflow-y-auto h-full w-full z-50" onclick="closeStudentDetailsModalOnOutside(event, 'rejected-{{ $enrollment->id }}')">
                        <div class="relative top-10 mx-auto p-0 border w-full max-w-4xl shadow-lg rounded-lg bg-white max-h-[90vh] overflow-y-auto modal-scrollbar" onclick="event.stopPropagation()">
                            <!-- Simple Header -->
                            <div class="bg-gray-50 border-b border-gray-200 px-6 py-4">
                                <div class="flex justify-between items-center">
                                    <h3 class="text-lg font-semibold text-gray-900">
                                        Student Details - Rejected
                                        @if($enrollment->is_re_enrollment)
                                            <span class="ml-2 px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                                <i class="fas fa-redo mr-1"></i>Re-Enrollment
                                            </span>
                                        @endif
                                    </h3>
                                    <button onclick="closeModal({{ $enrollment->id }}, 'rejected')" class="text-gray-400 hover:text-gray-600">
                                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            
                            <div class="p-6">
                                <!-- Student Photo and Basic Info Box -->
                                <div class="bg-gray-50 border border-gray-200 rounded-lg p-6 mb-6">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 mr-6">
                                    @if($enrollment->photo)
                                                <img src="{{ asset('storage/' . $enrollment->photo) }}" alt="Student Photo" class="w-20 h-20 rounded-full object-cover border-2 border-gray-200">
                                    @elseif($enrollment->user && $enrollment->user->photo)
                                                <img src="{{ asset('storage/' . $enrollment->user->photo) }}" alt="User Photo" class="w-20 h-20 rounded-full object-cover border-2 border-gray-200">
                                    @else
                                                <div class="w-20 h-20 bg-gray-200 rounded-full flex items-center justify-center">
                                                    <i class="fas fa-user text-gray-400 text-2xl"></i>
                                        </div>
                                    @endif
                                </div>
                                        <div class="flex-1">
                                            <h2 class="text-2xl font-semibold text-gray-900 mb-1">
                                                {{ \Illuminate\Support\Str::title($enrollment->last_name) }}, {{ \Illuminate\Support\Str::title($enrollment->first_name) }} {{ \Illuminate\Support\Str::title($enrollment->middle_name) }} {{ \Illuminate\Support\Str::title($enrollment->suffix_name) }}
                                            </h2>
                                            <p class="text-gray-600 mb-1">{{ $enrollment->email }}</p>
                                            <p class="text-sm text-gray-500">{{ $enrollment->program->name ?? 'No Program' }}</p>
                                        </div>
                                        </div>
                                    </div>

                                <!-- Basic Information -->
                                <div class="mb-6">
                                    <h3 class="text-lg font-medium text-gray-900 mb-4">Basic Information</h3>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div class="flex items-center py-2 border-b border-gray-100">
                                            <span class="text-sm font-medium text-gray-500 mr-3">Phone Number:</span>
                                            <span class="text-sm text-gray-900">{{ $enrollment->phone ?? 'N/A' }}</span>
                                        </div>
                                        <div class="flex items-center py-2 border-b border-gray-100">
                                            <span class="text-sm font-medium text-gray-500 mr-3">Status:</span>
                                            <span class="text-sm text-red-600 font-medium">{{ $enrollment->status ?? 'Rejected' }}</span>
                                        </div>
                                    </div>
                                        </div>

                                <!-- Personal Details -->
                                <div class="mb-6">
                                    <h3 class="text-lg font-medium text-gray-900 mb-4">Personal Details</h3>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div class="flex items-center py-2 border-b border-gray-100">
                                            <span class="text-sm font-medium text-gray-500 mr-3">Birthdate:</span>
                                            <span class="text-sm text-gray-900">{{ $enrollment->birthdate }}</span>
                                        </div>
                                        <div class="flex items-center py-2 border-b border-gray-100">
                                            <span class="text-sm font-medium text-gray-500 mr-3">Age:</span>
                                            <span class="text-sm text-gray-900">{{ $enrollment->age }}</span>
                                    </div>
                                        <div class="flex items-center py-2 border-b border-gray-100">
                                            <span class="text-sm font-medium text-gray-500 mr-3">Gender:</span>
                                            <span class="text-sm text-gray-900">{{ $enrollment->gender }}</span>
                                        </div>
                                        <div class="flex items-center py-2 border-b border-gray-100">
                                            <span class="text-sm font-medium text-gray-500 mr-3">Submission Date:</span>
                                            <span class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($enrollment->created_at)->format('M d, Y') }}</span>
                                        </div>
                                    </div>
                                        </div>

                                <!-- Rejection Information -->
                                <div class="mb-6">
                                    <h3 class="text-lg font-medium text-gray-900 mb-4">Rejection Information</h3>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div class="flex items-center py-2 border-b border-gray-100">
                                            <span class="text-sm font-medium text-gray-500 mr-3">Rejected Date:</span>
                                            <span class="text-sm text-gray-900">{{ $enrollment->rejected_at ? \Carbon\Carbon::parse($enrollment->rejected_at)->format('M d, Y') : 'N/A' }}</span>
                                        </div>
                                        <div class="flex items-center py-2 border-b border-gray-100">
                                            <span class="text-sm font-medium text-gray-500 mr-3">Rejection Reason:</span>
                                            <span class="text-sm text-gray-900">{{ $enrollment->rejection_reason ?? 'No reason provided' }}</span>
                                    </div>
                                        </div>
                                </div>

                                <!-- Family Information -->
                                <div class="mb-6">
                                    <h3 class="text-lg font-medium text-gray-900 mb-4">Family Information</h3>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div class="flex items-center py-2 border-b border-gray-100">
                                            <span class="text-sm font-medium text-gray-500 mr-3">Parent Consent:</span>
                                            <span class="text-sm text-gray-900">
                                                @if($enrollment->parent_consent)
                                                    <a href="{{ asset('storage/' . $enrollment->parent_consent) }}" target="_blank" class="text-blue-600 hover:text-blue-800 hover:underline">
                                                        View Document
                                                    </a>
                                                @else
                                                    <span class="text-red-600">No document uploaded</span>
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </tbody>
            </table>

            <div class="bg-gray-50 px-6 py-3 flex items-center justify-between border-t border-gray-200">
                <div class="text-sm text-gray-500" id="paginationNote">
                    {{ $pendingEnrollments->total() }} total pending entries
                </div>
                <div class="flex space-x-2">
                    {{ $pendingEnrollments->appends(['pending_page' => $pendingEnrollments->currentPage(), 'approved_page' => $approvedEnrollments->currentPage(), 'rejected_page' => $rejectedEnrollments->currentPage()])->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- QR Code Generator Modal -->
<div id="qrGeneratorModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden px-2" onclick="closeQrGeneratorModalOnOutside(event)">
    <div class="bg-white rounded shadow-md w-full max-w-md p-6" onclick="event.stopPropagation()">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold text-gray-900">Generate QR Codes</h2>
            <button onclick="closeQrGeneratorModal()" class="text-gray-500 hover:text-gray-700 text-2xl font-bold leading-none">&times;</button>
        </div>
        
            <div class="space-y-4">
                <div>
                    <label for="qrQuantity" class="block text-sm font-medium text-gray-700 mb-1">Number of QR Codes</label>
                    <input type="number" id="qrQuantity" min="1" max="100" value="1" 
                           class="w-full border border-gray-300 rounded-md shadow-sm py-2 px-3">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">QR Code Type</label>
                    <div class="space-y-2">
                        <label class="flex items-center">
                            <input type="radio" name="qrType" value="enrollment" checked class="mr-2">
                            <span class="text-sm text-gray-700">Enrollment</span>
                        </label>
                    </div>
                </div>
                <div id="qrPreviewContainer" class="hidden">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Preview</label>
                    <div class="border border-gray-300 rounded-md p-4 flex justify-center">
                        <div id="qrPreview"></div>
                    </div>
                </div>
            
            <div class="flex space-x-3 pt-4">
                <button onclick="generateQrCodes()" class="flex-1 px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                    <i class="fas fa-qrcode mr-2"></i>Generate
                </button>
                <button onclick="closeQrGeneratorModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>

<!-- QR Codes Display Modal -->
<div id="qrDisplayModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden px-2" onclick="closeQrDisplayModalOnOutside(event)">
    <div class="bg-white rounded shadow-md w-full max-w-6xl p-6 max-h-[90vh] overflow-y-auto" onclick="event.stopPropagation()">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-900">Generated QR Codes</h2>
            <button onclick="closeQrDisplayModal()" class="text-gray-500 hover:text-gray-700 text-3xl font-bold leading-none">&times;</button>
        </div>
        
        <div class="mb-6 flex justify-between items-center">
            <div class="text-sm text-gray-600" id="qrCodesCount"></div>
            <div class="flex space-x-2">
                <button onclick="selectAllQrCodes()" class="px-3 py-1 bg-gray-200 text-gray-700 rounded text-sm hover:bg-gray-300">
                    <i class="fas fa-check-square mr-1"></i>Select All
                </button>
                <button onclick="deselectAllQrCodes()" class="px-3 py-1 bg-gray-200 text-gray-700 rounded text-sm hover:bg-gray-300">
                    <i class="fas fa-times-circle mr-1"></i>Deselect All
                </button>
            </div>
        </div>
        
        <div id="qrCodesContainer" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4 mb-6"></div>
        
        <div class="flex space-x-3 justify-end">
            <button onclick="downloadSelectedQrCodes()" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                <i class="fas fa-download mr-2"></i>Download Selected
            </button>
            <button onclick="downloadAllQrCodes()" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                <i class="fas fa-download mr-2"></i>Download All
            </button>
            <button onclick="printQrCodes()" class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700">
                <i class="fas fa-print mr-2"></i>Print All
            </button>
            <button onclick="closeQrDisplayModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                Close
            </button>
        </div>
    </div>
</div>

<!-- QR History Modal -->
<div id="qrHistoryModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden px-2" onclick="closeQrHistoryModalOnOutside(event)">
    <div class="bg-white rounded shadow-md w-full max-w-4xl p-6 max-h-[90vh] overflow-y-auto" onclick="event.stopPropagation()">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-900">QR Code History</h2>
            <div class="flex items-center space-x-2">
                <button onclick="loadQrHistory()" class="px-3 py-1 bg-blue-500 text-white text-sm rounded hover:bg-blue-600">
                    <i class="fas fa-sync-alt mr-1"></i>Refresh
                </button>
                <button onclick="closeQrHistoryModal()" class="text-gray-500 hover:text-gray-700 text-3xl font-bold leading-none">&times;</button>
            </div>
        </div>
        
        <div id="qrHistoryContainer" class="space-y-4">
            <!-- Loading state -->
            <div id="qrHistoryLoading" class="text-center py-8">
                <i class="fas fa-spinner fa-spin text-2xl text-gray-400 mb-2"></i>
                <p class="text-gray-500">Loading QR code history...</p>
            </div>
            
            <!-- Empty state -->
            <div id="qrHistoryEmpty" class="text-center py-8 hidden">
                <i class="fas fa-qrcode text-4xl text-gray-300 mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No QR codes found</h3>
                <p class="text-gray-500">No QR codes have been generated yet.</p>
            </div>
            
            <!-- Date groups will be populated here -->
        </div>
    </div>
</div>

<!-- QR Codes for Date Modal -->
<div id="qrDateModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden px-2" onclick="closeQrDateModalOnOutside(event)">
    <div class="bg-white rounded shadow-md w-full max-w-6xl p-6 max-h-[90vh] overflow-y-auto" onclick="event.stopPropagation()">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-900" id="qrDateModalTitle">QR Codes for Date</h2>
            <button onclick="closeQrDateModal()" class="text-gray-500 hover:text-gray-700 text-3xl font-bold leading-none">&times;</button>
        </div>
        
        <div class="mb-6 flex justify-between items-center">
            <div class="text-sm text-gray-600" id="qrDateCodesCount"></div>
            <div class="flex space-x-2">
                <button onclick="selectAllQrCodes()" class="px-3 py-1 bg-gray-200 text-gray-700 rounded text-sm hover:bg-gray-300">
                    <i class="fas fa-check-square mr-1"></i>Select All
                </button>
                <button onclick="deselectAllQrCodes()" class="px-3 py-1 bg-gray-200 text-gray-700 rounded text-sm hover:bg-gray-300">
                    <i class="fas fa-times-circle mr-1"></i>Deselect All
                </button>
            </div>
        </div>
        
        <div id="qrDateCodesContainer" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4 mb-6"></div>
        
        <div class="flex space-x-3 justify-end">
            <button onclick="downloadSelectedQrCodes()" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                <i class="fas fa-download mr-2"></i>Download Selected
            </button>
            <button onclick="downloadAllQrCodes()" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                <i class="fas fa-download mr-2"></i>Download All
            </button>
            <button onclick="printQrCodes()" class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700">
                <i class="fas fa-print mr-2"></i>Print All
            </button>
            <button onclick="closeQrDateModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                Close
            </button>
        </div>
    </div>
</div>

    @push('scripts')
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
        item.addEventListener('click', function(e) {
            // Remove active class from all items
            navItems.forEach(nav => nav.classList.remove('active-nav'));
            // Add active class to clicked item
            this.classList.add('active-nav');
            
            const url = this.getAttribute('data-url');
            if (url) {
                // Prevent default link behavior
                e.preventDefault();
                
                // Show loading overlay before navigation
                if (typeof window.loadingManager !== 'undefined') {
                    const navText = this.querySelector('.nav-text').textContent.trim();
                    window.loadingManager.show('Loading ' + navText, 'Please wait while we navigate to ' + navText);
                }
                
                // Navigate after a short delay to show loading
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

    // Add loading for page reloads and browser navigation
    window.addEventListener('beforeunload', function() {
        if (typeof window.loadingManager !== 'undefined') {
            window.loadingManager.show('Loading Page', 'Please wait while we load the page');
        }
    });

    // Add loading for browser back/forward buttons
    window.addEventListener('popstate', function() {
        if (typeof window.loadingManager !== 'undefined') {
            window.loadingManager.show('Loading Page', 'Please wait while we load the page');
        }
    });

    // Show loading on page load if coming from another page
    if (performance.navigation.type === 1) { // Navigation type 1 = reload
        if (typeof window.loadingManager !== 'undefined') {
            window.loadingManager.show('Loading Page', 'Please wait while we load the page');
            // Hide loading after page is fully loaded
            window.addEventListener('load', function() {
                setTimeout(() => {
                    window.loadingManager.hide();
                }, 500);
            });
        }
    }

    // Responsive adjustments
    function handleResize() {
        if (window.innerWidth < 768) {
            document.querySelector('.sidebar').classList.add('sidebar-collapsed');
            document.querySelector('.content-area').classList.remove('ml-1');
            document.querySelector('.content-area').classList.add('ml-1');
        } else {
            document.querySelector('.sidebar').classList.remove('sidebar-collapsed');
            document.querySelector('.content-area').classList.remove('ml-1');
            document.querySelector('.content-area').classList.add('ml-1');
        }
    }

    window.addEventListener('resize', handleResize);
    handleResize(); // Run once on load

    // On page load, apply sidebar collapsed state and icon rotation
    window.addEventListener('DOMContentLoaded', () => {
        const sidebar = document.querySelector('.sidebar');
        const contentArea = document.querySelector('.content-area');
        const toggleIcon = document.querySelector('#toggleSidebar i');

        if (localStorage.getItem('sidebar-collapsed') === 'true') {
            sidebar.classList.add('sidebar-collapsed');
            contentArea.classList.add('ml-1');
            if (toggleIcon.classList.contains('fa-chevron-left')) {
                toggleIcon.classList.remove('fa-chevron-left');
                toggleIcon.classList.add('fa-chevron-right');
            }
        } else {
            sidebar.classList.remove('sidebar-collapsed');
            contentArea.classList.remove('ml-1');
        }
    });

    // Tab switching functionality
    const tabs = ['pending', 'approved', 'rejected'];

    const tabCounts = {
        pending: {{ $pendingEnrollments->total() }},
        approved: {{ $approvedEnrollments->total() }},
        rejected: {{ $rejectedEnrollments->total() }}
    };

    const tabLabels = {
        pending: 'pending',
        approved: 'approved',
        rejected: 'rejected',
    };

    // Initialize tab counts in tab labels
    document.getElementById('pendingTab').textContent = `Pending Review (${tabCounts.pending})`;
    document.getElementById('approvedTab').textContent = `Approved (${tabCounts.approved})`;
    document.getElementById('rejectedTab').textContent = `Rejected (${tabCounts.rejected})`;

    tabs.forEach(tab => {
        document.getElementById(`${tab}Tab`).addEventListener('click', function() {
            // Show loading animation
            if (typeof window.loadingManager !== 'undefined') {
                const tabName = this.textContent.trim().replace(/\(\d+\)/, '').trim();
                window.loadingManager.show('Loading ' + tabName, 'Please wait while we load the ' + tabName + ' data');
            }

            // Update active tab styling
            document.querySelectorAll('.tab-button').forEach(btn => {
                btn.classList.remove('border-blue-500', 'text-blue-600');
                btn.classList.add('border-transparent', 'text-gray-500');
            });
            this.classList.add('border-blue-500', 'text-blue-600');
            this.classList.remove('border-transparent', 'text-gray-500');

            // Show/hide tables with a slight delay to show loading
            setTimeout(() => {
            tabs.forEach(t => {
                const table = document.getElementById(`${t}Table`);
                if (t === tab) {
                    table.classList.remove('hidden');
                } else {
                    table.classList.add('hidden');
                }
            });

            // Update pagination note
            const paginationNote = document.getElementById('paginationNote');
            const count = tabCounts[tab];
            const label = tabLabels[tab];
            paginationNote.textContent = `${count} total ${label} entries`;

                // Hide loading animation
                if (typeof window.loadingManager !== 'undefined') {
                    window.loadingManager.hide();
                }
            }, 300);
        });
    });

    // Logout functionality
    document.getElementById('logoutButton').addEventListener('click', function(e) {
        e.preventDefault();
        document.getElementById('logout-form').submit();
    });

    // Modal logic for details
    function openModal(id, tab = 'pending') {
        if (tab === 'pending') {
            document.getElementById(`studentDetailsModal-${id}`).classList.remove('hidden');
        } else {
            document.getElementById(`studentDetailsModal-${tab}-${id}`).classList.remove('hidden');
        }
    }

    function closeModal(id, tab = 'pending') {
        if (tab === 'pending') {
            document.getElementById(`studentDetailsModal-${id}`).classList.add('hidden');
        } else {
            document.getElementById(`studentDetailsModal-${tab}-${id}`).classList.add('hidden');
        }
    }

    function closeStudentDetailsModalOnOutside(event, modalId) {
        if (event.target === event.currentTarget) {
            document.getElementById(`studentDetailsModal-${modalId}`).classList.add('hidden');
        }
    }

    // Show the reject reason textarea when Reject is clicked
    function showRejectReason(id) {
        document.getElementById(`rejectReasonBox-${id}`).classList.remove('hidden');
    }

    // Submit the reject reason and call handleEnrollmentAction with reason
    function submitRejectReason(id) {
        const reason = document.getElementById(`rejectReasonInput-${id}`).value;
        if (!reason.trim()) {
            alert('Please provide a reason for rejection.');
            return;
        }
        handleEnrollmentAction(id, 'reject', reason);
        document.getElementById(`rejectReasonBox-${id}`).classList.add('hidden');
    }

    function confirmApprove(id) {
        if (confirm('Are you sure you want to approve this application/enrollment?')) {
            handleEnrollmentAction(id, 'approve');
        }
    }

    function confirmReject(id) {
        const reason = document.getElementById(`rejectReasonInput-${id}`).value;
        if (!reason.trim()) {
            alert('Please provide a reason for rejection.');
            return;
        }
        if (confirm('Are you sure you want to reject this application/enrollment?')) {
            handleEnrollmentAction(id, 'reject', reason);
            document.getElementById(`rejectReasonBox-${id}`).classList.add('hidden');
        }
    }

    //Handle enrollment actions (approve/reject)
    function handleEnrollmentAction(id, action, reason = null) {
        if (!['approve', 'reject'].includes(action)) return;

        let bodyData = {};
        if (action === 'reject' && reason) {
            bodyData.reason = reason;
        }

        fetch(`/admin/enrollments/${id}/${action}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify(bodyData)
        })
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.json();
        })
        .then(data => {
            closeModal(id);

            // Show confirmation text
            let confirmText = '';
            if (action === 'approve') {
                confirmText = 'Enrollment approved successfully!';
            } else if (action === 'reject') {
                confirmText = 'Enrollment rejected with reason submitted!';
            }
            showConfirmation(confirmText);

            // Move row and update counts
            const pendingRow = document.querySelector(`tr[data-enrollment-id="${id}"]`);
            if (pendingRow) {
                const rowClone = pendingRow.cloneNode(true);

                // Update status and styling
                const statusCell = rowClone.querySelector('td:nth-child(4) > span');
                if (statusCell) {
                    if(action === 'approve') {
                        statusCell.textContent = 'Approved';
                        statusCell.className = 'px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800';
                    } else if(action === 'reject') {
                        statusCell.textContent = 'Rejected';
                        statusCell.className = 'px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800';
                    }
                }

                // Update actions cell
                const actionsCell = rowClone.querySelector('td:nth-child(5)');
                if (actionsCell) {
                    actionsCell.innerHTML = '<button class="text-blue-600 hover:text-blue-900">View Details</button>';
                }

                // Remove from pending table
                pendingRow.remove();

                // Add to appropriate table
                if (action === 'approve') {
                    document.querySelector('#approvedTable tbody').appendChild(rowClone);
                } else if (action === 'reject') {
                    document.querySelector('#rejectedTable tbody').appendChild(rowClone);
                }

                // Update counts
                tabCounts.pending--;
                tabCounts[action]++;

                // Update tab labels
                document.getElementById('pendingTab').textContent = `Pending Review (${tabCounts.pending})`;
                document.getElementById('approvedTab').textContent = `Approved (${tabCounts.approved})`;
                document.getElementById('rejectedTab').textContent = `Rejected (${tabCounts.rejected})`;

                // If no more pending items, show empty state
                if (tabCounts.pending === 0) {
                    const pendingTable = document.querySelector('#pendingTable tbody');
                    pendingTable.innerHTML = '<tr><td colspan="5" class="px-6 py-4 text-center text-gray-500">No pending enrollments</td></tr>';
                }
            }
        })
        .catch(error => {
            alert('There was an error processing your request.');
            console.error('Error:', error);
        });
    }

    // Confirmation message
    function showConfirmation(message) {
        let existing = document.getElementById('confirmationMessage');
        if (existing) existing.remove();

        const div = document.createElement('div');
        div.id = 'confirmationMessage';
        div.className = 'fixed top-8 right-8 bg-green-600 text-white px-6 py-3 rounded shadow-lg z-50';
        div.textContent = message;
        document.body.appendChild(div);

        setTimeout(() => {
            div.remove();
        }, 2500);
    }

    // Notification system for better user feedback
    function showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `fixed top-8 right-8 px-6 py-3 rounded shadow-lg z-50 text-white transition-all duration-300 transform translate-x-full`;
        
        switch(type) {
            case 'success':
                notification.className += ' bg-green-600';
                break;
            case 'error':
                notification.className += ' bg-red-600';
                break;
            case 'warning':
                notification.className += ' bg-yellow-600';
                break;
            default:
                notification.className += ' bg-blue-600';
        }
        
        notification.textContent = message;
        document.body.appendChild(notification);
        
        // Animate in
        setTimeout(() => {
            notification.classList.remove('translate-x-full');
            notification.classList.add('translate-x-0');
        }, 10);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            notification.classList.remove('translate-x-0');
            notification.classList.add('translate-x-full');
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
            }, 300);
        }, 5000);
        
        // Allow manual dismissal
        notification.addEventListener('click', () => {
            notification.classList.remove('translate-x-0');
            notification.classList.add('translate-x-full');
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
            }, 300);
        });
    }

    // Sorting functionality
    function getTableAndTbody(tab) {
    let tableId = tab + 'Table';
    let table = document.getElementById(tableId);
    let tbody = table.querySelector('tbody');
    return { table, tbody };
    }

    let sortStates = {
        pending: { student: 0, program: 0, date: 0 },
        approved: { student: 0, program: 0, date: 0, approve: 0 },
        rejected: { student: 0, program: 0, date: 0, rejected: 0 }
    };

    let originalRows = {
        pending: Array.from(document.querySelector('#pendingTable tbody').querySelectorAll('tr')),
        approved: Array.from(document.querySelector('#approvedTable tbody').querySelectorAll('tr')),
        rejected: Array.from(document.querySelector('#rejectedTable tbody').querySelectorAll('tr'))
    };

    function resetSort(tab) {
        Object.keys(sortStates[tab]).forEach(key => sortStates[tab][key] = 0);
        let { tbody } = getTableAndTbody(tab);
        tbody.innerHTML = '';
        originalRows[tab].forEach(row => tbody.appendChild(row));
        updateArrows(tab, null, 0);
    }

    function sortTable(tab, column, asc) {
        let { tbody } = getTableAndTbody(tab);
        let rows = Array.from(tbody.querySelectorAll('tr'));
        let idx;
        if (column === 'student') idx = 0;
        if (column === 'program') idx = 1;
        if (column === 'date') idx = 2;
        if (column === 'approve') idx = 4; // Approved Date column index
        if (column === 'rejected') idx = 4; // Rejected Date column index

        rows.sort((a, b) => {
            let aText = a.children[idx].innerText.trim().toLowerCase();
            let bText = b.children[idx].innerText.trim().toLowerCase();
            if (column === 'date' || column === 'approve' || column === 'rejected') {
                aText = new Date(aText);
                bText = new Date(bText);
            }
            if (aText < bText) return asc ? -1 : 1;
            if (aText > bText) return asc ? 1 : -1;
            return 0;
        });

        tbody.innerHTML = '';
        rows.forEach(row => tbody.appendChild(row));
        updateArrows(tab, column, asc ? 1 : 2);
    }

    function updateArrows(tab, column, state) {
        let table = document.getElementById(tab + 'Table');
        table.querySelectorAll('.sort-arrow').forEach(span => span.innerHTML = '');

        if (!column || state === 0) return;
        let thId = tab + capitalize(column) + 'Header';
        let th = document.getElementById(thId);
        let arrowSpan = th.querySelector('.sort-arrow');
        if (state === 1) arrowSpan.innerHTML = ' <i class="fas fa-arrow-down"></i>';
        else if (state === 2) arrowSpan.innerHTML = ' <i class="fas fa-arrow-up"></i>';
    }

    // Add event listeners for each tab's table headers
    function addSortListeners(tab) {
        let columns = Object.keys(sortStates[tab]);
        columns.forEach(column => {
            let thId = tab + capitalize(column) + 'Header';
            let th = document.getElementById(thId);
            if (!th) return;
            th.addEventListener('click', function() {
                columns.forEach(col => { if (col !== column) sortStates[tab][col] = 0; });
                sortStates[tab][column] = (sortStates[tab][column] + 1) % (column === 'student' ? 3 : 2);
                if (sortStates[tab][column] === 1) sortTable(tab, column, true);
                else if (sortStates[tab][column] === 2 && column === 'student') sortTable(tab, column, false);
                else resetSort(tab);
            });
        });
    }

    function capitalize(str) {
        return str.charAt(0).toUpperCase() + str.slice(1);
    }

    // Initialize listeners for all tabs
    addSortListeners('pending');
    addSortListeners('approved');
    addSortListeners('rejected');

    // QR Code Generator Modal Functions
    function openQrGeneratorModal() {
        document.getElementById('qrGeneratorModal').classList.remove('hidden');
    }

    function closeQrGeneratorModal() {
        document.getElementById('qrGeneratorModal').classList.add('hidden');
        document.getElementById('qrPreviewContainer').classList.add('hidden');
        document.getElementById('qrQuantity').value = 1;
        document.querySelector('input[name="qrType"][value="enrollment"]').checked = true;
    }

    function closeQrGeneratorModalOnOutside(event) {
        if (event.target === event.currentTarget) {
            closeQrGeneratorModal();
        }
    }

    function closeQrDisplayModal() {
        document.getElementById('qrDisplayModal').classList.add('hidden');
    }

    function closeQrDisplayModalOnOutside(event) {
        if (event.target === event.currentTarget) {
            closeQrDisplayModal();
        }
    }

    async function generateQrCodes() {
        const quantity = parseInt(document.getElementById('qrQuantity').value);
        const type = document.querySelector('input[name="qrType"]:checked').value;
        
        if (quantity < 1 || quantity > 100) {
            showNotification('Please enter a quantity between 1 and 100', 'error');
            return;
        }

        try {
            // Show loading state
            const generateBtn = document.querySelector('#qrGeneratorModal button:first-child');
            const originalText = generateBtn.innerHTML;
            generateBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Generating...';
            generateBtn.disabled = true;

            // Check if QRCode library is properly loaded
            if (typeof QRCode === 'undefined') {
                throw new Error('QR Code library is not loaded. Please refresh the page and try again.');
            }

            if (typeof QRCode.toCanvas !== 'function') {
                throw new Error('QR Code library is not functioning properly. Please check the console for errors.');
            }

            const response = await fetch('{{ route("admin.qr-codes.generate") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    quantity: quantity,
                    type: type
                })
            });

            if (!response.ok) {
                const errorData = await response.json().catch(() => ({}));
                throw new Error(errorData.message || `Server returned ${response.status}: ${response.statusText}`);
            }

            const data = await response.json();

            if (data.success) {
                // Generate QR codes client-side
                await displayGeneratedQrCodes(data.qr_codes);
                closeQrGeneratorModal();
                showNotification(`${quantity} QR codes generated successfully!`, 'success');
                
                // Refresh QR history if the history modal is open
                if (!document.getElementById('qrHistoryModal').classList.contains('hidden')) {
                    console.log('Refreshing QR history after generation');
                    await loadQrHistory();
                }
            } else {
                throw new Error(data.message || 'Failed to generate QR codes');
            }
        } catch (error) {
            console.error('Error generating QR codes:', error);
            showNotification('Error generating QR codes: ' + error.message, 'error');
        } finally {
            // Restore button state
            const generateBtn = document.querySelector('#qrGeneratorModal button:first-child');
            generateBtn.innerHTML = '<i class="fas fa-qrcode mr-2"></i>Generate';
            generateBtn.disabled = false;
        }
    }

    async function displayGeneratedQrCodes(qrCodes) {
        const container = document.getElementById('qrCodesContainer');
        container.innerHTML = '';
        
        // Check if QRCode library is available
        if (typeof QRCode === 'undefined' || typeof QRCode.toCanvas !== 'function') {
            console.error('QRCode library not loaded properly');
            alert('QR Code library is not available. Please refresh the page and try again.');
            return;
        }
        
        for (const qrCode of qrCodes) {
            const qrDiv = document.createElement('div');
            qrDiv.className = 'bg-white p-4 rounded-lg shadow-md text-center cursor-pointer';
            qrDiv.dataset.selected = 'false';
            
            // Add click event for selection
            qrDiv.addEventListener('click', function(e) {
                // Don't trigger selection if clicking on download button
                if (e.target.tagName === 'BUTTON' || e.target.closest('button')) {
                    return;
                }
                
                const isSelected = this.dataset.selected === 'true';
                if (isSelected) {
                    this.classList.remove('border-2', 'border-blue-500', 'bg-blue-50');
                    this.dataset.selected = 'false';
                } else {
                    this.classList.add('border-2', 'border-blue-500', 'bg-blue-50');
                    this.dataset.selected = 'true';
                }
                updateQrCodesCount();
            });
            
            // Create QR code canvas
            const canvas = document.createElement('canvas');
            canvas.className = 'mx-auto mb-2';
            canvas.width = 150;
            canvas.height = 150;
            
            qrDiv.appendChild(canvas);
            
            // Add QR code info
            const infoDiv = document.createElement('div');
            infoDiv.className = 'text-xs text-gray-600 mt-2';
            infoDiv.innerHTML = `
                <div>ID: ${qrCode.id}</div>
                <div>Type: ${qrCode.type}</div>
                <div>Generated: ${new Date(qrCode.generated_at).toLocaleString()}</div>
            `;
            qrDiv.appendChild(infoDiv);
            
            // Add download button for individual QR code
            const downloadBtn = document.createElement('button');
            downloadBtn.className = 'mt-2 px-3 py-1 bg-blue-500 text-white text-xs rounded hover:bg-blue-600';
            downloadBtn.innerHTML = '<i class="fas fa-download mr-1"></i>Download';
            downloadBtn.onclick = (e) => {
                e.stopPropagation(); // Prevent triggering the div click event
                downloadQrCode(qrCode.data, qrCode.id);
            };
            qrDiv.appendChild(downloadBtn);
            
            container.appendChild(qrDiv);
            
            try {
                // Use only the unique pin as the QR code content
                const qrContent = qrCode.qr_content || qrCode.unique_pin;

                // Generate QR code using the correct API for the qrcode library
                await QRCode.toCanvas(canvas, qrContent, {
                    errorCorrectionLevel: 'H', // Set error correction level
                    margin: 1, // Set margin
                    width: 150, // Set width
                    color: {
                        dark: '#000000', // Dark color
                        light: '#ffffff' // Light color
                    }
                });
            } catch (error) {
                console.error('Error generating QR code:', error);
                // Show error message on the canvas
                const ctx = canvas.getContext('2d');
                ctx.fillStyle = '#f3f4f6';
                ctx.fillRect(0, 0, canvas.width, canvas.height);
                ctx.fillStyle = '#dc2626';
                ctx.font = '12px Arial';
                ctx.textAlign = 'center';
                ctx.fillText('QR Error', canvas.width / 2, canvas.height / 2);
            }
        }
        
        // Update count and show the display modal
        updateQrCodesCount();
        document.getElementById('qrDisplayModal').classList.remove('hidden');
    }

    function downloadQrCode(qrData, qrId) {
        const canvas = document.createElement('canvas');
        // Convert the QR code data object to a JSON string for encoding
        const qrDataString = JSON.stringify(qrData);
        QRCode.toCanvas(canvas, qrDataString, {
            errorCorrectionLevel: 'H', // Set error correction level
            margin: 2, // Set margin
            width: 300, // Set width
            color: {
                dark: '#000000', // Dark color
                light: '#ffffff' // Light color
            }
        }, function(error) {
            if (error) {
                console.error('Error generating download QR code:', error);
                return;
            }

            const link = document.createElement('a');
            link.download = `qr_code_${qrId}.png`;
            link.href = canvas.toDataURL('image/png');
            link.click();
        });
    }

    function downloadAllQrCodes() {
        const qrCanvases = document.querySelectorAll('#qrCodesContainer canvas');
        if (qrCanvases.length === 0) {
            alert('No QR codes to download');
            return;
        }

        // Create a zip file containing all QR codes
        alert('Preparing download... This may take a moment for ' + qrCanvases.length + ' QR codes.');
        
        // For simplicity, we'll download each QR code individually
        // In a production environment, you might want to use a zip library
        qrCanvases.forEach((canvas, index) => {
            const link = document.createElement('a');
            link.download = `qr_code_${index + 1}.png`;
            link.href = canvas.toDataURL('image/png');
            link.click();
        });
    }

    // QR Code Selection Functions
    function selectAllQrCodes() {
        const qrDivs = document.querySelectorAll('#qrCodesContainer > div');
        qrDivs.forEach(div => {
            div.classList.add('border-2', 'border-blue-500', 'bg-blue-50');
            div.dataset.selected = 'true';
        });
        updateQrCodesCount();
    }

    function deselectAllQrCodes() {
        const qrDivs = document.querySelectorAll('#qrCodesContainer > div');
        qrDivs.forEach(div => {
            div.classList.remove('border-2', 'border-blue-500', 'bg-blue-50');
            div.dataset.selected = 'false';
        });
        updateQrCodesCount();
    }

    function updateQrCodesCount() {
        const totalQrCodes = document.querySelectorAll('#qrCodesContainer > div').length;
        const selectedQrCodes = document.querySelectorAll('#qrCodesContainer > div[data-selected="true"]').length;
        document.getElementById('qrCodesCount').textContent = 
            `${selectedQrCodes} of ${totalQrCodes} QR codes selected`;
    }

    function downloadSelectedQrCodes() {
        const selectedQrDivs = document.querySelectorAll('#qrCodesContainer > div[data-selected="true"]');
        if (selectedQrDivs.length === 0) {
            alert('Please select at least one QR code to download');
            return;
        }

        alert(`Preparing download for ${selectedQrDivs.length} QR codes...`);
        
        selectedQrDivs.forEach((div, index) => {
            const canvas = div.querySelector('canvas');
            if (canvas) {
                const link = document.createElement('a');
                link.download = `qr_code_${index + 1}.png`;
                link.href = canvas.toDataURL('image/png');
                link.click();
            }
        });
    }

    function downloadAllQrCodes() {
        const qrCanvases = document.querySelectorAll('#qrCodesContainer canvas');
        if (qrCanvases.length === 0) {
            alert('No QR codes to download');
            return;
        }

        alert(`Preparing download for ${qrCanvases.length} QR codes...`);
        
        qrCanvases.forEach((canvas, index) => {
            const link = document.createElement('a');
            link.download = `qr_code_${index + 1}.png`;
            link.href = canvas.toDataURL('image/png');
            link.click();
        });
    }

    function printQrCodes() {
        const qrDivs = document.querySelectorAll('#qrCodesContainer > div');
        let printHtml = `
            <!DOCTYPE html>
            <html>
            <head>
                <title>QR Codes Print</title>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        margin: 20px;
                    }
                    .print-header {
                        text-align: center;
                        margin-bottom: 30px;
                    }
                    .qr-grid {
                        display: grid;
                        grid-template-columns: repeat(4, 1fr);
                        gap: 20px;
                        margin: 0 auto;
                        max-width: 1200px;
                    }
                    .qr-item {
                        text-align: center;
                        padding: 15px;
                        border: 1px solid #ddd;
                        border-radius: 8px;
                    }
                    .qr-item img {
                        max-width: 100%;
                        height: auto;
                    }
                    .qr-info {
                        margin-top: 10px;
                        font-size: 12px;
                        color: #666;
                    }
                    @media print {
                        .qr-grid {
                            grid-template-columns: repeat(4, 1fr) !important;
                        }
                    }
                </style>
            </head>
            <body>
                <div class="print-header">
                    <h1>Generated QR Codes</h1>
                    <p>Printed on ${new Date().toLocaleString()}</p>
                </div>
                <div class="qr-grid">
        `;

        qrDivs.forEach(div => {
            const canvas = div.querySelector('canvas');
            const infoDiv = div.querySelector('div.text-xs');
            let imgData = '';
            if (canvas) {
                imgData = canvas.toDataURL('image/png');
            }
            printHtml += `
                <div class="qr-item">
                    <img src="${imgData}" alt="QR Code" />
                    <div class="qr-info">${infoDiv ? infoDiv.innerHTML : ''}</div>
                </div>
            `;
        });

        printHtml += `
                </div>
            </body>
            </html>
        `;

        const printWindow = window.open('', '_blank');
        printWindow.document.open();
        printWindow.document.write(printHtml);
        printWindow.document.close();

        // Wait for images to load, then print
        printWindow.onload = function() {
            const images = printWindow.document.querySelectorAll('img');
            let loaded = 0;
            if (images.length === 0) {
                setTimeout(() => {
                    printWindow.print();
                    printWindow.close();
                }, 500);
                return;
            }
            images.forEach(img => {
                img.onload = img.onerror = function() {
                    loaded++;
                    if (loaded === images.length) {
                        setTimeout(() => {
                            printWindow.print();
                            printWindow.close();
                        }, 500);
                    }
                };
            });
        };
    }

    // QR History Modal Functions
    function openQrHistoryModal() {
        document.getElementById('qrHistoryModal').classList.remove('hidden');
        loadQrHistory();
    }

    function closeQrHistoryModal() {
        document.getElementById('qrHistoryModal').classList.add('hidden');
    }

    function closeQrHistoryModalOnOutside(event) {
        if (event.target === event.currentTarget) {
            closeQrHistoryModal();
        }
    }

    function closeQrDateModal() {
        document.getElementById('qrDateModal').classList.add('hidden');
    }

    function closeQrDateModalOnOutside(event) {
        if (event.target === event.currentTarget) {
            closeQrDateModal();
        }
    }

    async function loadQrHistory() {
        const container = document.getElementById('qrHistoryContainer');
        const loading = document.getElementById('qrHistoryLoading');
        const empty = document.getElementById('qrHistoryEmpty');
        
        // Check if container exists (required)
        if (!container) {
            console.error('QR History Container not found in loadQrHistory');
            return;
        }
        
        // Create missing elements if they don't exist
        if (!loading) {
            console.warn('Loading element not found in loadQrHistory, creating fallback');
            const loadingFallback = document.createElement('div');
            loadingFallback.id = 'qrHistoryLoading';
            loadingFallback.className = 'text-center py-8';
            loadingFallback.innerHTML = '<i class="fas fa-spinner fa-spin text-2xl text-gray-400 mb-2"></i><p class="text-gray-500">Loading QR code history...</p>';
            container.appendChild(loadingFallback);
        }
        
        if (!empty) {
            console.warn('Empty element not found in loadQrHistory, creating fallback');
            const emptyFallback = document.createElement('div');
            emptyFallback.id = 'qrHistoryEmpty';
            emptyFallback.className = 'text-center py-8 hidden';
            emptyFallback.innerHTML = '<i class="fas fa-qrcode text-4xl text-gray-300 mb-4"></i><h3 class="text-lg font-medium text-gray-900 mb-2">No QR codes found</h3><p class="text-gray-500">No QR codes have been generated yet.</p>';
            container.appendChild(emptyFallback);
        }
        
        // Get elements again (including any newly created ones)
        const finalLoading = document.getElementById('qrHistoryLoading');
        const finalEmpty = document.getElementById('qrHistoryEmpty');
        
        // Show loading state
        finalLoading.classList.remove('hidden');
        finalEmpty.classList.add('hidden');
        container.innerHTML = '';
        container.appendChild(finalLoading);

        try {
            console.log('Loading QR history from:', '{{ route("admin.qr-codes.by-date") }}');
            
            const response = await fetch('{{ route("admin.qr-codes.by-date") }}', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });

            console.log('Response status:', response.status);
            console.log('Response headers:', response.headers);
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();
            console.log('QR history data:', data);
            
            if (data.success && data.data && data.data.length > 0) {
                displayQrHistory(data.data);
            } else {
                console.log('No QR codes found or data structure issue');
                showEmptyState();
            }
        } catch (error) {
            console.error('Error loading QR history:', error);
            showEmptyState();
        }
    }

    function displayQrHistory(qrData) {
        console.log('Displaying QR history with data:', qrData);
        
        const container = document.getElementById('qrHistoryContainer');
        const loading = document.getElementById('qrHistoryLoading');
        const empty = document.getElementById('qrHistoryEmpty');
        
        // Debug: Log all elements found
        console.log('DOM elements found:', {
            container: container,
            loading: loading,
            empty: empty,
            containerExists: !!container,
            loadingExists: !!loading,
            emptyExists: !!empty
        });
        
        // Check if container exists (required)
        if (!container) {
            console.error('QR History Container not found');
            return;
        }
        
        // Create missing elements if they don't exist
        if (!loading) {
            console.warn('Loading element not found, creating fallback');
            const loadingFallback = document.createElement('div');
            loadingFallback.id = 'qrHistoryLoading';
            loadingFallback.className = 'text-center py-8';
            loadingFallback.innerHTML = '<i class="fas fa-spinner fa-spin text-2xl text-gray-400 mb-2"></i><p class="text-gray-500">Loading QR code history...</p>';
            container.appendChild(loadingFallback);
        }
        
        if (!empty) {
            console.warn('Empty element not found, creating fallback');
            const emptyFallback = document.createElement('div');
            emptyFallback.id = 'qrHistoryEmpty';
            emptyFallback.className = 'text-center py-8 hidden';
            emptyFallback.innerHTML = '<i class="fas fa-qrcode text-4xl text-gray-300 mb-4"></i><h3 class="text-lg font-medium text-gray-900 mb-2">No QR codes found</h3><p class="text-gray-500">No QR codes have been generated yet.</p>';
            container.appendChild(emptyFallback);
        }
        
        // Get elements again (including any newly created ones)
        const finalLoading = document.getElementById('qrHistoryLoading');
        const finalEmpty = document.getElementById('qrHistoryEmpty');
        
        // Hide loading and empty states
        finalLoading.classList.add('hidden');
        finalEmpty.classList.add('hidden');
        
        // Clear container
        container.innerHTML = '';
        
        if (!qrData || !Array.isArray(qrData)) {
            console.error('Invalid QR data structure:', qrData);
            showEmptyState();
            return;
        }
        
        qrData.forEach((dateGroup, index) => {
            console.log(`Processing date group ${index}:`, dateGroup);
            
            const dateDiv = document.createElement('div');
            dateDiv.className = 'bg-gray-50 rounded-lg p-4 border border-gray-200';
            
            const date = new Date(dateGroup.date);
            const formattedDate = date.toLocaleDateString('en-US', { 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric' 
            });
            
            dateDiv.innerHTML = `
                <div class="flex justify-between items-center cursor-pointer" onclick="viewQrCodesForDate('${dateGroup.date}')">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">${formattedDate}</h3>
                        <p class="text-sm text-gray-600">${dateGroup.count} QR codes generated</p>
                    </div>
                    <div class="flex items-center text-gray-400">
                        <i class="fas fa-chevron-right"></i>
                    </div>
                </div>
            `;
            
            container.appendChild(dateDiv);
        });
        
        console.log(`Added ${qrData.length} date groups to container`);
    }

    function showEmptyState() {
        const container = document.getElementById('qrHistoryContainer');
        const loading = document.getElementById('qrHistoryLoading');
        const empty = document.getElementById('qrHistoryEmpty');
        
        // Check if container exists (required)
        if (!container) {
            console.error('QR History Container not found in showEmptyState');
            return;
        }
        
        // Create missing elements if they don't exist
        if (!loading) {
            console.warn('Loading element not found in showEmptyState, creating fallback');
            const loadingFallback = document.createElement('div');
            loadingFallback.id = 'qrHistoryLoading';
            loadingFallback.className = 'text-center py-8';
            loadingFallback.innerHTML = '<i class="fas fa-spinner fa-spin text-2xl text-gray-400 mb-2"></i><p class="text-gray-500">Loading QR code history...</p>';
            container.appendChild(loadingFallback);
        }
        
        if (!empty) {
            console.warn('Empty element not found in showEmptyState, creating fallback');
            const emptyFallback = document.createElement('div');
            emptyFallback.id = 'qrHistoryEmpty';
            emptyFallback.className = 'text-center py-8';
            emptyFallback.innerHTML = '<i class="fas fa-qrcode text-4xl text-gray-300 mb-4"></i><h3 class="text-lg font-medium text-gray-900 mb-2">No QR codes found</h3><p class="text-gray-500">No QR codes have been generated yet.</p>';
            container.appendChild(emptyFallback);
        }
        
        // Get elements again (including any newly created ones)
        const finalLoading = document.getElementById('qrHistoryLoading');
        const finalEmpty = document.getElementById('qrHistoryEmpty');
        
        finalLoading.classList.add('hidden');
        finalEmpty.classList.remove('hidden');
        
        container.innerHTML = '';
        container.appendChild(finalEmpty);
    }

    async function viewQrCodesForDate(date) {
        // Close history modal and open date modal
        closeQrHistoryModal();
        
        const dateModal = document.getElementById('qrDateModal');
        if (!dateModal) {
            console.error('QR Date Modal not found');
            return;
        }
        dateModal.classList.remove('hidden');
        
        // Update modal title
        const modalTitle = document.getElementById('qrDateModalTitle');
        if (modalTitle) {
            const dateObj = new Date(date);
            const formattedDate = dateObj.toLocaleDateString('en-US', { 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric' 
            });
            modalTitle.textContent = `QR Codes for ${formattedDate}`;
        }
        
        // Load QR codes for the specific date
        await loadQrCodesForDate(date);
    }

    async function loadQrCodesForDate(date) {
        const container = document.getElementById('qrDateCodesContainer');
        const countElement = document.getElementById('qrDateCodesCount');
        
        // Check if elements exist
        if (!container) {
            console.error('QR Date Codes Container not found');
            return;
        }
        
        // Show loading state
        container.innerHTML = '<div class="col-span-full text-center py-8"><i class="fas fa-spinner fa-spin text-2xl text-gray-400"></i></div>';
        
        try {
            const response = await fetch(`{{ route("admin.qr-codes.for-date") }}?date=${date}`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });

            const data = await response.json();
            
            if (data.success) {
                await displayQrCodesForDate(data.qr_codes);
                if (countElement) {
                    countElement.textContent = `${data.qr_codes.length} QR codes`;
                }
            } else {
                container.innerHTML = '<div class="col-span-full text-center py-8 text-gray-500">No QR codes found for this date.</div>';
                if (countElement) {
                    countElement.textContent = '0 QR codes';
                }
            }
        } catch (error) {
            console.error('Error loading QR codes for date:', error);
            container.innerHTML = '<div class="col-span-full text-center py-8 text-red-500">Error loading QR codes.</div>';
            if (countElement) {
                countElement.textContent = '0 QR codes';
            }
        }
    }

    async function displayQrCodesForDate(qrCodes) {
        const container = document.getElementById('qrDateCodesContainer');
        
        // Check if container exists
        if (!container) {
            console.error('QR Date Codes Container not found in displayQrCodesForDate');
            return;
        }
        
        container.innerHTML = '';
        
        // Check if QRCode library is available
        if (typeof QRCode === 'undefined' || typeof QRCode.toCanvas !== 'function') {
            console.error('QRCode library not loaded properly');
            container.innerHTML = '<div class="col-span-full text-center py-8 text-red-500">QR Code library is not available. Please refresh the page and try again.</div>';
            return;
        }
        
        for (const qrCode of qrCodes) {
            const qrDiv = document.createElement('div');
            qrDiv.className = 'bg-white p-4 rounded-lg shadow-md text-center cursor-pointer';
            qrDiv.dataset.selected = 'false';
            
            // Add click event for selection
            qrDiv.addEventListener('click', function(e) {
                // Don't trigger selection if clicking on download button
                if (e.target.tagName === 'BUTTON' || e.target.closest('button')) {
                    return;
                }
                
                const isSelected = this.dataset.selected === 'true';
                if (isSelected) {
                    this.classList.remove('border-2', 'border-blue-500', 'bg-blue-50');
                    this.dataset.selected = 'false';
                } else {
                    this.classList.add('border-2', 'border-blue-500', 'bg-blue-50');
                    this.dataset.selected = 'true';
                }
                updateQrCodesCount();
            });
            
            // Create QR code canvas
            const canvas = document.createElement('canvas');
            canvas.className = 'mx-auto mb-2';
            canvas.width = 150;
            canvas.height = 150;
            
            qrDiv.appendChild(canvas);
            
            // Add QR code info
            const infoDiv = document.createElement('div');
            infoDiv.className = 'text-xs text-gray-600 mt-2';
            infoDiv.innerHTML = `
                <div>ID: ${qrCode.id}</div>
                <div>Type: ${qrCode.type}</div>
                <div>Generated: ${new Date(qrCode.generated_at).toLocaleString()}</div>
                <div>Status: ${qrCode.is_used ? 'Used' : 'Available'}</div>
            `;
            qrDiv.appendChild(infoDiv);
            
            // Add download button for individual QR code
            const downloadBtn = document.createElement('button');
            downloadBtn.className = 'mt-2 px-3 py-1 bg-blue-500 text-white text-xs rounded hover:bg-blue-600';
            downloadBtn.innerHTML = '<i class="fas fa-download mr-1"></i>Download';
            downloadBtn.onclick = (e) => {
                e.stopPropagation(); // Prevent triggering the div click event
                downloadQrCode(qrCode.data, qrCode.id);
            };
            qrDiv.appendChild(downloadBtn);
            
            container.appendChild(qrDiv);
            
            try {
                // Use only the unique pin as the QR code content
                const qrContent = qrCode.qr_content || qrCode.unique_pin;

                // Generate QR code using the correct API for the qrcode library
                await QRCode.toCanvas(canvas, qrContent, {
                    errorCorrectionLevel: 'H', // Set error correction level
                    margin: 1, // Set margin
                    width: 150, // Set width
                    color: {
                        dark: '#000000', // Dark color
                        light: '#ffffff' // Light color
                    }
                });
            } catch (error) {
                console.error('Error generating QR code:', error);
                // Show error message on the canvas
                const ctx = canvas.getContext('2d');
                ctx.fillStyle = '#f3f4f6';
                ctx.fillRect(0, 0, canvas.width, canvas.height);
                ctx.fillStyle = '#ef4444';
                ctx.font = '12px Arial';
                ctx.textAlign = 'center';
                ctx.fillText('Error', canvas.width / 2, canvas.height / 2 - 10);
                ctx.fillText('Generating', canvas.width / 2, canvas.height / 2 + 10);
            }
        }
    }

    // Function to save status changes for approved enrollments
    function saveStatus(id, tab) {
        const statusSelect = document.getElementById(`statusSelect-${tab}-${id}`);
        if (!statusSelect) {
            alert('Status select element not found.');
            return;
        }

        const newStatus = statusSelect.value;
        if (!newStatus) {
            alert('Please select a status.');
            return;
        }

        // Confirm the change
        if (!confirm(`Are you sure you want to change the status to "${newStatus}"?`)) {
            return;
        }

        // Send AJAX request to update status
        fetch(`/admin/enrollments/${id}/update-status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                status: newStatus
            })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Close the modal
                closeModal(id, tab);

                // Show success message
                showNotification('Status updated successfully!', 'success');

                // Optionally, update the UI to reflect the new status
                // For example, update the status cell in the table
                const row = document.querySelector(`#approvedTable tr[data-enrollment-id="${id}"]`);
                if (row) {
                    const statusCell = row.querySelector('td:nth-child(4) span');
                    if (statusCell) {
                        statusCell.textContent = newStatus.charAt(0).toUpperCase() + newStatus.slice(1);
                        if (newStatus === 'enrolled') {
                            statusCell.className = 'px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800';
                        } else {
                            statusCell.className = 'px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800';
                        }
                    }
                }
            } else {
                throw new Error(data.message || 'Failed to update status');
            }
        })
        .catch(error => {
            console.error('Error updating status:', error);
            showNotification('Error updating status: ' + error.message, 'error');
        });
    }
</script>

    <!-- Include Admin Profile Modal -->
    @include('Admin.Top.profile')

    <!-- Admin Enrollment Management Loading Functionality -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Wait for loading manager to be available
            const waitForLoadingManager = () => {
                if (typeof window.loadingManager !== 'undefined') {
                    initEnrollmentManagementLoading();
                } else {
                    setTimeout(waitForLoadingManager, 100);
                }
            };
            
            waitForLoadingManager();
        });

        function initEnrollmentManagementLoading() {
            // Add loading to approve/reject buttons
            const actionButtons = document.querySelectorAll('button[onclick*="updateStatus"]');
            actionButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    const action = this.textContent.trim().toLowerCase();
                    window.loadingManager.show('Processing ' + action, 'Please wait while we ' + action + ' the enrollment');
                });
            });

            // Add loading to search functionality
            const searchInput = document.querySelector('input[type="search"]');
            if (searchInput) {
                searchInput.addEventListener('input', function(e) {
                    if (this.value.length > 2) {
                        window.loadingManager.show('Searching', 'Please wait while we search enrollments');
                        // Hide loading after search completes
                        setTimeout(() => {
                            window.loadingManager.hide();
                        }, 1000);
                    }
                });
            }

            // Add loading to filter buttons
            const filterButtons = document.querySelectorAll('button[onclick*="filterByStatus"]');
            filterButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    const status = this.textContent.trim();
                    window.loadingManager.show('Filtering', 'Please wait while we filter by ' + status);
                    setTimeout(() => {
                        window.loadingManager.hide();
                    }, 500);
                });
            });

            // Add loading to export buttons
            const exportButtons = document.querySelectorAll('button[onclick*="export"]');
            exportButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    window.loadingManager.show('Exporting Data', 'Please wait while we prepare your export');
                });
            });

            // Add loading to QR code generation
            const qrButtons = document.querySelectorAll('button[onclick*="generateQR"]');
            qrButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    window.loadingManager.show('Generating QR Codes', 'Please wait while we generate QR codes');
                });
            });

            // Enhanced navigation loading
            const allLinks = document.querySelectorAll('a[href]');
            allLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    const href = this.getAttribute('href');
                    
                    // Skip if it's a hash link, external link, or same page
                    if (!href || href.startsWith('#') || href.startsWith('javascript:') || 
                        href.startsWith('mailto:') || href.startsWith('tel:') || 
                        this.getAttribute('target') === '_blank' ||
                        href === window.location.pathname) {
                        return;
                    }

                    // Skip if it's marked as no-loading
                    if (this.getAttribute('data-no-loading') === 'true' || 
                        this.classList.contains('no-loading')) {
                        return;
                    }

                    // Show loading for navigation
                    const linkText = this.textContent.trim() || 'Page';
                    window.loadingManager.show('Loading ' + linkText, 'Please wait while we navigate to ' + linkText);
                });
            });

            // Add loading for form submissions
            const forms = document.querySelectorAll('form');
            forms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    const action = this.getAttribute('action');
                    const method = this.getAttribute('method') || 'GET';
                    
                    // Skip if it's a GET form or has no action
                    if (method.toLowerCase() === 'get' || !action) {
                        return;
                    }

                    // Skip if it's marked as no-loading
                    if (this.getAttribute('data-no-loading') === 'true' || 
                        this.classList.contains('no-loading')) {
                        return;
                    }

                    // Show loading for form submission
                    window.loadingManager.show('Processing Form', 'Please wait while we process your request');
                });
            });
        }
    </script>

<form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
    @csrf
</form>

<!-- Loading System Integration -->
@include('partials.loading-integration')

</body>
</html>
