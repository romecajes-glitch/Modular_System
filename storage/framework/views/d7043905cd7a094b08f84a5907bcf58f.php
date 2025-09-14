<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate</title>
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

        /* Certificate Preview Modal Styles */
        .certificate-preview-bg {
            background: url('<?php echo e(asset('pictures/certificate.png')); ?>') center/contain no-repeat;
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            position: relative;
            font-family: 'Inria Serif', serif;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .certificate-preview-content {
            text-align: center;
            padding: 0px;
            width: 100%;
            max-width: 9in;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .preview-student-name {
            font-family: 'Satisfy', cursive;
            font-size: 38px;
            margin-top: 50px;
            margin-bottom: 10px;
            font-weight: bold;
            color: #b49958;
            text-transform: first-letter: uppercase;
            line-height: 1.2;
        }
        .preview-completion-text {
            font-size: 16px;
            margin-top: 2px;
            color: #7a0000;
        }

    </style>
    <link href="https://fonts.googleapis.com/css2?family=Inria+Serif:wght@400;700&family=Satisfy&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-100 font-sans">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <div class="sidebar bg-blue-900 text-white w-56 flex flex-col">
            <!-- Logo (Collapse Button) -->
            <button id="toggleSidebar" class="p-3 flex items-center justify-center border-b border-blue-800 w-full hover:bg-blue-800/80 transition-colors">
                <div class="bg-blue-800 p-1 rounded-lg">
                    <img src="<?php echo e(asset('pictures/logo.png')); ?>" alt="Logo" class="w-10 h-10 object-contain">
                </div>
                <span class="logo-text ml-3 font-bold text-xl">Student Portal</span>
                <div class="ml-auto">
                    <i class="fas fa-chevron-left text-blue-300"></i>
                </div>
            </button>

            <!-- User Profile -->
            <div class="user-profile p-4 flex items-center border-b border-blue-800">
                <?php if($enrollment && $enrollment->photo): ?>
                    <img src="<?php echo e(asset('storage/' . $enrollment->photo)); ?>" alt="Profile" class="h-12 w-12 rounded-full object-cover">
                <?php elseif($student && $student->photo): ?>
                    <img src="<?php echo e(asset('storage/' . $student->photo)); ?>" alt="Profile" class="h-12 w-12 rounded-full object-cover">
                <?php else: ?>
                    <div class="h-12 w-12 rounded-full bg-gradient-to-r from-blue-600 to-blue-400 flex items-center justify-center shadow">
                        <i class="fas fa-user text-white"></i>
                    </div>
                <?php endif; ?>
                <div class="ml-3 user-details">
                    <div class="font-medium text-white"><?php echo e($student->name); ?></div>
                    <div class="text-xs text-blue-200"><?php echo e($enrollment->program->name ?? 'No Program Enrolled'); ?></div>
                </div>
            </div>

            <!-- Navigation -->
            <?php echo $__env->make('Student.partials.navigation', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            
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
                        Certificates
                    </h2>
                </div>
                
                <div class="flex items-center space-x-6">
                    <div class="flex space-x-5">
                        <div class="relative group">
                            <button class="text-gray-500 hover:text-gray-700 transition-colors relative">
                                <i class="fas fa-bell text-xl"></i>
                                <span class="absolute -top-1 -right-1 h-4 w-4 rounded-full bg-red-500 text-white text-xs flex items-center justify-center">3</span>
                            </button>
                            <div class="hidden group-hover:block absolute right-0 mt-2 w-72 bg-white rounded-lg shadow-xl z-50 border border-gray-200">
                                <div class="p-3 border-b border-gray-200">
                                    <h4 class="font-medium text-gray-800">Notifications (3)</h4>
                                </div>
                                <div class="max-h-96 overflow-y-auto">
                                    <a href="#" class="block px-4 py-3 hover:bg-gray-50 border-b border-gray-100">
                                        <div class="flex items-start">
                                            <div class="bg-blue-100 p-2 rounded-full mr-3">
                                                <i class="fas fa-calendar-check text-blue-600"></i>
                                            </div>
                                            <div>
                                                <p class="font-medium text-sm">New session scheduled</p>
                                                <p class="text-xs text-gray-500 mt-1">Web Development - Tomorrow 10AM</p>
                                            </div>
                                        </div>
                                    </a>
                                    <a href="#" class="block px-4 py-3 hover:bg-gray-50 border-b border-gray-100">
                                        <div class="flex items-start">
                                            <div class="bg-green-100 p-2 rounded-full mr-3">
                                                <i class="fas fa-money-bill-wave text-green-600"></i>
                                            </div>
                                            <div>
                                                <p class="font-medium text-sm">Payment received</p>
                                                <p class="text-xs text-gray-500 mt-1">$500 for 2 Sessions</p>
                                            </div>
                                        </div>
                                    </a>
                                    <a href="#" class="block px-4 py-3 hover:bg-gray-50">
                                        <div class="flex items-start">
                                            <div class="bg-purple-100 p-2 rounded-full mr-3">
                                                <i class="fas fa-certificate text-purple-600"></i>
                                            </div>
                                            <div>
                                                <p class="font-medium text-sm">Certificate available</p>
                                                <p class="text-xs text-gray-500 mt-1">JavaScript Fundamentals</p>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="p-3 text-center border-t border-gray-200">
                                    <a href="#" class="text-sm font-medium text-blue-600 hover:text-blue-800">View All</a>
                                </div>
                            </div>
                        </div>

                        <div class="relative group">
                            <button class="text-gray-500 hover:text-gray-700 transition-colors relative">
                                <i class="fas fa-envelope text-xl"></i>
                                <span class="absolute -top-1 -right-1 h-4 w-4 rounded-full bg-blue-500 text-white text-xs flex items-center justify-center">2</span>
                            </button>
                            <div class="hidden group-hover:block absolute right-0 mt-2 w-72 bg-white rounded-lg shadow-xl z-50 border border-gray-200">
                                <div class="p-3 border-b border-gray-200">
                                    <h4 class="font-medium text-gray-800">Messages (2)</h4>
                                </div>
                                <div class="max-h-96 overflow-y-auto">
                                    <a href="#" class="block px-4 py-3 hover:bg-gray-50 border-b border-gray-100">
                                        <div class="flex items-start">
                                            <div class="bg-blue-100 p-2 rounded-full mr-3">
                                                <i class="fas fa-user-graduate text-blue-600"></i>
                                            </div>
                                            <div>
                                                <p class="font-medium text-sm">From: Advisor</p>
                                                <p class="text-xs text-gray-500 mt-1">About your course selection...</p>
                                            </div>
                                        </div>
                                    </a>
                                    <a href="#" class="block px-4 py-3 hover:bg-gray-50">
                                        <div class="flex items-start">
                                            <div class="bg-yellow-100 p-2 rounded-full mr-3">
                                                <i class="fas fa-exclamation-triangle text-yellow-600"></i>
                                            </div>
                                            <div>
                                                <p class="font-medium text-sm">System Notification</p>
                                                <p class="text-xs text-gray-500 mt-1">Upcoming maintenance...</p>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="p-3 text-center border-t border-gray-200">
                                    <a href="#" class="text-sm font-medium text-blue-600 hover:text-blue-800">View All Messages</a>
                                </div>
                            </div>
                        </div>
                    </div>

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
                            <a href="#" class="block px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                                <i class="fas fa-cog mr-3"></i>Settings
                            </a>
                            <a href="#" id="logoutButton" class="block px-4 py-3 text-sm text-gray-700 hover:bg-red-50 hover:text-red-600 transition-colors border-t border-gray-100">
                                <i class="fas fa-sign-out-alt mr-3"></i>Log Out
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Certificate Content -->
            <div class="p-6">
                <!-- Registration Fee Payment Modal -->
                <?php
                    $registrationFeePaid = app('App\Http\Controllers\StudentController')->checkRegistrationFeeStatus(auth()->user());
                    $registrationFeeAmount = $enrollment && $enrollment->program ? $enrollment->program->registration_fee : 0;
                    $enrollmentApproved = $enrollment && $enrollment->status === \App\Models\Enrollment::STATUS_APPROVED;
                ?>
                <?php if(!$registrationFeePaid && $registrationFeeAmount > 0 && $enrollmentApproved): ?>
                    <div id="registrationFeeModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                        <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
                            <div class="bg-red-600 text-white p-4 rounded-t-lg">
                                <div class="flex items-center">
                                    <i class="fas fa-exclamation-triangle text-2xl mr-3"></i>
                                    <h3 class="text-lg font-bold">Registration Fee Required</h3>
                                </div>
                            </div>
                            <div class="p-6">
                                <div class="text-center mb-6">
                                    <div class="bg-red-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                                        <i class="fas fa-lock text-red-600 text-2xl"></i>
                                    </div>
                                    <h4 class="text-xl font-semibold text-gray-800 mb-2">Access Blocked</h4>
                                    <p class="text-gray-600 mb-4">
                                        You must pay the registration fee of <span class="font-bold text-red-600">₱<?php echo e(number_format($registrationFeeAmount, 2)); ?></span>
                                        before you can access any features of the student portal.
                                    </p>
                                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
                                        <p class="text-sm text-yellow-800">
                                            <i class="fas fa-info-circle mr-2"></i>
                                            This is a mandatory requirement and cannot be bypassed.
                                            Please complete the payment to continue.
                                        </p>
                                    </div>
                                </div>
                                <div class="space-y-3">
                                    <button onclick="payRegistrationFee()" class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-4 rounded-lg transition duration-200 flex items-center justify-center">
                                        <i class="fas fa-credit-card mr-2"></i>
                                        Pay Registration Fee (₱<?php echo e(number_format($registrationFeeAmount, 2)); ?>)
                                    </button>
                                    <button onclick="contactAdmin()" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-lg transition duration-200 flex items-center justify-center">
                                        <i class="fas fa-envelope mr-2"></i>
                                        Contact Administrator
                                    </button>
                                </div>
                                <div class="mt-4 text-center">
                                    <p class="text-xs text-gray-500">
                                        After payment, you will have full access to attendance records, payment history, certificates, and all other features.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <?php
                    $certificates = \App\Models\Certificate::with(['enrollment.program'])
                        ->whereHas('enrollment', function($query) {
                            $query->where('student_id', auth()->user()->id);
                        })
                        ->latest()
                        ->get();
                ?>

                <?php if($certificates->count() > 0): ?>
                    <!-- Show Issued Certificates -->
                    <div class="max-w-6xl mx-auto">
                        <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden mb-8">
                            <div class="bg-gradient-to-r from-green-50 to-emerald-50 px-6 py-4 border-b border-gray-100">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                        <i class="fas fa-certificate text-green-600"></i>
                                    </div>
                                    <h2 class="text-lg font-semibold text-gray-800">Your Certificates</h2>
                                </div>
                            </div>
                            <div class="p-6">
                                <div class="grid gap-6">
                                    <?php $__currentLoopData = $certificates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $certificate): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg p-6 border border-blue-200">
                                        <div class="flex items-center justify-between">
                                            <div class="flex-1">
                                                <h3 class="text-xl font-bold text-gray-800 mb-2"><?php echo e($certificate->enrollment->program->name ?? 'Program Certificate'); ?></h3>
                                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                                                    <div>
                                                        <span class="font-semibold text-gray-600">Certificate Number:</span>
                                                        <p class="text-gray-800"><?php echo e($certificate->certificate_number); ?></p>
                                                    </div>
                                                    <div>
                                                        <span class="font-semibold text-gray-600">Issue Date:</span>
                                                        <p class="text-gray-800"><?php echo e(\Carbon\Carbon::parse($certificate->issue_date)->format('F j, Y')); ?></p>
                                                    </div>
                                                    <?php if($certificate->instructor_name): ?>
                                                    <div>
                                                        <span class="font-semibold text-gray-600">Instructor:</span>
                                                        <p class="text-gray-800"><?php echo e($certificate->instructor_name); ?></p>
                                                    </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            <div class="flex space-x-3 ml-6">
                                                <button onclick="viewCertificate(<?php echo e($certificate->id); ?>)" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center">
                                                    <i class="fas fa-eye mr-2"></i> View
                                                </button>
                                                <button onclick="downloadCertificate(<?php echo e($certificate->id); ?>)" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center">
                                                    <i class="fas fa-download mr-2"></i> Download
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php elseif($enrollment && $enrollment->isEligibleForCertificate()): ?>
                    <!-- Show Certificate if enrollment is completed but no certificate issued yet -->
                    <div class="max-w-4xl mx-auto bg-white rounded-xl shadow-md overflow-hidden">
                        <div class="p-8 text-center">
                            <div class="text-blue-500 mb-4">
                                <i class="fas fa-clock text-5xl"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-800 mb-2">Certificate Pending</h3>
                            <p class="text-gray-600">You have completed all requirements for your program. Your certificate is being processed and will be available soon.</p>
                            <div class="mt-6">
                                <div class="flex justify-between text-xs text-gray-500 mb-1">
                                    <span>Progress</span>
                                    <span>100% Complete</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="h-2 bg-gradient-to-r from-green-400 to-green-600 rounded-full" style="width: 100%"></div>
                                </div>
                                <p class="text-xs text-gray-500 mt-2">All requirements completed - Certificate processing</p>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <!-- Show Certificate Not Available if enrollment is not completed -->
                    <div class="max-w-2xl mx-auto bg-white rounded-xl shadow-md overflow-hidden p-8 text-center">
                        <div class="text-blue-500 mb-4">
                            <i class="fas fa-certificate text-5xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-2">Certificate Not Available</h3>
                        <p class="text-gray-600">You haven't completed the program yet. Complete all requirements to receive your certificate.</p>
                        <div class="mt-6">
                            <div class="flex justify-between text-xs text-gray-500 mb-1">
                                <span>Progress</span>
                                <span><?php echo e($certificates['attendance_percentage'] ?? 0); ?>% Complete</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="h-2 bg-gradient-to-r from-blue-400 to-blue-600 rounded-full" style="width: <?php echo e($certificates['attendance_percentage'] ?? 0); ?>%"></div>
                            </div>
                            <p class="text-xs text-gray-500 mt-2">Attendance: <?php echo e($certificates['attendance_percentage'] ?? 0); ?>% / 100% required</p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Certificate View Modal -->
            <div id="certificateModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
                <div class="relative mx-auto shadow-lg rounded-md bg-white" style="width: 12in; height: auto; max-width: 95vw; max-height: none; margin: 5vh auto;">
                    <div class="flex justify-between items-center p-4 bg-gray-100 border-b">
                        <h3 class="text-lg font-medium">Certificate</h3>
                        <button onclick="closeCertificateModal()" class="text-gray-500 hover:text-gray-700">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="certificate-preview-container" style="width: 100%; height: 8.5in; overflow: hidden;">
                        <div class="certificate-preview-bg" id="certificateToExport" style="width: 11in; height: 8.5in; margin: 0 0.5in;">
                            <div class="certificate-preview-content">
                                <h1 class="preview-student-name" id="modalStudentName">[Student Name]</h1>
                                <p class="preview-completion-text">Has successfully completed the <span id="modalProgramName"><strong>[Program Name]</strong></span></p>
                                <p class="preview-completion-text">modular training program at Bohol Northern Star College (BNSC), given this day on </p>
                                <p class="preview-completion-text" id="modalIssueDate">[Issue Date]</p>
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-end gap-2 p-4 bg-gray-100 border-t">
                        <button id="downloadPdfBtn" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 flex items-center gap-2">
                            <i class="fas fa-download"></i> Download as PDF
                        </button>
                        <button id="printBtn" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 flex items-center gap-2">
                            <i class="fas fa-print"></i> Print
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php $__env->startPush('scripts'); ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
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
        
        // Set active nav item
        const navItems = document.querySelectorAll('.nav-item');
        navItems.forEach(item => {
            item.addEventListener('click', function() {
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
        });

        // Registration fee payment functions
        function payRegistrationFee() {
            // Redirect to payment page with registration fee flag
            window.location.href = '<?php echo e(route("student.payment")); ?>?type=registration_fee';
        }

        function contactAdmin() {
            // Open email client or redirect to contact page
            const subject = encodeURIComponent('Registration Fee Payment Inquiry');
            const body = encodeURIComponent('Hello,\n\nI would like to inquire about the registration fee payment process.\n\nThank you.');
            window.location.href = 'mailto:admin@school.com?subject=' + subject + '&body=' + body;
        }

        // Certificate functions
        function viewCertificate(certificateId) {
            // Fetch certificate data and show modal
            fetch(`/admin/student-certificates/<?php echo e(auth()->user()->id); ?>`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const certificate = data.certificates.find(cert => cert.id === certificateId);
                        if (certificate) {
                            document.getElementById('modalStudentName').textContent = certificate.enrollment.user.name;
                            document.getElementById('modalProgramName').textContent = certificate.enrollment.program.name;
                            
                            // Format issue date
                            const issueDate = new Date(certificate.issue_date);
                            const formattedDate = issueDate.toLocaleDateString('en-US', { 
                                year: 'numeric', 
                                month: 'long', 
                                day: 'numeric' 
                            });
                            document.getElementById('modalIssueDate').textContent = formattedDate;
                            
                            document.getElementById('certificateModal').classList.remove('hidden');
                        }
                    }
                })
                .catch(error => {
                    console.error('Error fetching certificate:', error);
                    alert('Error loading certificate');
                });
        }

        function downloadCertificate(certificateId) {
            // Download certificate as PDF
            window.open(`/admin/certificates/${certificateId}/pdf`, '_blank');
        }

        function closeCertificateModal() {
            document.getElementById('certificateModal').classList.add('hidden');
        }

        // Download as PDF
        document.getElementById('downloadPdfBtn').addEventListener('click', function() {
            const element = document.getElementById('certificateToExport');
            const opt = {
                margin:       0,
                filename:     'certificate.pdf',
                image:        { type: 'jpeg', quality: 0.98 },
                html2canvas:  { scale: 2 },
                jsPDF:        { unit: 'in', format: 'letter', orientation: 'landscape' }
            };
            html2pdf().set(opt).from(element).save();
        });

        // Print
        document.getElementById('printBtn').addEventListener('click', function() {
            const printContents = document.getElementById('certificateToExport').outerHTML;
            const printWindow = window.open('', '', 'width=1100,height=850');
            printWindow.document.write(`
                <html>
                <head>
                    <title>Print Certificate</title>
                    <link href="https://fonts.googleapis.com/css2?family=Inria+Serif:wght@400;700&family=Satisfy&display=swap" rel="stylesheet">
                    <style>
                        body { margin:0; padding:0; }
                        .certificate-preview-bg { width:11in; height:8.5in; display:flex; justify-content:center; align-items:center; background: url('<?php echo e(asset('pictures/certificate.png')); ?>') center/contain no-repeat; font-family: 'Inria Serif', serif; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
                        .certificate-preview-content { text-align:center; width:100%; max-width:9in; }
                        .preview-student-name { font-family:'Satisfy',cursive; font-size:38px; margin-top:50px; margin-bottom:10px; font-weight:bold; color:#b49958; line-height:1.2; }
                        .preview-completion-text { font-size:16px; margin-top:2px; color:#7a0000; }
                    </style>
                </head>
                <body>${printContents}</body>
                </html>
            `);
            printWindow.document.close();
            printWindow.focus();
            setTimeout(() => {
                printWindow.print();
                printWindow.close();
            }, 500);
        });
    </script>
    <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" class="hidden">
        <?php echo csrf_field(); ?>
    </form>
    
    <?php echo $__env->make('Student.Top.profile', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</body>
</html>
<?php /**PATH C:\Users\gucor\OneDrive\Documents\Herd\Modular_System\resources\views/Student/certificate.blade.php ENDPATH**/ ?>