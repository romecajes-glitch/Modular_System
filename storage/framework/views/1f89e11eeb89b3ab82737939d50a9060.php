<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
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

        /* Global Blended Scrollbar Styles */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, rgba(59, 130, 246, 0.3) 0%, rgba(37, 99, 235, 0.4) 50%, rgba(29, 78, 216, 0.3) 100%);
            border-radius: 10px;
            border: 2px solid transparent;
            background-clip: content-box;
            transition: all 0.3s ease;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(180deg, rgba(59, 130, 246, 0.5) 0%, rgba(37, 99, 235, 0.6) 50%, rgba(29, 78, 216, 0.5) 100%);
            background-clip: content-box;
        }

        ::-webkit-scrollbar-thumb:active {
            background: linear-gradient(180deg, rgba(59, 130, 246, 0.7) 0%, rgba(37, 99, 235, 0.8) 50%, rgba(29, 78, 216, 0.7) 100%);
            background-clip: content-box;
        }

        ::-webkit-scrollbar-corner {
            background: transparent;
        }

        /* Firefox scrollbar */
        * {
            scrollbar-width: thin;
            scrollbar-color: rgba(59, 130, 246, 0.4) transparent;
        }

        /* Modal scrollbar styles */
        .modal-scrollbar::-webkit-scrollbar {
            width: 6px;
        }

        .modal-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .modal-scrollbar::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, rgba(59, 130, 246, 0.3) 0%, rgba(37, 99, 235, 0.4) 50%, rgba(29, 78, 216, 0.3) 100%);
            border-radius: 8px;
            border: 1px solid transparent;
            background-clip: content-box;
        }

        .modal-scrollbar::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(180deg, rgba(59, 130, 246, 0.5) 0%, rgba(37, 99, 235, 0.6) 50%, rgba(29, 78, 216, 0.5) 100%);
            background-clip: content-box;
        }

        /* Content area scrollbar */
        .content-scrollbar::-webkit-scrollbar {
            width: 8px;
        }

        .content-scrollbar::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.05);
            border-radius: 10px;
        }

        .content-scrollbar::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, rgba(59, 130, 246, 0.4) 0%, rgba(37, 99, 235, 0.5) 50%, rgba(29, 78, 216, 0.4) 100%);
            border-radius: 10px;
            border: 2px solid transparent;
            background-clip: content-box;
        }

        .content-scrollbar::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(180deg, rgba(59, 130, 246, 0.6) 0%, rgba(37, 99, 235, 0.7) 50%, rgba(29, 78, 216, 0.6) 100%);
            background-clip: content-box;
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

        /* Modal Animations */
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px) scale(0.95);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .animate-fade-in {
            animation: fadeIn 0.3s ease-out;
        }

        .animate-slide-up {
            animation: slideUp 0.4s ease-out;
        }

        /* Button hover effects */
        .btn-hover-lift {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .btn-hover-lift:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }

        /* Gradient text effects */
        .gradient-text {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Enrollment Blocking Modal Styles */
        #enrollmentBlockingModal {
            z-index: 9999 !important;
        }

        #enrollmentBlockingModal .bg-white {
            pointer-events: auto;
        }

        /* Disable all interactions when enrollment modal is active */
        .enrollment-blocked {
            pointer-events: none !important;
            user-select: none !important;
        }

        .enrollment-blocked * {
            pointer-events: none !important;
            user-select: none !important;
        }

        /* Ensure modal is NEVER affected by blocking styles */
        #enrollmentBlockingModal,
        #enrollmentBlockingModal.enrollment-blocked,
        #enrollmentBlockingModal .enrollment-blocked {
            pointer-events: auto !important;
            opacity: 1 !important;
            filter: none !important;
            user-select: auto !important;
        }

        /* Don't apply blocking to body - it affects everything */
        body.enrollment-blocked {
            opacity: 1 !important;
            filter: none !important;
        }

        body.enrollment-blocked * {
            opacity: 1 !important;
            filter: none !important;
        }

        /* Ensure modal and its content are always interactive and visible */
        #enrollmentBlockingModal {
            pointer-events: auto !important;
            opacity: 1 !important;
            filter: none !important;
            user-select: auto !important;
            z-index: 9999 !important;
            background: rgba(0, 0, 0, 0.5);
        }

        /* Override ALL blocking styles for modal content */
        #enrollmentBlockingModal *,
        #enrollmentBlockingModal *:before,
        #enrollmentBlockingModal *:after {
            pointer-events: auto !important;
            user-select: auto !important;
            opacity: 1 !important;
            filter: none !important;
            background: inherit !important;
            color: inherit !important;
        }

        /* Specific overrides for modal content */
        #enrollmentBlockingModal .bg-white {
            opacity: 1 !important;
            filter: none !important;
            pointer-events: auto !important;
            background: white !important;
        }

        #enrollmentBlockingModal .text-white {
            color: white !important;
        }

        #enrollmentBlockingModal .text-gray-800 {
            color: #1f2937 !important;
        }

        #enrollmentBlockingModal .text-gray-600 {
            color: #4b5563 !important;
        }

        #enrollmentBlockingModal .text-gray-500 {
            color: #6b7280 !important;
        }

        #enrollmentBlockingModal .text-blue-800 {
            color: #1e40af !important;
        }

        #enrollmentBlockingModal .text-red-600 {
            color: #dc2626 !important;
        }

        #enrollmentBlockingModal .text-yellow-600 {
            color: #d97706 !important;
        }

        #enrollmentBlockingModal .text-blue-600 {
            color: #2563eb !important;
        }

        /* Ensure logout button is always visible and functional */
        #enrollmentBlockingModal button {
            opacity: 1 !important;
            visibility: visible !important;
            display: flex !important;
            pointer-events: auto !important;
            background: inherit !important;
            color: inherit !important;
        }

        #enrollmentBlockingModal button:hover {
            opacity: 1 !important;
            transform: translateY(-1px) !important;
        }

        #enrollmentBlockingModal .bg-gradient-to-r {
            background: linear-gradient(to right, var(--tw-gradient-stops)) !important;
        }

        #enrollmentBlockingModal .from-gray-500 {
            --tw-gradient-from: #6b7280 !important;
        }

        #enrollmentBlockingModal .to-gray-600 {
            --tw-gradient-to: #4b5563 !important;
        }

        #enrollmentBlockingModal .hover\\:from-gray-600:hover {
            --tw-gradient-from: #4b5563 !important;
        }

        #enrollmentBlockingModal .hover\\:to-gray-700:hover {
            --tw-gradient-to: #374151 !important;
        }

        /* Specific logout button styling */
        #enrollmentLogoutButton {
            opacity: 1 !important;
            visibility: visible !important;
            display: flex !important;
            pointer-events: auto !important;
            background: linear-gradient(to right, #4b5563, #374151) !important;
            color: white !important;
            border: none !important;
            outline: none !important;
            font-weight: bold !important;
            font-size: 1rem !important;
            border-radius: 0.75rem !important;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05) !important;
        }

        #enrollmentLogoutButton:hover {
            background: linear-gradient(to right, #374151, #1f2937) !important;
            transform: translateY(-1px) !important;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04) !important;
        }

        #enrollmentLogoutButton:focus {
            outline: 2px solid #3b82f6 !important;
            outline-offset: 2px !important;
        }

        /* Enhanced modal animations */
        @keyframes modalSlideIn {
            from {
                opacity: 0;
                transform: scale(0.9) translateY(-20px);
            }
            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }

        #enrollmentBlockingModal {
            animation: modalSlideIn 0.3s ease-out;
        }

        /* Pulse animation for pending status */
        @keyframes pulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: 0.7;
            }
        }

        .animate-pulse {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
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
            <!-- Logo (Collapse Button) -->
            <button id="toggleSidebar" class="p-4 flex items-center justify-center border-b border-blue-800/50 w-full transition-all duration-300">
                <div class="bg-gradient-to-r from-blue-600 to-blue-500 p-2 rounded-xl shadow-lg">
                    <img src="<?php echo e(asset('pictures/logo.png')); ?>" alt="Logo" class="w-8 h-8 object-contain">
                </div>
                <span class="logo-text ml-3 font-bold text-xl text-white">Student Portal</span>
                <div class="ml-auto collapse-icon">
                    <i class="fas fa-chevron-left text-blue-300 transition-transform duration-300"></i>
                </div>
            </button>

            <!-- User Profile -->
            <div class="user-profile p-4 flex items-center border-b border-blue-800/50 transition-all duration-300">
                <?php if($enrollment && $enrollment->photo): ?>
                    <img src="<?php echo e(asset('storage/' . $enrollment->photo)); ?>" alt="Profile" class="h-12 w-12 rounded-full object-cover">
                <?php elseif($student && $student->photo): ?>
                    <img src="<?php echo e(asset('storage/' . $student->photo)); ?>" alt="Profile" class="h-12 w-12 rounded-full object-cover">
                <?php else: ?>
                    <div class="h-12 w-12 rounded-full bg-gradient-to-r from-blue-600 to-blue-400 flex items-center justify-center shadow-lg">
                        <i class="fas fa-user text-white text-lg"></i>
                    </div>
                <?php endif; ?>
                <div class="ml-3 user-details text-left">
                    <div class="font-semibold text-white"><?php echo e($student->name); ?></div>
                    <div class="text-xs text-blue-200">Student</div>
                </div>
            </div>

            <!-- Navigation -->
            <?php echo $__env->make('Student.partials.navigation', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        </div>
        
        <!-- Main Content -->
        <div class="content-area flex-1 overflow-y-auto content-scrollbar">
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
                                <?php if($enrollment && $enrollment->photo): ?>
                                    <img src="<?php echo e(asset('storage/' . $enrollment->photo)); ?>" alt="Profile" class="w-8 h-8 rounded-full object-cover">
                                <?php elseif($student && $student->photo): ?>
                                    <img src="<?php echo e(asset('storage/' . $student->photo)); ?>" alt="Profile" class="w-8 h-8 rounded-full object-cover">
                                <?php else: ?>
                                    <i class="fas fa-user text-white text-sm"></i>
                                <?php endif; ?>
                            </div>
                            <div class="hidden md:block text-left">
                                <p class="text-sm font-medium"><?php echo e(auth()->user()->name); ?></p>
                                <p class="text-xs opacity-75">Student</p>
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
                <!-- Alerts -->
                <?php if(session('success')): ?>
                    <div class="mb-6">
                        <div class="alert alert-success rounded-lg p-4 border-l-4 border-green-500 bg-green-50">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-green-500" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-green-800">
                                        <?php echo e(session('success')); ?>

                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Define variables for JavaScript -->
                <?php
                    $registrationFeePaid = app('App\Http\Controllers\StudentController')->checkRegistrationFeeStatus(auth()->user());
                    $registrationFeeAmount = $enrollment && $enrollment->program ? $enrollment->program->registration_fee : 0;
                    $enrollmentApproved = $enrollment && $enrollment->status === \App\Models\Enrollment::STATUS_APPROVED;
                ?>

                <!-- Enhanced Enrollment Status Notification Modal -->
                <?php if($enrollmentStatusMessage && $enrollmentStatusMessage['type'] !== 'success'): ?>
                    <!-- Modern Notification Modal -->
                    <div id="enrollmentNotificationModal" class="fixed inset-0 flex items-center justify-center z-50 p-4" style="background: rgba(0, 0, 0, 0.3); backdrop-filter: blur(4px);">
                        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md mx-auto transform transition-all duration-500 scale-100 max-h-[90vh] overflow-y-auto modal-scrollbar" style="box-shadow: 0 32px 64px -12px rgba(0, 0, 0, 0.25);">
                            <!-- Enhanced Modal Header -->
                            <div class="relative overflow-hidden rounded-t-3xl">
                                <?php if($enrollmentStatusMessage['type'] === 'error'): ?>
                                    <div class="bg-gradient-to-br from-blue-500 via-blue-600 to-blue-700 text-white p-6 relative">
                                        <div class="absolute inset-0 bg-gradient-to-br from-blue-400 to-blue-600 opacity-90"></div>
                                        <div class="absolute top-0 right-0 w-32 h-32 bg-white opacity-10 rounded-full -translate-y-16 translate-x-16"></div>
                                        <div class="absolute bottom-0 left-0 w-24 h-24 bg-white opacity-5 rounded-full translate-y-12 -translate-x-12"></div>
                                        <div class="relative flex items-center justify-center">
                                            <div class="bg-white bg-opacity-20 p-4 rounded-2xl mr-4 backdrop-blur-sm">
                                                <i class="fas fa-graduation-cap text-3xl"></i>
                                            </div>
                                            <div>
                                                <h3 class="text-2xl font-bold">Complete Your Enrollment</h3>
                                                <p class="text-blue-100 text-sm">Almost there! Just one more step to get started</p>
                                            </div>
                                        </div>
                                    </div>
                                <?php elseif($enrollmentStatusMessage['type'] === 'warning'): ?>
                                    <div class="bg-gradient-to-br from-amber-500 via-orange-500 to-yellow-600 text-white p-6 relative">
                                        <div class="absolute inset-0 bg-gradient-to-br from-amber-400 to-orange-500 opacity-90"></div>
                                        <div class="absolute top-0 right-0 w-32 h-32 bg-white opacity-10 rounded-full -translate-y-16 translate-x-16"></div>
                                        <div class="absolute bottom-0 left-0 w-24 h-24 bg-white opacity-5 rounded-full translate-y-12 -translate-x-12"></div>
                                        <div class="relative flex items-center justify-center">
                                            <div class="bg-white bg-opacity-20 p-4 rounded-2xl mr-4 backdrop-blur-sm">
                                                <i class="fas fa-bell text-3xl"></i>
                                            </div>
                                            <div>
                                                <h3 class="text-2xl font-bold">Enrollment Notification</h3>
                                                <p class="text-amber-100 text-sm">Important information about your enrollment</p>
                                            </div>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <div class="bg-gradient-to-br from-blue-500 via-indigo-600 to-purple-700 text-white p-6 relative">
                                        <div class="absolute inset-0 bg-gradient-to-br from-blue-400 to-indigo-600 opacity-90"></div>
                                        <div class="absolute top-0 right-0 w-32 h-32 bg-white opacity-10 rounded-full -translate-y-16 translate-x-16"></div>
                                        <div class="absolute bottom-0 left-0 w-24 h-24 bg-white opacity-5 rounded-full translate-y-12 -translate-x-12"></div>
                                        <div class="relative flex items-center justify-center">
                                            <div class="bg-white bg-opacity-20 p-4 rounded-2xl mr-4 backdrop-blur-sm">
                                                <i class="fas fa-clock text-3xl animate-pulse"></i>
                                            </div>
                                            <div>
                                                <h3 class="text-2xl font-bold">Enrollment Notification</h3>
                                                <p class="text-blue-100 text-sm">Important information about your enrollment</p>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <!-- Enhanced Modal Body -->
                            <div class="p-8">
                                <div class="text-center mb-8">
                                    <!-- Enhanced Icon -->
                                    <div class="mb-6">
                                        <?php if($enrollmentStatusMessage['type'] === 'error'): ?>
                                            <div class="bg-gradient-to-br from-blue-100 to-blue-200 rounded-full w-24 h-24 flex items-center justify-center mx-auto shadow-lg">
                                                <i class="fas fa-graduation-cap text-blue-600 text-4xl"></i>
                                            </div>
                                        <?php elseif($enrollmentStatusMessage['type'] === 'warning'): ?>
                                            <div class="bg-gradient-to-br from-amber-100 to-orange-200 rounded-full w-24 h-24 flex items-center justify-center mx-auto shadow-lg">
                                                <i class="fas fa-bell text-amber-600 text-4xl"></i>
                                            </div>
                                        <?php else: ?>
                                            <div class="bg-gradient-to-br from-blue-100 to-indigo-200 rounded-full w-24 h-24 flex items-center justify-center mx-auto shadow-lg">
                                                <i class="fas fa-clock text-blue-600 text-4xl animate-pulse"></i>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <!-- Enhanced Title -->
                                    <h4 class="text-3xl font-bold text-gray-800 mb-4">
                                        <?php if($enrollmentStatusMessage['type'] === 'error'): ?>
                                            Welcome to Your Program! 🎓
                                        <?php elseif($enrollmentStatusMessage['type'] === 'warning'): ?>
                                            Payment Pending
                                        <?php else: ?>
                                            Enrollment Under Review
                                        <?php endif; ?>
                                    </h4>
                                    
                                    <!-- Enhanced Message -->
                                    <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-2xl p-6 mb-6 border border-gray-200">
                                        <p class="text-gray-700 leading-relaxed text-lg">
                                            <?php echo e($enrollmentStatusMessage['message']); ?>

                                        </p>
                                    </div>
                                    
                                    <!-- Enhanced Info Box -->
                                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border-2 border-blue-200 rounded-2xl p-6 mb-8 shadow-sm">
                                        <div class="flex items-start">
                                            <div class="bg-blue-500 p-3 rounded-xl mr-4 shadow-sm">
                                                <i class="fas fa-lightbulb text-white text-xl"></i>
                                            </div>
                                            <div class="flex-1">
                                                <h5 class="text-blue-800 font-bold text-lg mb-2">What's Next?</h5>
                                                <p class="text-blue-700 text-sm leading-relaxed">
                                                    <?php if($enrollmentStatusMessage['type'] === 'error'): ?>
                                                        Once you complete your registration fee payment, you'll be officially enrolled and can start your learning journey with full access to all features!
                                                    <?php elseif($enrollmentStatusMessage['type'] === 'warning'): ?>
                                                        Complete your session payment to be officially enrolled in the program.
                                                    <?php else: ?>
                                                        Your enrollment is being reviewed by our administrators. You'll receive an email notification once approved.
                                                    <?php endif; ?>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Enhanced Action Buttons -->
                                <div class="space-y-4">
                                    <?php if($enrollmentStatusMessage['type'] === 'error' && isset($enrollmentStatusMessage['show_payment_button']) && $enrollmentStatusMessage['show_payment_button']): ?>
                                        <button id="payRegistrationFeeBtn" onclick="payRegistrationFee()" class="w-full bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-bold py-4 px-6 rounded-2xl transition-all duration-300 flex items-center justify-center shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                                            <i class="fas fa-credit-card mr-3 text-xl"></i>
                                            <span class="text-lg">Pay Registration Fee (₱<?php echo e(number_format($enrollmentStatusMessage['registration_fee'] ?? 0, 2)); ?>)</span>
                                        </button>
                                    <?php elseif($enrollmentStatusMessage['type'] === 'error' && isset($enrollmentStatusMessage['show_reapply_button']) && $enrollmentStatusMessage['show_reapply_button']): ?>
                                        <a href="<?php echo e(route('enrollment.reapply', $enrollment->id)); ?>" 
                                           class="w-full bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white font-bold py-4 px-6 rounded-2xl transition-all duration-300 flex items-center justify-center shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                                            <i class="fas fa-redo mr-3 text-xl"></i>
                                            <span class="text-lg">Re-apply for Enrollment</span>
                                        </a>
                                    <?php endif; ?>
                                    
                                    <button id="enrollmentLogoutButton" onclick="logout()" class="w-full bg-gradient-to-r from-gray-600 to-gray-700 hover:from-gray-700 hover:to-gray-800 text-white font-bold py-4 px-6 rounded-2xl transition-all duration-300 flex items-center justify-center shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                                        <i class="fas fa-sign-out-alt mr-3 text-xl"></i>
                                        <span class="text-lg">Log Out</span>
                                    </button>
                                </div>
                                
                                <!-- Enhanced Contact Info -->
                                <div class="mt-8 text-center">
                                    <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-2xl p-4 border border-gray-200">
                                        <div class="flex items-center justify-center text-gray-600">
                                            <div class="bg-gray-500 p-2 rounded-lg mr-3">
                                                <i class="fas fa-phone text-white"></i>
                                            </div>
                                            <span class="text-sm font-medium">Need help? Contact our support team</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Success Modal for Enrollment Confirmation -->
                <div id="enrollmentSuccessModal" class="fixed inset-0 flex items-center justify-center z-50 p-4 hidden" style="background: rgba(0, 0, 0, 0.3); backdrop-filter: blur(4px);">
                    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md mx-auto transform transition-all duration-500 scale-100 max-h-[90vh] overflow-y-auto modal-scrollbar" style="box-shadow: 0 32px 64px -12px rgba(0, 0, 0, 0.25);">
                        <!-- Success Modal Header -->
                        <div class="relative overflow-hidden rounded-t-3xl">
                            <div class="bg-gradient-to-br from-green-500 via-green-600 to-green-700 text-white p-6 relative">
                                <div class="absolute inset-0 bg-gradient-to-br from-green-400 to-green-600 opacity-90"></div>
                                <div class="absolute top-0 right-0 w-32 h-32 bg-white opacity-10 rounded-full -translate-y-16 translate-x-16"></div>
                                <div class="absolute bottom-0 left-0 w-24 h-24 bg-white opacity-5 rounded-full translate-y-12 -translate-x-12"></div>
                                <div class="relative flex items-center justify-center">
                                    <div class="bg-white bg-opacity-20 p-4 rounded-2xl mr-4 backdrop-blur-sm">
                                        <i class="fas fa-check-circle text-3xl"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-2xl font-bold">Congratulations! 🎉</h3>
                                        <p class="text-green-100 text-sm">You're now officially enrolled!</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Success Modal Body -->
                        <div class="p-8">
                            <div class="text-center mb-8">
                                <!-- Success Icon -->
                                <div class="mb-6">
                                    <div class="bg-gradient-to-br from-green-100 to-green-200 rounded-full w-24 h-24 flex items-center justify-center mx-auto shadow-lg">
                                        <i class="fas fa-graduation-cap text-green-600 text-4xl"></i>
                                    </div>
                                </div>
                                
                                <!-- Success Title -->
                                <h4 class="text-3xl font-bold text-gray-800 mb-4">
                                    Welcome to Your Program! 🎓
                                </h4>
                                
                                <!-- Success Message -->
                                <div class="bg-gradient-to-r from-green-50 to-green-100 rounded-2xl p-6 mb-6 border border-green-200">
                                    <p class="text-gray-700 leading-relaxed text-lg">
                                        Fantastic! Your registration fee payment has been processed successfully. You are now officially enrolled in your program and have full access to all student portal features.
                                    </p>
                                </div>
                                
                                <!-- Success Info Box -->
                                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border-2 border-blue-200 rounded-2xl p-6 mb-8 shadow-sm">
                                    <div class="flex items-start">
                                        <div class="bg-blue-500 p-3 rounded-xl mr-4 shadow-sm">
                                            <i class="fas fa-rocket text-white text-xl"></i>
                                        </div>
                                        <div class="flex-1">
                                            <h5 class="text-blue-800 font-bold text-lg mb-2">What You Can Do Now:</h5>
                                            <ul class="text-blue-700 text-sm leading-relaxed space-y-1">
                                                <li>• View your course schedule and sessions</li>
                                                <li>• Track your attendance and progress</li>
                                                <li>• Access learning materials and resources</li>
                                                <li>• Make additional payments as needed</li>
                                                <li>• Download certificates upon completion</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Success Action Button -->
                            <div class="space-y-4">
                                <button id="startLearningBtn" onclick="startLearning()" class="w-full bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-bold py-4 px-6 rounded-2xl transition-all duration-300 flex items-center justify-center shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                                    <i class="fas fa-play mr-3 text-xl"></i>
                                    <span class="text-lg">Start Learning Now!</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Welcome Banner -->
                <div class="bg-gradient-to-r from-blue-500 to-blue-700 rounded-xl p-6 text-white mb-6 shadow-lg">
                    <h2 class="text-2xl font-bold mb-2">Welcome back, <?php echo e(ucfirst(explode(' ', auth()->user()->name)[0])); ?>!</h2>
                    <p class="text-blue-100">Here's what's happening with your account today.</p>
                </div>


                <!-- Enhanced Program Card -->
                <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-xl shadow-lg p-6 mb-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold mb-1"><?php echo e($enrollment->program->name ?? 'No Program Enrolled'); ?></h3>
                            <p class="text-indigo-100"><?php echo e($enrollment->program->description ?? 'No Program Description Available'); ?></p>
                        </div>
                        <div class="bg-white/20 p-3 rounded-full">
                            <i class="fas fa-graduation-cap text-xl"></i>
                        </div>
                    </div>
                    <div class="mt-4 pt-4 border-t border-white/20">
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                            <div>
                            <p class="text-indigo-100">Start Date</p>
                            <p class="font-medium">
                                <?php if(isset($startDate)): ?>
                                    <?php echo e(\Carbon\Carbon::parse($startDate)->format('M d, Y')); ?>

                                <?php else: ?>
                                    N/A
                                <?php endif; ?>
                            </p>
                        </div>
                        <div>
                            <p class="text-indigo-100">Expected Graduation</p>
                            <p class="font-medium">
                                <?php if(isset($expectedGraduation)): ?>
                                    <?php echo e(\Carbon\Carbon::parse($expectedGraduation)->format('M d, Y')); ?>

                                <?php else: ?>
                                    N/A
                                <?php endif; ?>
                            </p>
                        </div>
                        <div>
                            <p class="text-indigo-100">Number of Sessions</p>
                            <p class="font-medium">
                                <?php if(isset($numberOfSessions)): ?>
                                    <?php echo e($numberOfSessions); ?>

                                <?php else: ?>
                                    N/A
                                <?php endif; ?>
                            </p>
                            </div>
                        </div>
                        <div class="mt-4">
                            <div class="flex justify-between text-sm mb-1">
                                <span>Progress</span>
                                <span><?php echo e($progressPercentage ?? 0); ?>% Complete</span>
                            </div>
                            <div class="w-full bg-white/20 rounded-full h-2">
                                <div class="bg-white h-2 rounded-full" style="width: <?php echo e($progressPercentage ?? 0); ?>%"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                    <!-- Enhanced Attendance Card -->
                    <div class="bg-white rounded-xl shadow-lg p-6 border-t-4 border-blue-500">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="p-3 rounded-xl bg-blue-100/50 text-blue-600 mr-4">
                                    <i class="fas fa-user-graduate text-xl"></i>
                                </div>
                                <div>
                                    <p class="text-gray-500 text-sm font-medium">Session Attendance</p>
                                    <h3 class="text-2xl font-bold text-gray-800">
                                        <?php echo e($attendance['attended_sessions'] ?? '--'); ?>/<?php echo e($attendance['total_sessions'] ?? '--'); ?>

                                    </h3>
                                </div>
                            </div>
                            <span class="text-sm font-medium text-blue-600"><?php echo e(isset($attendance['attendance_percentage']) ? $attendance['attendance_percentage'] . '%' : '--'); ?></span>
                        </div>
                        <div class="mt-4">
                            <div class="flex justify-between text-xs text-gray-500 mb-1">
                            <span>Attended: <?php echo e($attendance['attended_sessions'] ?? '--'); ?> sessions</span>
                            <span>Remaining: <?php echo e(isset($attendance['total_sessions'], $attendance['attended_sessions']) ? $attendance['total_sessions'] - $attendance['attended_sessions'] : '--'); ?> sessions</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="h-2 bg-gradient-to-r from-blue-400 to-blue-600 rounded-full" style="width: <?php echo e($attendance['attendance_percentage'] ?? 0); ?>%"></div>
                            </div>
                            <div class="mt-2 flex justify-between text-xs">
                            <span class="text-gray-500">Last attended: <?php echo e($attendance['last_attended'] ?? '--'); ?></span>
                            </div>
                        </div>
                    </div>

                    <!-- Enhanced Payments Card -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-green-100 text-green-600 mr-4">
                                    <i class="fas fa-money-bill-wave text-xl"></i>
                                </div>
                                <div>
                                    <p class="text-gray-500 text-sm">Payment Status</p>
                                    <h3 class="text-xl font-bold">$<?php echo e(number_format($payments['total_paid'] ?? 0)); ?> Paid</h3>
                                </div>
                            </div>
                            <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded-full capitalize"><?php echo e($payments['payment_status'] ?? 'current'); ?></span>
                        </div>
                        <div class="mt-4 space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Latest Payment Date:</span>
                                <span class="font-medium"><?php echo e($payments['payment_date'] ?? '--'); ?></span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Amount Paid:</span>
                                <span class="font-medium">$<?php echo e(number_format($payments['total_paid'] ?? 0)); ?></span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Payment Method:</span>
                                <span class="font-medium"><?php echo e($payments['payment_method'] ?? '--'); ?></span>
                            </div>
                        </div>
                            <button onclick="window.location.href='<?php echo e(route('student.payment')); ?>'" class="mt-3 w-full text-sm bg-green-50 hover:bg-green-100 text-green-600 py-1 px-3 rounded-lg transition duration-200 cursor-pointer">
                                Make Payment Via Gcash
                            </button>
                    </div>

                    <!-- Certificates Card -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-purple-100 text-purple-600 mr-4">
                                    <i class="fas fa-certificate text-xl"></i>
                                </div>
                                <div>
                                    <p class="text-gray-500 text-sm">Certificates</p>
                                    <h3 class="text-xl font-bold"><?php echo e($certificates['total_certificates'] ?? 0); ?> Earned</h3>
                                </div>
                            </div>
                            <?php if($certificates['is_eligible'] ?? false): ?>
                                <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded-full">Eligible</span>
                            <?php else: ?>
                                <span class="text-xs bg-gray-100 text-gray-800 px-2 py-1 rounded-full">Not Eligible</span>
                            <?php endif; ?>
                        </div>
                        <div class="mt-4">
                            <?php if($certificates['is_eligible'] ?? false): ?>
                                <p class="text-sm text-green-600 font-medium">You are eligible for a certificate!</p>
                                <p class="text-xs text-gray-500 mt-1">Attendance: <?php echo e($certificates['attendance_percentage'] ?? 0); ?>%</p>
                            <?php else: ?>
                                <p class="text-sm text-gray-600">
                                    <?php if(($certificates['attendance_percentage'] ?? 0) > 0): ?>
                                        <?php echo e(80 - ($certificates['attendance_percentage'] ?? 0)); ?>% more attendance needed
                                    <?php else: ?>
                                        Not yet eligible
                                    <?php endif; ?>
                                </p>
                                <p class="text-xs text-gray-500 mt-1">Attendance: <?php echo e($certificates['attendance_percentage'] ?? 0); ?>% / 100% required</p>
                            <?php endif; ?>
                            <button onclick="window.location.href='<?php echo e(route('student.certificate')); ?>'" class="mt-2 text-sm text-purple-600 hover:text-purple-800 font-medium cursor-pointer">View Certificates</button>
                        </div>
                    </div>

                    <!-- Enhanced Sessions Card -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600 mr-4">
                                    <i class="fas fa-calendar-alt text-xl"></i>
                                </div>
                                <div>
                                    <p class="text-gray-500 text-sm">Upcoming Sessions</p>
                                    <h3 class="text-xl font-bold"><?php echo e($thisWeekSessionsCount); ?> This Week</h3>
                                </div>
                            </div>
                            <span class="text-xs bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full">Active</span>
                        </div>
                        <div class="mt-4 space-y-2">
                            <?php if(count($upcomingSessions) > 0): ?>
                                <?php $__currentLoopData = $upcomingSessions->take(2); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $session): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="flex items-center text-sm">
                                        <div class="w-2 h-2 bg-yellow-500 rounded-full mr-2"></div>
                                        <span class="font-medium">
                                            <?php echo e(\Carbon\Carbon::parse($session->session_date)->format('D:')); ?>

                                        </span>
                                        <span class="ml-1 text-gray-600">
                                            <?php echo e($session->session_name ?? 'Session'); ?> 
                                            (<?php echo e(\Carbon\Carbon::parse($session->start_time)->format('g:i A')); ?> - 
                                            <?php echo e(\Carbon\Carbon::parse($session->end_time)->format('g:i A')); ?>)
                                        </span>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php if(count($upcomingSessions) > 2): ?>
                                    <div class="text-xs text-gray-500 mt-2">
                                        +<?php echo e(count($upcomingSessions) - 2); ?> more sessions
                                    </div>
                                <?php endif; ?>
                            <?php else: ?>
                                <div class="text-sm text-gray-500">
                                    No upcoming sessions scheduled
                                </div>
                            <?php endif; ?>
                        </div>
                        <button class="mt-3 w-full text-sm bg-yellow-50 hover:bg-yellow-100 text-yellow-600 py-1 px-3 rounded-lg transition duration-200">
                            View Full Schedule
                        </button>
                    </div>
                </div>

                <!-- Recent Activity Section -->
                <div class="bg-white rounded-lg shadow p-6 mb-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">Recent Activity</h3>
                        <button class="text-sm text-blue-600 hover:text-blue-800 font-medium">View All</button>
                    </div>
                    <div class="space-y-4">
                        <?php if(count($recentActivities) > 0): ?>
                            <?php $__currentLoopData = $recentActivities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="flex items-start">
                                    <?php if($activity['type'] === 'attendance'): ?>
                                        <div class="p-2 rounded-full bg-blue-100 text-blue-600 mr-3">
                                            <i class="fas fa-check-circle"></i>
                                        </div>
                                        <div>
                                            <p class="font-medium">Attendance marked</p>
                                            <p class="text-sm text-gray-500"><?php echo e($activity['description']); ?></p>
                                            <p class="text-xs text-gray-400 mt-1"><?php echo e($activity['date']); ?></p>
                                        </div>
                                    <?php elseif($activity['type'] === 'payment'): ?>
                                        <div class="p-2 rounded-full bg-green-100 text-green-600 mr-3">
                                            <i class="fas fa-money-bill-wave"></i>
                                        </div>
                                        <div>
                                            <p class="font-medium">Payment received</p>
                                            <p class="text-sm text-gray-500"><?php echo e($activity['description']); ?></p>
                                            <p class="text-xs text-gray-400 mt-1"><?php echo e($activity['date']); ?></p>
                                        </div>
                                    <?php elseif($activity['type'] === 'certificate'): ?>
                                        <div class="p-2 rounded-full bg-purple-100 text-purple-600 mr-3">
                                            <i class="fas fa-certificate"></i>
                                        </div>
                                        <div>
                                            <p class="font-medium">Certificate earned</p>
                                            <p class="text-sm text-gray-500"><?php echo e($activity['description']); ?></p>
                                            <p class="text-xs text-gray-400 mt-1"><?php echo e($activity['date']); ?></p>
                                        </div>
                                    <?php elseif($activity['type'] === 'session'): ?>
                                        <div class="p-2 rounded-full bg-orange-100 text-orange-600 mr-3">
                                            <i class="fas fa-calendar-plus"></i>
                                        </div>
                                        <div>
                                            <p class="font-medium">Session scheduled</p>
                                            <p class="text-sm text-gray-500"><?php echo e($activity['description']); ?></p>
                                            <p class="text-xs text-gray-400 mt-1"><?php echo e($activity['date']); ?></p>
                                        </div>
                                    <?php elseif($activity['type'] === 'info'): ?>
                                        <div class="p-2 rounded-full bg-gray-100 text-gray-600 mr-3">
                                            <i class="fas fa-info-circle"></i>
                                        </div>
                                        <div>
                                            <p class="font-medium">Information</p>
                                            <p class="text-sm text-gray-500"><?php echo e($activity['description']); ?></p>
                                            <p class="text-xs text-gray-400 mt-1"><?php echo e($activity['date']); ?></p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php else: ?>
                            <div class="text-center py-4">
                                <p class="text-gray-500">No recent activities found</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-lg font-semibold mb-4">Session Attendance</h3>
                        <p class="text-gray-600 mb-4">View and manage your class attendance records.</p>
                        <button onclick="window.location.href='<?php echo e(route('student.attendance')); ?>'" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg transition duration-200 cursor-pointer">
                            View Attendance
                        </button>
                    </div>
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-lg font-semibold mb-4">Payment Records</h3>
                        <p class="text-gray-600 mb-4">Check your payment history and upcoming dues.</p>
                        <button onclick="window.location.href='<?php echo e(route('student.payment')); ?>'" class="w-full bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-lg transition duration-200 cursor-pointer">
                            View Payments
                        </button>
                    </div>
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-lg font-semibold mb-4">Certificates</h3>
                        <p class="text-gray-600 mb-4">Download your earned certificates and view progress.</p>
                        <button onclick="window.location.href='<?php echo e(route('student.certificate')); ?>'" class="w-full bg-purple-600 hover:bg-purple-700 text-white py-2 px-4 rounded-lg transition duration-200 cursor-pointer">
                            View Certificates
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php $__env->startPush('scripts'); ?>
    <script>
        // student dropdown toggle
        document.getElementById('adminDropdown').addEventListener('click', function(e) {
            e.stopPropagation();
            document.getElementById('dropdownMenu').classList.toggle('hidden');
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function() {
            document.getElementById('dropdownMenu').classList.add('hidden');
        });
        // Toggle sidebar collapse
        document.getElementById('toggleSidebar').addEventListener('click', function () {
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
        
        // Set active nav item and auto-expand sidebar
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
                    // Only navigate if it's a different page
                    if (window.location.pathname !== url) {
                        window.location.href = url;
                    }
                }
            });
        });

        // Function to set active nav item based on current URL
        function setActiveNavItem() {
            const currentPath = window.location.pathname;
            navItems.forEach(item => {
                const url = item.getAttribute('data-url');
                if (url && currentPath === url) {
                    // Remove active class from all items
                    navItems.forEach(nav => nav.classList.remove('active-nav'));
                    // Add active class to current page item
                    item.classList.add('active-nav');
                }
            });
        }

        // Set active nav item on page load
        setActiveNavItem();
        
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
                contentArea.classList.add('ml-1');

                // Set the correct icon direction
                if (toggleIcon.classList.contains('fa-chevron-left')) {
                    toggleIcon.classList.remove('fa-chevron-left');
                    toggleIcon.classList.add('fa-chevron-right');
                }
            }

            // Show registration fee modal if it exists
            const modal = document.getElementById('registrationFeeModal');
            if (modal) {
                modal.style.display = 'flex';
            }

            // Auto-dismiss enrollment status alert if it's a success message
            autoDismissAlert();
        });

        // Registration fee payment functions
        function initiatePayMongoCheckout() {
            // Use AJAX to call the processPayment endpoint for registration fee
            const amount = <?php echo e($registrationFeeAmount); ?>;
            const email = '<?php echo e(auth()->user()->email); ?>';
            const sessionCount = 1; // Registration fee is for 1 session

            fetch('<?php echo e(route("payment.direct")); ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                },
                body: JSON.stringify({
                    amount: amount,
                    email: email,
                    session_count: sessionCount,
                    payment_type: 'registration'
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Hide the registration fee modal
                    const modal = document.getElementById('registrationFeeModal');
                    if (modal) {
                        modal.style.display = 'none';
                    }
                    // Reload the page or update UI to reflect enrollment status
                    location.reload();
                } else {
                    alert('Payment failed: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error processing payment:', error);
                alert('An error occurred while processing payment. Please try again.');
            });
        }

        function showOnsitePaymentMessage() {
            // Show the onsite payment modal
            document.getElementById('onsitePaymentModal').classList.remove('hidden');
        }

        function closeOnsiteModal() {
            // Hide the onsite payment modal
            document.getElementById('onsitePaymentModal').classList.add('hidden');
        }

        function contactAdmin() {
            // Open email client or redirect to contact page
            const subject = encodeURIComponent('Registration Fee Payment Inquiry');
            const body = encodeURIComponent('Hello,\n\nI would like to inquire about the registration fee payment process.\n\nThank you.');
            window.location.href = 'mailto:admin@school.com?subject=' + subject + '&body=' + body;
        }

        // Auto-dismiss enrollment status alert after 3 seconds (only for success messages)
        function autoDismissAlert() {
            const alertElement = document.getElementById('enrollmentStatusAlert');
            if (alertElement) {
                // Check if it's a success message
                const alertContent = alertElement.querySelector('.alert');
                if (alertContent && alertContent.classList.contains('alert-success')) {
                    setTimeout(() => {
                        dismissAlert();
                    }, 3000); // 3 seconds
                }
            }
        }

        // Manual dismiss function
        function dismissAlert() {
            const alertElement = document.getElementById('enrollmentStatusAlert');
            if (alertElement) {
                alertElement.style.opacity = '0';
                alertElement.style.transform = 'translateY(-20px)';
                setTimeout(() => {
                    alertElement.style.display = 'none';
                }, 500); // Wait for transition to complete
            }
        }

        // Logout function for enrollment blocking modal
        function logout() {
            document.getElementById('logout-form').submit();
        }

        // Pay registration fee function
        function payRegistrationFee() {
            const amount = <?php echo e($registrationFeeAmount); ?>;
            const email = '<?php echo e(auth()->user()->email); ?>';
            
            // Show loading state
            const payBtn = document.getElementById('payRegistrationFeeBtn');
            const originalText = payBtn.innerHTML;
            payBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-3 text-xl"></i><span class="text-lg">Processing Payment...</span>';
            payBtn.disabled = true;

            fetch('<?php echo e(route("payment.direct")); ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                },
                body: JSON.stringify({
                    amount: amount,
                    email: email,
                    session_count: 1,
                    payment_type: 'registration'
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success modal
                    showEnrollmentSuccessModal();
                } else {
                    // Show error message
                    alert('Payment failed: ' + (data.message || 'Please try again.'));
                    // Reset button
                    payBtn.innerHTML = originalText;
                    payBtn.disabled = false;
                }
            })
            .catch(error => {
                console.error('Payment error:', error);
                alert('Payment failed. Please try again.');
                // Reset button
                payBtn.innerHTML = originalText;
                payBtn.disabled = false;
            });
        }

        // Show enrollment success modal
        function showEnrollmentSuccessModal() {
            // Hide the notification modal
            const notificationModal = document.getElementById('enrollmentNotificationModal');
            if (notificationModal) {
                notificationModal.style.display = 'none';
            }
            
            // Show success modal
            const successModal = document.getElementById('enrollmentSuccessModal');
            if (successModal) {
                successModal.classList.remove('hidden');
                successModal.style.display = 'flex';
            }
        }

        // Start learning function
        function startLearning() {
            // Hide success modal
            const successModal = document.getElementById('enrollmentSuccessModal');
            if (successModal) {
                successModal.classList.add('hidden');
                successModal.style.display = 'none';
            }
            
            // Reload the page to update the student status
            window.location.reload();
        }

        // Prevent clicking outside the enrollment blocking modal
        document.addEventListener('DOMContentLoaded', function() {
            const enrollmentModal = document.getElementById('enrollmentBlockingModal');
            if (enrollmentModal) {
                console.log('Enrollment blocking modal found, applying blocking...');
                // Don't apply blocking class to body - it affects everything
                // Instead, apply blocking only to specific elements
                
                // Prevent clicks on the backdrop from closing the modal
                enrollmentModal.addEventListener('click', function(e) {
                    e.stopPropagation();
                    e.preventDefault();
                });
                
                // Prevent clicks on the modal content from bubbling up
                const modalContent = enrollmentModal.querySelector('.bg-white');
                if (modalContent) {
                    modalContent.addEventListener('click', function(e) {
                        e.stopPropagation();
                    });
                }
                
                // Disable all navigation and interactions outside the modal
                const sidebar = document.querySelector('.sidebar');
                const contentArea = document.querySelector('.content-area');
                
                if (sidebar) {
                    sidebar.classList.add('enrollment-blocked');
                    console.log('Sidebar blocked');
                }
                
                if (contentArea) {
                    contentArea.classList.add('enrollment-blocked');
                    console.log('Content area blocked');
                }
                
                // Also block any other interactive elements (excluding modal)
                const allButtons = document.querySelectorAll('button:not(#enrollmentBlockingModal button)');
                const allLinks = document.querySelectorAll('a:not(#enrollmentBlockingModal a)');
                const allInputs = document.querySelectorAll('input:not(#enrollmentBlockingModal input)');
                
                allButtons.forEach(btn => {
                    if (!enrollmentModal.contains(btn)) {
                        btn.classList.add('enrollment-blocked');
                    }
                });
                allLinks.forEach(link => {
                    if (!enrollmentModal.contains(link)) {
                        link.classList.add('enrollment-blocked');
                    }
                });
                allInputs.forEach(input => {
                    if (!enrollmentModal.contains(input)) {
                        input.classList.add('enrollment-blocked');
                    }
                });
                
                // Explicitly remove any blocking classes from modal content
                const modalElements = enrollmentModal.querySelectorAll('*');
                modalElements.forEach(element => {
                    element.classList.remove('enrollment-blocked');
                    element.style.pointerEvents = 'auto';
                });
                
                console.log('All blocking mechanisms applied successfully');
                
                // Disable keyboard shortcuts that might bypass the modal
                document.addEventListener('keydown', function(e) {
                    // Prevent F5, Ctrl+R, Ctrl+F5, etc.
                    if (e.key === 'F5' || (e.ctrlKey && e.key === 'r') || (e.ctrlKey && e.shiftKey && e.key === 'R')) {
                        e.preventDefault();
                        return false;
                    }
                    
                    // Prevent tab navigation outside modal
                    if (e.key === 'Tab') {
                        const focusableElements = enrollmentModal.querySelectorAll('button, a, input, select, textarea, [tabindex]:not([tabindex="-1"])');
                        const firstElement = focusableElements[0];
                        const lastElement = focusableElements[focusableElements.length - 1];
                        
                        if (e.shiftKey) {
                            if (document.activeElement === firstElement) {
                                lastElement.focus();
                                e.preventDefault();
                            }
                        } else {
                            if (document.activeElement === lastElement) {
                                firstElement.focus();
                                e.preventDefault();
                            }
                        }
                    }
                    
                    // Prevent Escape key from closing modal
                    if (e.key === 'Escape') {
                        e.preventDefault();
                        return false;
                    }
                });
                
                // Focus the first interactive element in the modal
                const firstButton = enrollmentModal.querySelector('button, a');
                if (firstButton) {
                    firstButton.focus();
                }
                
                // Debug: Check if logout button exists and is visible
                const logoutButton = document.getElementById('enrollmentLogoutButton');
                if (logoutButton) {
                    console.log('Logout button found:', logoutButton);
                    console.log('Logout button styles:', window.getComputedStyle(logoutButton));
                    logoutButton.style.opacity = '1';
                    logoutButton.style.visibility = 'visible';
                    logoutButton.style.display = 'flex';
                    logoutButton.style.pointerEvents = 'auto';
                    logoutButton.style.background = 'linear-gradient(to right, #6b7280, #4b5563)';
                    logoutButton.style.color = 'white';
                } else {
                    console.log('Logout button not found!');
                }
                
                // Global event prevention for all interactions outside modal
                document.addEventListener('click', function(e) {
                    if (!enrollmentModal.contains(e.target)) {
                        e.preventDefault();
                        e.stopPropagation();
                        e.stopImmediatePropagation();
                        return false;
                    }
                }, true);
                
                document.addEventListener('mousedown', function(e) {
                    if (!enrollmentModal.contains(e.target)) {
                        e.preventDefault();
                        e.stopPropagation();
                        e.stopImmediatePropagation();
                        return false;
                    }
                }, true);
                
                document.addEventListener('mouseup', function(e) {
                    if (!enrollmentModal.contains(e.target)) {
                        e.preventDefault();
                        e.stopPropagation();
                        e.stopImmediatePropagation();
                        return false;
                    }
                }, true);
                
                console.log('All blocking mechanisms applied successfully');
            } else {
                console.log('Enrollment blocking modal not found');
            }
        });
    </script>
    <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" class="hidden">
        <?php echo csrf_field(); ?>
    </form>
    
    <?php echo $__env->make('Student.Top.profile', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</body>
</html>
<?php /**PATH C:\Users\gucor\OneDrive\Documents\Herd\Modular_System\resources\views/Student/dashboard.blade.php ENDPATH**/ ?>