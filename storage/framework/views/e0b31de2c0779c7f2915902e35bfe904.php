<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Admin - Certificate</title>
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
        /* Tab Styles */
        .tab-button.active {
            border-bottom: 2px solid #3b82f6 !important;
            color: #2563eb !important;
        }
        .tab-button:not(.active) {
            border-bottom: 2px solid transparent !important;
            color: #6b7280 !important;
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
        
            <?php echo $__env->make('Admin.partials.navigation', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            
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
                    <div class="border-l h-8 border-gray-200"></div>

                    <div class="relative group">
                        <button id="adminDropdown" class="flex items-center focus:outline-none space-x-3 bg-gradient-to-r from-blue-500 to-purple-600 text-white px-4 py-2 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300">
                            <div class="h-8 w-8 rounded-full bg-white bg-opacity-20 flex items-center justify-center">
                                <?php if(auth()->user()->photo): ?>
                                    <img src="<?php echo e(asset('storage/' . auth()->user()->photo)); ?>" alt="Profile Photo" class="w-8 h-8 rounded-full object-cover">
                                <?php else: ?>
                                    <i class="fas fa-user text-white text-sm"></i>
                                <?php endif; ?>
                            </div>
                            <div class="hidden md:block text-left">
                                <p class="text-sm font-medium"><?php echo e(auth()->user()->name); ?></p>
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

            <!-- Certificate Content -->
            <div class="p-6">
                <!-- Enhanced Certificate Management Section -->
                <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-4 border-b border-gray-100">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-certificate text-blue-600"></i>
                                </div>
                                <h2 class="text-lg font-semibold text-gray-800">Certificate Management</h2>
                            </div>
                            <button onclick="console.log('Button clicked'); openStudentRecordsModal();" class="inline-flex items-center px-4 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-gradient-to-r from-purple-600 to-purple-700 hover:from-purple-700 hover:to-purple-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-all duration-200">
                                <i class="fas fa-users mr-2"></i>View Student Records
                            </button>
                        </div>
                    </div>
                    <div class="p-6">
                        <!-- Enhanced Tabs -->
                        <div class="border-b border-gray-200 mb-6">
                            <nav class="-mb-px flex space-x-8">
                                <button id="pendingTab" class="tab-button border-b-2 border-blue-500 py-3 px-4 text-sm font-semibold text-blue-600 active bg-blue-50 rounded-t-lg">
                                    <i class="fas fa-clock mr-2"></i>Pending Certificates
                                </button>
                                <button id="issuedTab" class="tab-button border-b-2 border-transparent py-3 px-4 text-sm font-semibold text-gray-500 hover:text-gray-700 hover:border-gray-300 hover:bg-gray-50 rounded-t-lg">
                                    <i class="fas fa-check-circle mr-2"></i>Issued Certificates
                                </button>
                            </nav>
                        </div>

                    <!-- Tab Content -->
                    <div id="pendingContent" class="tab-content">
                        <!-- Enhanced Search and Filter -->
                        <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg p-4 mb-6">
                            <div class="flex flex-col md:flex-row justify-between gap-4">
                                <div class="flex-1">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Search Students</label>
                                    <div class="relative">
                                        <input type="text" id="searchInput" class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" placeholder="Search by name or ID">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-search text-gray-400"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="w-full md:w-48">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Program</label>
                                    <select id="programFilter" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                        <option value="">All Programs</option>
                                        <?php $__currentLoopData = \App\Models\Program::all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $program): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($program->name); ?>"><?php echo e($program->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <!-- Enhanced Student List -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white border border-gray-200 rounded-lg overflow-hidden" id="pendingTable">
                                <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                                    <tr>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider border-b border-gray-200">Name</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider border-b border-gray-200">Program</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider border-b border-gray-200">Certificate Issued</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider border-b border-gray-200">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <?php $__currentLoopData = $eligibleStudents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $enrollment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr class="hover:bg-gray-50 transition-colors" data-name="<?php echo e($enrollment->user->name); ?>" data-id="<?php echo e($enrollment->user->student_id); ?>" data-program="<?php echo e($enrollment->program->name ?? 'No Program'); ?>" data-year="<?php echo e(\Carbon\Carbon::parse($enrollment->completion_date)->year); ?>" data-issued="false">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                                    <i class="fas fa-user text-blue-600 text-sm"></i>
                                                </div>
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900"><?php echo e($enrollment->user->name); ?></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <i class="fas fa-graduation-cap mr-1"></i><?php echo e($enrollment->program->name ?? 'No Program'); ?>

                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <input type="checkbox" class="certificate-checkbox rounded border-gray-300 text-blue-600 focus:ring-blue-500" data-id="<?php echo e($enrollment->user->student_id); ?>">
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <button class="inline-flex items-center px-4 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 generate-btn"
                                                data-enrollment-id="<?php echo e($enrollment->id); ?>"
                                                data-name="<?php echo e($enrollment->user->name); ?>"
                                                data-program="<?php echo e($enrollment->program->name ?? 'No Program'); ?>"
                                                data-date="<?php echo e($enrollment->completion_date); ?>"
                                                data-generated="false">
                                                <i class="fas fa-certificate mr-2"></i>Generate Certificate
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                            <div class="mt-4">
                                <?php echo e($eligibleStudents->links()); ?>

                            </div>
                        </div>
                    </div>

                    <!-- Issued Certificates Tab Content -->
                    <div id="issuedContent" class="tab-content hidden">
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white border border-gray-200 rounded-lg overflow-hidden" id="issuedTable">
                                <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                                    <tr>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider border-b border-gray-200">Name</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider border-b border-gray-200">Program</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider border-b border-gray-200">Issue Date</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider border-b border-gray-200">Instructor</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider border-b border-gray-200">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200" id="issuedTableBody">
                                    <?php $__currentLoopData = $issuedCertificates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $certificate): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                                    <i class="fas fa-user-graduate text-green-600 text-sm"></i>
                                                </div>
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900"><?php echo e($certificate->enrollment->user->name); ?></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                <i class="fas fa-graduation-cap mr-1"></i><?php echo e($certificate->enrollment->program->name); ?>

                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?php echo e(\Carbon\Carbon::parse($certificate->issue_date)->format('M d, Y')); ?>

                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?php echo e($certificate->instructor_name ?? 'N/A'); ?>

                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex space-x-2">
                                                <button onclick="showCertificatePreview(<?php echo e($certificate->id); ?>)" class="inline-flex items-center px-3 py-2 border border-transparent text-xs leading-4 font-medium rounded-md text-white bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                                                    <i class="fas fa-eye mr-1"></i>View
                                                </button>
                                                <button onclick="markCertificateAsDone(<?php echo e($certificate->id); ?>)" class="inline-flex items-center px-3 py-2 border border-transparent text-xs leading-4 font-medium rounded-md text-white bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200">
                                                    <i class="fas fa-check mr-1"></i>Mark as Done
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                            <div class="mt-4">
                                <?php echo e($issuedCertificates->links()); ?>

                            </div>
                        </div>
                    </div>

                    <!-- Enhanced Certificate Preview Modal (hidden by default) -->
                    <div id="previewModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" onclick="closePreviewModalOnOutside(event)">
                        <div class="relative mx-auto shadow-xl rounded-xl bg-white" style="width: 12in; height: auto; max-width: 95vw; max-height: none; margin: 5vh auto;" onclick="event.stopPropagation()">
                            <div class="flex justify-between items-center p-6 bg-gradient-to-r from-green-50 to-emerald-50 border-b border-gray-200">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                        <i class="fas fa-eye text-green-600"></i>
                                    </div>
                                    <h3 class="text-lg font-semibold text-gray-800">Certificate Preview</h3>
                                </div>
                                <button onclick="document.getElementById('previewModal').classList.add('hidden')" class="text-gray-500 hover:text-gray-700 transition-colors">
                                    <i class="fas fa-times text-lg"></i>
                                </button>
                            </div>
                            <div class="certificate-preview-container" style="width: 100%; height: 8.5in; overflow: hidden;">
                                <div class="certificate-preview-bg" id="certificateToExport" style="width: 11in; height: 8.5in; margin: 0 0.5in;">
                                    <div class="certificate-preview-content">
                                        <h1 class="preview-student-name" id="previewStudentName">[Student Name]</h1>
                                        <p class="preview-completion-text">Has successfully completed the <span id="previewProgramName"><strong>[Program Name]</strong></span></p>
                                        <p class="preview-completion-text">modular training program at Bohol Northern Star College (BNSC), given this day on </p>
                                        <p class="preview-completion-text" id="previewCompletionDate">[Completion Date]</p>
                                    </div>
                                </div>
                            </div>
                            <div class="flex justify-end gap-4 p-6 bg-gradient-to-r from-gray-50 to-gray-100 border-t border-gray-200">
                                <button id="downloadPdfBtn" class="inline-flex items-center px-6 py-3 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                                    <i class="fas fa-download mr-2"></i>Download as PDF
                                </button>
                                <button id="printBtn" class="inline-flex items-center px-6 py-3 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200">
                                    <i class="fas fa-print mr-2"></i>Print
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Enhanced Certificate Generation Modal -->
            <div id="generateCertificateModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" onclick="closeGenerateModalOnOutside(event)">
                <div class="relative mx-auto shadow-xl rounded-xl bg-white max-w-lg w-full mx-4 mt-20" onclick="event.stopPropagation()">
                    <div class="flex justify-between items-center p-6 bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-gray-200">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-certificate text-blue-600"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-800">Generate Certificate</h3>
                        </div>
                        <button onclick="closeGenerateModal()" class="text-gray-500 hover:text-gray-700 transition-colors">
                            <i class="fas fa-times text-lg"></i>
                        </button>
                    </div>
                    <form id="generateCertificateForm" class="p-6">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">
                        <div class="mb-6">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Student Name</label>
                            <input type="text" id="modalStudentName" class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 text-gray-600" readonly>
                        </div>
                        <div class="mb-6">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Program</label>
                            <input type="text" id="modalProgramName" class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 text-gray-600" readonly>
                        </div>
                        <div class="mb-6">
                            <label for="issueDate" class="block text-sm font-semibold text-gray-700 mb-2">Certificate Issue Date</label>
                            <input type="date" id="issueDate" name="issue_date" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        </div>
                        <div class="mb-6">
                            <label for="instructorName" class="block text-sm font-semibold text-gray-700 mb-2">Instructor Name</label>
                            <select id="instructorName" name="instructor_name" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <option value="">Select Instructor</option>
                                <?php $__currentLoopData = $instructors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $instructor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($instructor->name); ?>"><?php echo e($instructor->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="flex justify-end space-x-4 pt-4">
                            <button type="button" onclick="closeGenerateModal()" class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-all duration-200">
                                <i class="fas fa-times mr-2"></i>Cancel
                            </button>
                            <button type="submit" id="generateSubmitBtn" class="px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white rounded-lg shadow-lg hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200 transform hover:-translate-y-0.5">
                                <i class="fas fa-certificate mr-2" id="generateIcon"></i>
                                <span id="generateText">Generate Certificate</span>
                                <i class="fas fa-spinner fa-spin hidden ml-2" id="generateSpinner"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php $__env->startPush('scripts'); ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script>
        // Helper function to capitalize first letter of each word
        function toTitleCase(str) {
            return str.replace(/\w\S*/g, function(txt){
                return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
            });
        }

        // Admin dropdown toggle
        document.getElementById('adminDropdown').addEventListener('click', function(e) {
            e.stopPropagation();
            document.getElementById('dropdownMenu').classList.toggle('hidden');
        });
        document.addEventListener('click', function() {
            document.getElementById('dropdownMenu').classList.add('hidden');
        });
        document.getElementById('toggleSidebar').addEventListener('click', function() {
            const sidebar = document.querySelector('.sidebar');
            const contentArea = document.querySelector('.content-area');
            sidebar.classList.toggle('sidebar-collapsed');
            contentArea.classList.toggle('ml-1');
            if (sidebar.classList.contains('sidebar-collapsed')) {
                localStorage.setItem('sidebar-collapsed', 'true');
            } else {
                localStorage.setItem('sidebar-collapsed', 'false');
            }
            const icon = this.querySelector('i');
            icon.classList.toggle('fa-chevron-left');
            icon.classList.toggle('fa-chevron-right');
        });
        document.getElementById('mobileMenuButton').addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('hidden');
        });
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
        handleResize();

        // Tab switching functionality
        document.getElementById('pendingTab').addEventListener('click', function() {
            document.getElementById('pendingContent').classList.remove('hidden');
            document.getElementById('issuedContent').classList.add('hidden');
            document.getElementById('pendingTab').classList.add('active');
            document.getElementById('issuedTab').classList.remove('active');
        });

        document.getElementById('issuedTab').addEventListener('click', function() {
            document.getElementById('pendingContent').classList.add('hidden');
            document.getElementById('issuedContent').classList.remove('hidden');
            document.getElementById('issuedTab').classList.add('active');
            document.getElementById('pendingTab').classList.remove('active');
        });

        // Generate Certificate Button
        document.querySelectorAll('.generate-btn').forEach(btn => {
            console.log('Adding event listener to button:', btn);
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                console.log('Generate button clicked:', this);
                const isGenerated = this.dataset.generated === 'true';

                if (!isGenerated) {
                    // Show generation modal
                    openGenerateModal(this);
                } else {
                    // Second click: Show preview
                    showCertificatePreview(this);
                }
            });
        });

        // Certificate checkbox functionality
        document.querySelectorAll('.certificate-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const row = this.closest('tr');
                if (this.checked) {
                    moveToIssuedTab(row);
                }
            });
        });

        // Function to show certificate preview
        function showCertificatePreview(btnOrId) {
            // Check if it's a button element or certificate ID
            if (typeof btnOrId === 'number' || typeof btnOrId === 'string') {
                // It's a certificate ID, fetch the certificate data
                fetch(`/admin/certificate-details/${btnOrId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            document.getElementById('previewStudentName').textContent = toTitleCase(data.certificate.student_name);
                            document.getElementById('previewProgramName').textContent = data.certificate.program_name;
                            
                            // Format the issue date
                            const issueDate = new Date(data.certificate.issue_date);
                            const options = { year: 'numeric', month: 'long', day: 'numeric' };
                            const formattedDate = issueDate.toLocaleDateString('en-US', options);
                            
                            document.getElementById('previewCompletionDate').textContent = formattedDate;
                            document.getElementById('previewModal').classList.remove('hidden');
                        } else {
                            alert('Error loading certificate details');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Error loading certificate details');
                    });
            } else {
                // It's a button element, use the existing logic
                document.getElementById('previewStudentName').textContent = toTitleCase(btnOrId.dataset.name);
                document.getElementById('previewProgramName').textContent = btnOrId.dataset.program;

                // Check if certificate date is filled, otherwise use original completion date
                const certificateDateInput = document.getElementById('certificateDate');
                let rawDate = certificateDateInput ? certificateDateInput.value : btnOrId.dataset.date;
                let formattedDate = rawDate;

                if (rawDate) {
                    // Handles both 'YYYY-MM-DD' and 'YYYY-MM-DD HH:MM:SS'
                    const datePart = rawDate.split(' ')[0];
                    const parts = datePart.split('-');
                    if (parts.length === 3) {
                        // Month is 0-based in JS Date
                        const dateObj = new Date(parts[0], parts[1] - 1, parts[2]);
                        if (!isNaN(dateObj)) {
                            const options = { year: 'numeric', month: 'long', day: 'numeric' };
                            formattedDate = dateObj.toLocaleDateString('en-US', options);
                        }
                    }
                }

                document.getElementById('previewCompletionDate').textContent = formattedDate;
                document.getElementById('previewModal').classList.remove('hidden');
            }
        }

        // Function to move row to issued tab
        function moveToIssuedTab(row) {
            // Instead of moving the row, we'll refresh the page to show the updated issued certificates
            // This ensures we get the latest data from the database
            window.location.reload();
        }

        // Function to show success message
        function showSuccessMessage(message) {
            const successDiv = document.createElement('div');
            successDiv.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
            successDiv.textContent = message;
            document.body.appendChild(successDiv);

            setTimeout(() => {
                successDiv.remove();
            }, 3000);
        }

        // Function to open generate certificate modal
        function openGenerateModal(btn) {
            console.log('Opening generate modal for button:', btn);
            console.log('Button dataset:', btn.dataset);
            
            document.getElementById('modalStudentName').value = btn.dataset.name;
            document.getElementById('modalProgramName').value = btn.dataset.program;
            document.getElementById('issueDate').value = new Date().toISOString().split('T')[0];
            
            const enrollmentId = btn.dataset.enrollmentId;
            console.log('Setting enrollment ID:', enrollmentId);
            document.getElementById('generateCertificateForm').dataset.enrollmentId = enrollmentId;
            
            document.getElementById('generateCertificateModal').classList.remove('hidden');
        }

        // Function to close generate certificate modal
        function closeGenerateModal() {
            document.getElementById('generateCertificateModal').classList.add('hidden');
            document.getElementById('generateCertificateForm').reset();
        }

        // Function to close generate modal when clicking outside
        function closeGenerateModalOnOutside(event) {
            if (event.target === event.currentTarget) {
                closeGenerateModal();
            }
        }

        // Function to close preview modal when clicking outside
        function closePreviewModalOnOutside(event) {
            if (event.target === event.currentTarget) {
                document.getElementById('previewModal').classList.add('hidden');
            }
        }

        // Function to mark certificate as done
        function markCertificateAsDone(certificateId) {
            if (confirm('Are you sure you want to mark this certificate as done? This will remove it from the issued certificates list.')) {
                // Get CSRF token
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                                 document.querySelector('input[name="_token"]')?.value;
                
                fetch(`/admin/certificates/${certificateId}/mark-done`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showSuccessMessage(data.message);
                        // Remove the row from the table
                        const row = document.querySelector(`button[onclick="markCertificateAsDone(${certificateId})"]`).closest('tr');
                        if (row) {
                            row.remove();
                        }
                    } else {
                        alert(data.message || 'Error marking certificate as done');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error marking certificate as done');
                });
            }
        }

        // Function to open student records modal
        function openStudentRecordsModal() {
            console.log('Opening student records modal...');
            
            // Remove any existing dynamic modal
            const existingModal = document.getElementById('dynamicStudentRecordsModal');
            if (existingModal) {
                existingModal.remove();
            }
            
            // Create a new dynamic modal that actually works
            const modal = document.createElement('div');
            modal.id = 'dynamicStudentRecordsModal';
            modal.style.cssText = `
                position: fixed;
                top: 0;
                left: 0;
                width: 100vw;
                height: 100vh;
                background-color: rgba(0, 0, 0, 0.5);
                z-index: 99999;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 20px;
            `;
            
            // Create modal content
            const modalContent = document.createElement('div');
            modalContent.style.cssText = `
                background-color: white;
                border-radius: 12px;
                box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
                max-width: 1200px;
                width: 100%;
                max-height: 90vh;
                overflow-y: auto;
            `;
            
            // Create modal header
            const modalHeader = document.createElement('div');
            modalHeader.style.cssText = `
                padding: 24px;
                border-bottom: 1px solid #e5e7eb;
                display: flex;
                justify-content: space-between;
                align-items: center;
                background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
                border-radius: 12px 12px 0 0;
            `;
            
            const headerTitle = document.createElement('h3');
            headerTitle.style.cssText = `
                font-size: 20px;
                font-weight: 600;
                color: #374151;
                margin: 0;
            `;
            headerTitle.innerHTML = '📋 Student Records - Completed Programs';
            
            const closeButton = document.createElement('button');
            closeButton.style.cssText = `
                background: none;
                border: none;
                font-size: 24px;
                color: #6b7280;
                cursor: pointer;
                padding: 4px;
                border-radius: 4px;
                transition: all 0.2s;
            `;
            closeButton.innerHTML = '×';
            closeButton.onclick = () => modal.remove();
            
            modalHeader.appendChild(headerTitle);
            modalHeader.appendChild(closeButton);
            
            // Create modal body
            const modalBody = document.createElement('div');
            modalBody.style.cssText = `
                padding: 24px;
            `;
            
            // Create search input
            const searchInput = document.createElement('input');
            searchInput.type = 'text';
            searchInput.placeholder = 'Search students by name or ID...';
            searchInput.style.cssText = `
                width: 100%;
                padding: 12px 16px;
                border: 2px solid #e5e7eb;
                border-radius: 8px;
                font-size: 16px;
                margin-bottom: 20px;
                transition: border-color 0.2s;
            `;
            searchInput.addEventListener('focus', () => {
                searchInput.style.borderColor = '#3b82f6';
            });
            searchInput.addEventListener('blur', () => {
                searchInput.style.borderColor = '#e5e7eb';
            });
            
            // Create table
            const table = document.createElement('table');
            table.style.cssText = `
                width: 100%;
                border-collapse: collapse;
                background: white;
                border-radius: 8px;
                overflow: hidden;
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            `;
            
            // Create table header
            const thead = document.createElement('thead');
            thead.style.cssText = `
                background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
            `;
            
            const headerRow = document.createElement('tr');
            const headers = ['Student', 'Program', 'Certificate Issued', 'Actions'];
            headers.forEach(headerText => {
                const th = document.createElement('th');
                th.style.cssText = `
                    padding: 16px;
                    text-align: left;
                    font-weight: 600;
                    color: #374151;
                    border-bottom: 2px solid #e5e7eb;
                `;
                th.textContent = headerText;
                headerRow.appendChild(th);
            });
            thead.appendChild(headerRow);
            
            // Create table body
            const tbody = document.createElement('tbody');
            tbody.id = 'dynamicStudentRecordsTableBody';
            tbody.style.cssText = `
                background: white;
            `;
            
            table.appendChild(thead);
            table.appendChild(tbody);
            
            modalBody.appendChild(searchInput);
            modalBody.appendChild(table);
            
            modalContent.appendChild(modalHeader);
            modalContent.appendChild(modalBody);
            modal.appendChild(modalContent);
            
            // Add to page
            document.body.appendChild(modal);
            
            // Close on outside click
            modal.addEventListener('click', (e) => {
                if (e.target === modal) {
                    modal.remove();
                }
            });
            
            console.log('Dynamic modal created and added to body');
            
            // Load student records
            loadStudentRecordsDynamic();
        }

        // Function to load student records for dynamic modal
        function loadStudentRecordsDynamic() {
            console.log('Loading student records for dynamic modal...');
            const tbody = document.getElementById('dynamicStudentRecordsTableBody');
            if (!tbody) {
                console.error('Dynamic student records table body not found!');
                return;
            }
            
            tbody.innerHTML = '<tr><td colspan="4" style="padding: 20px; text-align: center; color: #6b7280;">Loading...</td></tr>';
            
            fetch('/admin/student-records')
                .then(response => {
                    console.log('Response status:', response.status);
                    return response.json();
                })
                .then(data => {
                    console.log('Student records data:', data);
                    if (data.success) {
                        displayStudentRecordsDynamic(data.students);
                    } else {
                        tbody.innerHTML = '<tr><td colspan="4" style="padding: 20px; text-align: center; color: #ef4444;">Error loading student records</td></tr>';
                    }
                })
                .catch(error => {
                    console.error('Error loading student records:', error);
                    tbody.innerHTML = '<tr><td colspan="4" style="padding: 20px; text-align: center; color: #ef4444;">Error loading student records</td></tr>';
                });
        }

        // Function to display student records in dynamic modal
        function displayStudentRecordsDynamic(students) {
            console.log('displayStudentRecordsDynamic called with:', students);
            const tbody = document.getElementById('dynamicStudentRecordsTableBody');
            
            if (!tbody) {
                console.error('dynamicStudentRecordsTableBody not found!');
                return;
            }
            
            if (students.length === 0) {
                console.log('No students found, showing empty message');
                tbody.innerHTML = '<tr><td colspan="4" style="padding: 20px; text-align: center; color: #6b7280;">No completed students found</td></tr>';
                return;
            }
            
            console.log('Rendering', students.length, 'students in dynamic modal');
            
            tbody.innerHTML = students.map(student => `
                <tr style="border-bottom: 1px solid #e5e7eb; transition: background-color 0.2s;" onmouseover="this.style.backgroundColor='#f9fafb'" onmouseout="this.style.backgroundColor='white'">
                    <td style="padding: 16px;">
                        <div style="display: flex; align-items: center;">
                            <div style="width: 40px; height: 40px; background-color: #dcfce7; border-radius: 8px; display: flex; align-items: center; justify-content: center; margin-right: 12px;">
                                <span style="color: #16a34a; font-size: 16px;">🎓</span>
                            </div>
                            <div>
                                <div style="font-weight: 600; color: #111827; margin-bottom: 4px;">${student.name}</div>
                            </div>
                        </div>
                    </td>
                    <td style="padding: 16px;">
                        <span style="display: inline-flex; align-items: center; padding: 4px 12px; border-radius: 20px; font-size: 14px; font-weight: 500; background-color: #dbeafe; color: #1e40af;">
                            📚 ${student.program_name}
                        </span>
                    </td>
                    <td style="padding: 16px; color: #6b7280;">
                        ${new Date(student.certificate_issue_date).toLocaleDateString('en-US', {
                            year: 'numeric',
                            month: 'long',
                            day: 'numeric'
                        })}
                    </td>
                    <td style="padding: 16px;">
                        <button onclick="viewStudentDetailsDynamic(${student.id})" style="display: inline-flex; align-items: center; padding: 8px 16px; border: none; border-radius: 6px; font-size: 14px; font-weight: 500; color: white; background: linear-gradient(135deg, #10b981 0%, #059669 100%); cursor: pointer; transition: all 0.2s;" onmouseover="this.style.transform='translateY(-1px)'" onmouseout="this.style.transform='translateY(0)'">
                            👁️ View Details
                        </button>
                    </td>
                </tr>
            `).join('');
            
            console.log('Dynamic modal table populated with', students.length, 'students');
        }

        // Function to view student details in dynamic modal
        function viewStudentDetailsDynamic(studentId) {
            console.log('Opening student details for ID:', studentId);
            
            // Remove any existing student details modal
            const existingModal = document.getElementById('studentDetailsModal');
            if (existingModal) {
                existingModal.remove();
            }
            
            // Create student details modal
            const modal = document.createElement('div');
            modal.id = 'studentDetailsModal';
            modal.style.cssText = `
                position: fixed;
                top: 0;
                left: 0;
                width: 100vw;
                height: 100vh;
                background-color: rgba(0, 0, 0, 0.5);
                z-index: 100000;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 20px;
            `;
            
            // Create modal content
            const modalContent = document.createElement('div');
            modalContent.style.cssText = `
                background-color: white;
                border-radius: 12px;
                box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
                max-width: 1400px;
                width: 100%;
                max-height: 90vh;
                overflow-y: auto;
            `;
            
            // Create modal header
            const modalHeader = document.createElement('div');
            modalHeader.style.cssText = `
                padding: 24px;
                border-bottom: 1px solid #e5e7eb;
                display: flex;
                justify-content: space-between;
                align-items: center;
                background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
                border-radius: 12px 12px 0 0;
            `;
            
            const headerTitle = document.createElement('h3');
            headerTitle.style.cssText = `
                font-size: 24px;
                font-weight: 700;
                color: #0f172a;
                margin: 0;
            `;
            headerTitle.innerHTML = '👤 Student Complete Records';
            
            const closeButton = document.createElement('button');
            closeButton.style.cssText = `
                background: none;
                border: none;
                font-size: 28px;
                color: #6b7280;
                cursor: pointer;
                padding: 8px;
                border-radius: 6px;
                transition: all 0.2s;
            `;
            closeButton.innerHTML = '×';
            closeButton.onclick = () => modal.remove();
            closeButton.onmouseover = () => closeButton.style.backgroundColor = '#f3f4f6';
            closeButton.onmouseout = () => closeButton.style.backgroundColor = 'transparent';
            
            modalHeader.appendChild(headerTitle);
            modalHeader.appendChild(closeButton);
            
            // Create modal body with loading state
            const modalBody = document.createElement('div');
            modalBody.style.cssText = `
                padding: 24px;
            `;
            
            const loadingDiv = document.createElement('div');
            loadingDiv.style.cssText = `
                text-align: center;
                padding: 60px 20px;
                color: #6b7280;
                font-size: 18px;
            `;
            loadingDiv.innerHTML = `
                <div style="margin-bottom: 20px;">
                    <div style="width: 40px; height: 40px; border: 4px solid #e5e7eb; border-top: 4px solid #3b82f6; border-radius: 50%; animation: spin 1s linear infinite; margin: 0 auto;"></div>
                </div>
                Loading student details...
            `;
            
            // Add CSS animation for spinner
            if (!document.getElementById('spinner-styles')) {
                const style = document.createElement('style');
                style.id = 'spinner-styles';
                style.textContent = `
                    @keyframes spin {
                        0% { transform: rotate(0deg); }
                        100% { transform: rotate(360deg); }
                    }
                `;
                document.head.appendChild(style);
            }
            
            modalBody.appendChild(loadingDiv);
            modalContent.appendChild(modalHeader);
            modalContent.appendChild(modalBody);
            modal.appendChild(modalContent);
            
            // Add to page
            document.body.appendChild(modal);
            
            // Close on outside click
            modal.addEventListener('click', (e) => {
                if (e.target === modal) {
                    modal.remove();
                }
            });
            
            console.log('Student details modal created, loading data...');
            
            // Load student details
            loadStudentDetailsDynamic(studentId, modalBody);
        }

        // Function to load comprehensive student details
        function loadStudentDetailsDynamic(studentId, modalBody) {
            console.log('Loading comprehensive student details for ID:', studentId);
            
            fetch(`/admin/student-details/${studentId}`)
                .then(response => {
                    console.log('Student details response status:', response.status);
                    return response.json();
                })
                .then(data => {
                    console.log('Student details data:', data);
                    if (data.success) {
                        displayStudentDetailsDynamic(data.student, modalBody);
                    } else {
                        modalBody.innerHTML = `
                            <div style="text-align: center; padding: 60px 20px; color: #ef4444;">
                                <div style="font-size: 48px; margin-bottom: 20px;">❌</div>
                                <h3 style="color: #ef4444; margin-bottom: 10px;">Error Loading Student Details</h3>
                                <p style="color: #6b7280;">${data.message || 'Failed to load student information'}</p>
                            </div>
                        `;
                    }
                })
                .catch(error => {
                    console.error('Error loading student details:', error);
                    modalBody.innerHTML = `
                        <div style="text-align: center; padding: 60px 20px; color: #ef4444;">
                            <div style="font-size: 48px; margin-bottom: 20px;">⚠️</div>
                            <h3 style="color: #ef4444; margin-bottom: 10px;">Network Error</h3>
                            <p style="color: #6b7280;">Failed to load student information. Please try again.</p>
                        </div>
                    `;
                });
        }

        // Function to display comprehensive student details
        function displayStudentDetailsDynamic(student, modalBody) {
            console.log('Displaying comprehensive student details:', student);
            
            const enrollmentDate = new Date(student.enrollment_date).toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
            
            const certificateDate = new Date(student.certificate_issue_date).toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
            
            modalBody.innerHTML = `
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 24px;">
                    <!-- Student Information Card -->
                    <div style="background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%); border-radius: 12px; padding: 24px; border: 1px solid #bae6fd;">
                        <div style="display: flex; align-items: center; margin-bottom: 20px;">
                            <div style="width: 50px; height: 50px; background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-right: 16px;">
                                <span style="color: white; font-size: 24px;">👤</span>
                            </div>
                            <div>
                                <h3 style="font-size: 20px; font-weight: 700; color: #0f172a; margin: 0 0 4px 0;">Student Information</h3>
                                <p style="color: #64748b; margin: 0;">Personal Details</p>
                            </div>
                        </div>
                        <div style="display: flex; align-items: start; gap: 16px; margin-bottom: 20px;">
                            <!-- Student Photo -->
                            <div style="flex-shrink: 0;">
                                ${student.photo ? 
                                    `<img src="/storage/${student.photo}" alt="Profile" style="width: 60px; height: 60px; border-radius: 50%; object-fit: cover; border: 3px solid #3b82f6;">` :
                                    `<div style="width: 60px; height: 60px; border-radius: 50%; background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); display: flex; align-items: center; justify-content: center; border: 3px solid #3b82f6;">
                                        <span style="color: white; font-size: 24px;">👤</span>
                                    </div>`
                                }
                            </div>
                            
                            <!-- Student Details -->
                            <div style="flex: 1;">
                                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                                    <div>
                                        <label style="display: block; font-size: 12px; font-weight: 600; color: #6b7280; margin-bottom: 4px;">Name:</label>
                                        <p style="font-size: 16px; font-weight: 600; color: #111827; margin: 0;">${student.name}</p>
                                    </div>
                                    
                                    <div>
                                        <label style="display: block; font-size: 12px; font-weight: 600; color: #6b7280; margin-bottom: 4px;">Email:</label>
                                        <p style="color: #111827; margin: 0;">${student.email}</p>
                                    </div>
                                    
                                    <div>
                                        <label style="display: block; font-size: 12px; font-weight: 600; color: #6b7280; margin-bottom: 4px;">Phone:</label>
                                        <p style="color: #111827; margin: 0;">${student.phone || 'N/A'}</p>
                                    </div>
                                    
                                    <div>
                                        <label style="display: block; font-size: 12px; font-weight: 600; color: #6b7280; margin-bottom: 4px;">BirthDate:</label>
                                        <p style="color: #111827; margin: 0;">${student.birthdate ? new Date(student.birthdate).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' }) : 'N/A'}</p>
                                    </div>
                                </div>
                                
                                <!-- View More Button -->
                                <div style="margin-top: 16px;">
                                    <button onclick="openEnrollmentDetailsModal(${student.id})" style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); color: white; padding: 8px 16px; border-radius: 8px; border: none; cursor: pointer; font-weight: 500; display: flex; align-items: center; gap: 8px; transition: all 0.2s;" onmouseover="this.style.transform='translateY(-1px)'" onmouseout="this.style.transform='translateY(0)'">
                                        <span>👁️</span>
                                        View More
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Program Information Card -->
                    <div style="background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%); border-radius: 12px; padding: 24px; border: 1px solid #bbf7d0;">
                        <div style="display: flex; align-items: center; margin-bottom: 20px;">
                            <div style="width: 50px; height: 50px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-right: 16px;">
                                <span style="color: white; font-size: 24px;">📚</span>
                            </div>
                            <div>
                                <h3 style="font-size: 20px; font-weight: 700; color: #0f172a; margin: 0 0 4px 0;">Program Information</h3>
                                <p style="color: #64748b; margin: 0;">Enrollment Details</p>
                            </div>
                        </div>
                        <div style="space-y: 12px;">
                            <div style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #e2e8f0;">
                                <span style="font-weight: 600; color: #374151;">Program:</span>
                                <span style="color: #111827;">${student.program_name}</span>
                            </div>
                            <div style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #e2e8f0;">
                                <span style="font-weight: 600; color: #374151;">Duration:</span>
                                <span style="color: #111827;">${student.program_duration} sessions</span>
                            </div>
                            <div style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #e2e8f0;">
                                <span style="font-weight: 600; color: #374151;">Instructor:</span>
                                <span style="color: #111827;">${student.certificate_instructor || 'N/A'}</span>
                            </div>
                            <div style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #e2e8f0;">
                                <span style="font-weight: 600; color: #374151;">Enrolled:</span>
                                <span style="color: #111827;">${enrollmentDate}</span>
                            </div>
                            <div style="display: flex; justify-content: space-between; padding: 8px 0;">
                                <span style="font-weight: 600; color: #374151;">Status:</span>
                                <span style="color: #111827; font-weight: 600; color: #059669;">✅ Completed</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 24px;">
                    <!-- Certificate Information Card -->
                    <div style="background: linear-gradient(135deg, #fefce8 0%, #fef3c7 100%); border-radius: 12px; padding: 24px; border: 1px solid #fde68a;">
                        <div style="display: flex; align-items: center; margin-bottom: 20px;">
                            <div style="width: 50px; height: 50px; background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-right: 16px;">
                                <span style="color: white; font-size: 24px;">🏆</span>
                            </div>
                            <div>
                                <h3 style="font-size: 20px; font-weight: 700; color: #0f172a; margin: 0 0 4px 0;">Certificate Information</h3>
                                <p style="color: #64748b; margin: 0;">Completion Details</p>
                            </div>
                        </div>
                        <div style="space-y: 12px;">
                            <div style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #e2e8f0;">
                                <span style="font-weight: 600; color: #374151;">Certificate #:</span>
                                <span style="color: #111827; font-family: monospace;">${student.certificate_number}</span>
                            </div>
                            <div style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #e2e8f0;">
                                <span style="font-weight: 600; color: #374151;">Issue Date:</span>
                                <span style="color: #111827;">${certificateDate}</span>
                            </div>
                            <div style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #e2e8f0;">
                                <span style="font-weight: 600; color: #374151;">Issued By:</span>
                                <span style="color: #111827;">${student.certificate_issued_by}</span>
                            </div>
                            <div style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #e2e8f0;">
                                <span style="font-weight: 600; color: #374151;">Instructor:</span>
                                <span style="color: #111827;">${student.certificate_instructor}</span>
                            </div>
                        </div>
                        
                        <!-- View Certificate Button -->
                        <div style="margin-top: 20px; text-align: center;">
                            <button onclick="viewStudentCertificate(${student.id})" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white; padding: 12px 24px; border-radius: 8px; border: none; cursor: pointer; font-weight: 600; display: flex; align-items: center; gap: 8px; margin: 0 auto; transition: all 0.2s;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
                                <span>📜</span>
                                View Certificate
                            </button>
                        </div>
                    </div>

                    <!-- Payment Summary Card -->
                    <div style="background: linear-gradient(135deg, #fdf2f8 0%, #fce7f3 100%); border-radius: 12px; padding: 24px; border: 1px solid #f9a8d4;">
                        <div style="display: flex; align-items: center; margin-bottom: 20px;">
                            <div style="width: 50px; height: 50px; background: linear-gradient(135deg, #ec4899 0%, #be185d 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-right: 16px;">
                                <span style="color: white; font-size: 24px;">💳</span>
                            </div>
                            <div>
                                <h3 style="font-size: 20px; font-weight: 700; color: #0f172a; margin: 0 0 4px 0;">Payment Summary</h3>
                                <p style="color: #64748b; margin: 0;">Financial Records</p>
                            </div>
                        </div>
                        <div style="space-y: 12px;">
                            <div style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #e2e8f0;">
                                <span style="font-weight: 600; color: #374151;">Total Payments:</span>
                                <span style="color: #111827; font-weight: 600;">₱${student.payments.reduce((sum, payment) => sum + parseFloat(payment.amount), 0).toFixed(2)}</span>
                            </div>
                            <div style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #e2e8f0;">
                                <span style="font-weight: 600; color: #374151;">Payment Count:</span>
                                <span style="color: #111827;">${student.payments.length} transactions</span>
                            </div>
                            <div style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #e2e8f0;">
                                <span style="font-weight: 600; color: #374151;">Latest Payment:</span>
                                <span style="color: #111827;">${new Date(student.payments[0]?.created_at).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' }) || 'N/A'}</span>
                            </div>
                            <div style="display: flex; justify-content: space-between; padding: 8px 0;">
                                <span style="font-weight: 600; color: #374151;">Status:</span>
                                <span style="color: #111827; font-weight: 600; color: #059669;">✅ Paid</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Attendance Records -->
                <div style="background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); border-radius: 12px; padding: 24px; border: 1px solid #cbd5e1; margin-bottom: 24px;">
                    <div style="display: flex; align-items: center; margin-bottom: 20px;">
                        <div style="width: 50px; height: 50px; background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-right: 16px;">
                            <span style="color: white; font-size: 24px;">📅</span>
                        </div>
                        <div>
                            <h3 style="font-size: 20px; font-weight: 700; color: #0f172a; margin: 0 0 4px 0;">Attendance Records</h3>
                            <p style="color: #64748b; margin: 0;">Session Attendance (${student.attendances.length}/${student.program_duration})</p>
                        </div>
                    </div>
                    <div style="overflow-x: auto;">
                        <table style="width: 100%; border-collapse: collapse; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);">
                            <thead style="background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);">
                                <tr>
                                    <th style="padding: 16px; text-align: left; font-weight: 600; color: #374151; border-bottom: 2px solid #e5e7eb;">Session Number</th>
                                    <th style="padding: 16px; text-align: left; font-weight: 600; color: #374151; border-bottom: 2px solid #e5e7eb;">Date Attended</th>
                                    <th style="padding: 16px; text-align: left; font-weight: 600; color: #374151; border-bottom: 2px solid #e5e7eb;">Status</th>
                                    <th style="padding: 16px; text-align: left; font-weight: 600; color: #374151; border-bottom: 2px solid #e5e7eb;">Amount Paid</th>
                                    <th style="padding: 16px; text-align: left; font-weight: 600; color: #374151; border-bottom: 2px solid #e5e7eb;">OR/Reference Number</th>
                                    <th style="padding: 16px; text-align: left; font-weight: 600; color: #374151; border-bottom: 2px solid #e5e7eb;">Marked by</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${student.attendances.map(attendance => `
                                    <tr style="border-bottom: 1px solid #f3f4f6;">
                                        <td style="padding: 16px; color: #111827; font-weight: 600;">Session ${attendance.session_number}</td>
                                        <td style="padding: 16px; color: #6b7280;">${new Date(attendance.date).toLocaleDateString('en-US', { 
                                            year: 'numeric', 
                                            month: 'long', 
                                            day: 'numeric' 
                                        })}</td>
                                        <td style="padding: 16px;">
                                            <span style="display: inline-flex; align-items: center; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; background-color: #dcfce7; color: #166534;">
                                                ✅ ${attendance.status.charAt(0).toUpperCase() + attendance.status.slice(1)}
                                            </span>
                                        </td>
                                        <td style="padding: 16px; color: #059669; font-weight: 600;">₱${parseFloat(attendance.amount_paid || 0).toFixed(2)}</td>
                                        <td style="padding: 16px; color: #6b7280; font-family: monospace; font-size: 14px;">${attendance.reference_number || 'N/A'}</td>
                                        <td style="padding: 16px; color: #6b7280;">${attendance.marked_by || 'N/A'}</td>
                                    </tr>
                                `).join('')}
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Payment Details -->
                <div style="background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); border-radius: 12px; padding: 24px; border: 1px solid #cbd5e1;">
                    <div style="display: flex; align-items: center; margin-bottom: 20px;">
                        <div style="width: 50px; height: 50px; background: linear-gradient(135deg, #ec4899 0%, #be185d 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-right: 16px;">
                            <span style="color: white; font-size: 24px;">💰</span>
                        </div>
                        <div>
                            <h3 style="font-size: 20px; font-weight: 700; color: #0f172a; margin: 0 0 4px 0;">Payment Details</h3>
                            <p style="color: #64748b; margin: 0;">Transaction History</p>
                        </div>
                    </div>
                    <div style="overflow-x: auto;">
                        <table style="width: 100%; border-collapse: collapse; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);">
                            <thead style="background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);">
                                <tr>
                                    <th style="padding: 16px; text-align: left; font-weight: 600; color: #374151; border-bottom: 2px solid #e5e7eb;">Date</th>
                                    <th style="padding: 16px; text-align: left; font-weight: 600; color: #374151; border-bottom: 2px solid #e5e7eb;">Amount</th>
                                    <th style="padding: 16px; text-align: left; font-weight: 600; color: #374151; border-bottom: 2px solid #e5e7eb;">Method</th>
                                    <th style="padding: 16px; text-align: left; font-weight: 600; color: #374151; border-bottom: 2px solid #e5e7eb;">Reference/OR Number</th>
                                    <th style="padding: 16px; text-align: left; font-weight: 600; color: #374151; border-bottom: 2px solid #e5e7eb;">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${student.payments.map(payment => `
                                    <tr style="border-bottom: 1px solid #e5e7eb; transition: background-color 0.2s;" onmouseover="this.style.backgroundColor='#f9fafb'" onmouseout="this.style.backgroundColor='white'">
                                        <td style="padding: 16px; color: #374151;">${new Date(payment.created_at).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' })}</td>
                                        <td style="padding: 16px; color: #111827; font-weight: 600;">₱${parseFloat(payment.amount).toFixed(2)}</td>
                                        <td style="padding: 16px;">
                                            <span style="display: inline-flex; align-items: center; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 500; ${payment.payment_method.toLowerCase() === 'gcash' ? 'background-color: #dbeafe; color: #1e40af;' : 'background-color: #fef3c7; color: #92400e;'}">
                                                ${payment.payment_method}
                                            </span>
                                        </td>
                                        <td style="padding: 16px; color: #6b7280; font-family: monospace;">${payment.or_number || 'N/A'}</td>
                                        <td style="padding: 16px;">
                                            <span style="display: inline-flex; align-items: center; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 500; background-color: #dcfce7; color: #166534;">
                                                ✅ ${payment.status}
                                            </span>
                                        </td>
                                    </tr>
                                `).join('')}
                            </tbody>
                        </table>
                    </div>
                </div>
            `;
            
            console.log('Comprehensive student details displayed successfully');
        }

        // Function to close student records modal
        function closeStudentRecordsModal() {
            const dynamicModal = document.getElementById('dynamicStudentRecordsModal');
            if (dynamicModal) {
                dynamicModal.remove();
            }
            const originalModal = document.getElementById('studentRecordsModal');
            if (originalModal) {
                originalModal.classList.add('hidden');
            }
        }

        // Function to open enrollment details modal
        function openEnrollmentDetailsModal(studentId) {
            console.log('Opening enrollment details modal for student ID:', studentId);
            
            // Create modal
            const modal = document.createElement('div');
            modal.style.cssText = `
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.7);
                display: flex;
                align-items: center;
                justify-content: center;
                z-index: 999999;
            `;
            
            // Create modal content
            const modalContent = document.createElement('div');
            modalContent.style.cssText = `
                background: white;
                border-radius: 16px;
                max-width: 1000px;
                width: 95%;
                max-height: 95vh;
                overflow-y: auto;
                box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            `;
            
            // Create modal header
            const modalHeader = document.createElement('div');
            modalHeader.style.cssText = `
                padding: 24px;
                border-bottom: 1px solid #e5e7eb;
                display: flex;
                justify-content: space-between;
                align-items: center;
                background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
                color: white;
                border-radius: 16px 16px 0 0;
            `;
            
            const headerTitle = document.createElement('h3');
            headerTitle.style.cssText = `
                font-size: 24px;
                font-weight: 700;
                margin: 0;
            `;
            headerTitle.innerHTML = '📋 Complete Enrollment Records';
            
            const closeButton = document.createElement('button');
            closeButton.style.cssText = `
                background: none;
                border: none;
                font-size: 28px;
                color: white;
                cursor: pointer;
                padding: 8px;
                border-radius: 6px;
                transition: all 0.2s;
            `;
            closeButton.innerHTML = '×';
            closeButton.onclick = () => modal.remove();
            closeButton.onmouseover = () => closeButton.style.backgroundColor = 'rgba(255, 255, 255, 0.1)';
            closeButton.onmouseout = () => closeButton.style.backgroundColor = 'transparent';
            
            modalHeader.appendChild(headerTitle);
            modalHeader.appendChild(closeButton);
            
            // Create modal body with loading state
            const modalBody = document.createElement('div');
            modalBody.style.cssText = `
                padding: 24px;
            `;
            
            const loadingDiv = document.createElement('div');
            loadingDiv.style.cssText = `
                text-align: center;
                padding: 60px 20px;
                color: #6b7280;
                font-size: 18px;
            `;
            loadingDiv.innerHTML = `
                <div style="margin-bottom: 20px;">
                    <div style="width: 40px; height: 40px; border: 4px solid #e5e7eb; border-top: 4px solid #3b82f6; border-radius: 50%; animation: spin 1s linear infinite; margin: 0 auto;"></div>
                </div>
                Loading enrollment details...
            `;
            
            modalBody.appendChild(loadingDiv);
            modalContent.appendChild(modalHeader);
            modalContent.appendChild(modalBody);
            modal.appendChild(modalContent);
            
            // Add to page
            document.body.appendChild(modal);
            
            // Close on outside click
            modal.addEventListener('click', (e) => {
                if (e.target === modal) {
                    modal.remove();
                }
            });
            
            // Load enrollment details
            loadEnrollmentDetails(studentId, modalBody);
        }

        // Function to load enrollment details
        function loadEnrollmentDetails(studentId, modalBody) {
            console.log('Loading enrollment details for student ID:', studentId);
            
            fetch(`/admin/enrollment-details/${studentId}`)
                .then(response => response.json())
                .then(data => {
                    console.log('Enrollment details data:', data);
                    if (data.success) {
                        displayEnrollmentDetails(data.enrollment, modalBody);
                    } else {
                        modalBody.innerHTML = `
                            <div style="text-align: center; padding: 60px 20px; color: #ef4444;">
                                <div style="font-size: 48px; margin-bottom: 20px;">❌</div>
                                <h3 style="color: #ef4444; margin-bottom: 10px;">Error Loading Enrollment Details</h3>
                                <p style="color: #6b7280;">${data.message || 'Failed to load enrollment information'}</p>
                            </div>
                        `;
                    }
                })
                .catch(error => {
                    console.error('Error loading enrollment details:', error);
                    modalBody.innerHTML = `
                        <div style="text-align: center; padding: 60px 20px; color: #ef4444;">
                            <div style="font-size: 48px; margin-bottom: 20px;">⚠️</div>
                            <h3 style="color: #ef4444; margin-bottom: 10px;">Network Error</h3>
                            <p style="color: #6b7280;">Failed to load enrollment information. Please try again.</p>
                        </div>
                    `;
                });
        }

        // Function to display enrollment details
        function displayEnrollmentDetails(enrollment, modalBody) {
            console.log('Displaying enrollment details:', enrollment);
            
            modalBody.innerHTML = `
                <div style="padding: 30px;">
                    <!-- Personal Information -->
                    <div style="background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); border-radius: 16px; padding: 30px; margin-bottom: 30px; border: 1px solid #e2e8f0;">
                        <h3 style="font-size: 24px; font-weight: 700; color: #0f172a; margin-bottom: 24px; border-bottom: 3px solid #3b82f6; padding-bottom: 12px; display: flex; align-items: center;">
                            <span style="margin-right: 12px; font-size: 28px;">👤</span>
                            Personal Information
                        </h3>
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
                            <div style="background: white; padding: 20px; border-radius: 12px; border-left: 4px solid #3b82f6; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                                <label style="display: block; font-size: 14px; font-weight: 600; color: #6b7280; margin-bottom: 8px;">Full Name</label>
                                <p style="color: #111827; font-weight: 500; font-size: 16px; margin: 0;">${enrollment.first_name || ''} ${enrollment.middle_name || ''} ${enrollment.last_name || ''} ${enrollment.suffix_name || ''}</p>
                            </div>
                            <div style="background: white; padding: 20px; border-radius: 12px; border-left: 4px solid #3b82f6; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                                <label style="display: block; font-size: 14px; font-weight: 600; color: #6b7280; margin-bottom: 8px;">Email</label>
                                <p style="color: #111827; font-size: 16px; margin: 0;">${enrollment.email || 'N/A'}</p>
                            </div>
                            <div style="background: white; padding: 20px; border-radius: 12px; border-left: 4px solid #3b82f6; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                                <label style="display: block; font-size: 14px; font-weight: 600; color: #6b7280; margin-bottom: 8px;">Phone</label>
                                <p style="color: #111827; font-size: 16px; margin: 0;">${enrollment.phone || 'N/A'}</p>
                            </div>
                            <div style="background: white; padding: 20px; border-radius: 12px; border-left: 4px solid #3b82f6; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                                <label style="display: block; font-size: 14px; font-weight: 600; color: #6b7280; margin-bottom: 8px;">Birthdate</label>
                                <p style="color: #111827; font-size: 16px; margin: 0;">${enrollment.birthdate ? new Date(enrollment.birthdate).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' }) : 'N/A'}</p>
                            </div>
                            <div style="background: white; padding: 20px; border-radius: 12px; border-left: 4px solid #3b82f6; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                                <label style="display: block; font-size: 14px; font-weight: 600; color: #6b7280; margin-bottom: 8px;">Age</label>
                                <p style="color: #111827; font-size: 16px; margin: 0;">${enrollment.age || 'N/A'}</p>
                            </div>
                            <div style="background: white; padding: 20px; border-radius: 12px; border-left: 4px solid #3b82f6; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                                <label style="display: block; font-size: 14px; font-weight: 600; color: #6b7280; margin-bottom: 8px;">Gender</label>
                                <p style="color: #111827; font-size: 16px; margin: 0;">${enrollment.gender || 'N/A'}</p>
                            </div>
                            <div style="background: white; padding: 20px; border-radius: 12px; border-left: 4px solid #3b82f6; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                                <label style="display: block; font-size: 14px; font-weight: 600; color: #6b7280; margin-bottom: 8px;">Civil Status</label>
                                <p style="color: #111827; font-size: 16px; margin: 0;">${enrollment.civil_status || 'N/A'}</p>
                            </div>
                            ${enrollment.civil_status === 'Married' ? `
                            <div style="background: white; padding: 20px; border-radius: 12px; border-left: 4px solid #3b82f6; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                                <label style="display: block; font-size: 14px; font-weight: 600; color: #6b7280; margin-bottom: 8px;">Spouse Name</label>
                                <p style="color: #111827; font-size: 16px; margin: 0;">${enrollment.spouse_name || 'N/A'}</p>
                            </div>
                            ` : ''}
                            <div style="background: white; padding: 20px; border-radius: 12px; border-left: 4px solid #3b82f6; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                                <label style="display: block; font-size: 14px; font-weight: 600; color: #6b7280; margin-bottom: 8px;">Citizenship</label>
                                <p style="color: #111827; font-size: 16px; margin: 0;">${enrollment.citizenship || 'N/A'}</p>
                            </div>
                            <div style="background: white; padding: 20px; border-radius: 12px; border-left: 4px solid #3b82f6; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                                <label style="display: block; font-size: 14px; font-weight: 600; color: #6b7280; margin-bottom: 8px;">Religion</label>
                                <p style="color: #111827; font-size: 16px; margin: 0;">${enrollment.religion || 'N/A'}</p>
                            </div>
                            <div style="background: white; padding: 20px; border-radius: 12px; border-left: 4px solid #3b82f6; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                                <label style="display: block; font-size: 14px; font-weight: 600; color: #6b7280; margin-bottom: 8px;">Place of Birth</label>
                                <p style="color: #111827; font-size: 16px; margin: 0;">${enrollment.place_of_birth || 'N/A'}</p>
                            </div>
                            <div style="background: white; padding: 20px; border-radius: 12px; border-left: 4px solid #3b82f6; box-shadow: 0 2px 4px rgba(0,0,0,0.05); grid-column: 1 / -1;">
                                <label style="display: block; font-size: 14px; font-weight: 600; color: #6b7280; margin-bottom: 8px;">Home Address</label>
                                <p style="color: #111827; font-size: 16px; margin: 0; line-height: 1.5;">${enrollment.address || 'N/A'}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Family Information -->
                    <div style="background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%); border-radius: 16px; padding: 30px; border: 1px solid #bbf7d0;">
                        <h3 style="font-size: 24px; font-weight: 700; color: #0f172a; margin-bottom: 24px; border-bottom: 3px solid #10b981; padding-bottom: 12px; display: flex; align-items: center;">
                            <span style="margin-right: 12px; font-size: 28px;">👨‍👩‍👧‍👦</span>
                            Family Information
                        </h3>
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
                            <div style="background: white; padding: 20px; border-radius: 12px; border-left: 4px solid #10b981; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                                <label style="display: block; font-size: 14px; font-weight: 600; color: #6b7280; margin-bottom: 8px;">Father's Name</label>
                                <p style="color: #111827; font-size: 16px; margin: 0;">${enrollment.father_name || 'N/A'}</p>
                            </div>
                            <div style="background: white; padding: 20px; border-radius: 12px; border-left: 4px solid #10b981; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                                <label style="display: block; font-size: 14px; font-weight: 600; color: #6b7280; margin-bottom: 8px;">Mother's Name</label>
                                <p style="color: #111827; font-size: 16px; margin: 0;">${enrollment.mother_name || 'N/A'}</p>
                            </div>
                            <div style="background: white; padding: 20px; border-radius: 12px; border-left: 4px solid #10b981; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                                <label style="display: block; font-size: 14px; font-weight: 600; color: #6b7280; margin-bottom: 8px;">Guardian</label>
                                <p style="color: #111827; font-size: 16px; margin: 0;">${enrollment.guardian || 'N/A'}</p>
                            </div>
                            <div style="background: white; padding: 20px; border-radius: 12px; border-left: 4px solid #10b981; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                                <label style="display: block; font-size: 14px; font-weight: 600; color: #6b7280; margin-bottom: 8px;">Guardian Contact</label>
                                <p style="color: #111827; font-size: 16px; margin: 0;">${enrollment.guardian_contact || 'N/A'}</p>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }

        // Function to view student certificate
        function viewStudentCertificate(studentId) {
            console.log('Opening certificate view for student ID:', studentId);
            
            // Create modal
            const modal = document.createElement('div');
            modal.style.cssText = `
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.7);
                display: flex;
                align-items: center;
                justify-content: center;
                z-index: 999999;
            `;
            
            // Create modal content
            const modalContent = document.createElement('div');
            modalContent.style.cssText = `
                background: white;
                border-radius: 16px;
                max-width: 95%;
                width: 100%;
                max-height: 95vh;
                overflow-y: auto;
                box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            `;
            
            // Create modal header
            const modalHeader = document.createElement('div');
            modalHeader.style.cssText = `
                padding: 24px;
                border-bottom: 1px solid #e5e7eb;
                display: flex;
                justify-content: space-between;
                align-items: center;
                background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
                color: white;
                border-radius: 16px 16px 0 0;
            `;
            
            const headerTitle = document.createElement('h3');
            headerTitle.style.cssText = `
                font-size: 24px;
                font-weight: 700;
                margin: 0;
            `;
            headerTitle.innerHTML = '📜 Student Certificate';
            
            const closeButton = document.createElement('button');
            closeButton.style.cssText = `
                background: none;
                border: none;
                font-size: 28px;
                color: white;
                cursor: pointer;
                padding: 8px;
                border-radius: 6px;
                transition: all 0.2s;
            `;
            closeButton.innerHTML = '×';
            closeButton.onclick = () => modal.remove();
            closeButton.onmouseover = () => closeButton.style.backgroundColor = 'rgba(255, 255, 255, 0.1)';
            closeButton.onmouseout = () => closeButton.style.backgroundColor = 'transparent';
            
            modalHeader.appendChild(headerTitle);
            modalHeader.appendChild(closeButton);
            
            // Create modal body with loading state
            const modalBody = document.createElement('div');
            modalBody.style.cssText = `
                padding: 24px;
            `;
            
            const loadingDiv = document.createElement('div');
            loadingDiv.style.cssText = `
                text-align: center;
                padding: 60px 20px;
                color: #6b7280;
                font-size: 18px;
            `;
            loadingDiv.innerHTML = `
                <div style="margin-bottom: 20px;">
                    <div style="width: 40px; height: 40px; border: 4px solid #e5e7eb; border-top: 4px solid #f59e0b; border-radius: 50%; animation: spin 1s linear infinite; margin: 0 auto;"></div>
                </div>
                Loading certificate...
            `;
            
            modalBody.appendChild(loadingDiv);
            modalContent.appendChild(modalHeader);
            modalContent.appendChild(modalBody);
            modal.appendChild(modalContent);
            
            // Add to page
            document.body.appendChild(modal);
            
            // Close on outside click
            modal.addEventListener('click', (e) => {
                if (e.target === modal) {
                    modal.remove();
                }
            });
            
            // Load certificate details
            loadCertificateDetails(studentId, modalBody);
        }

        // Function to load certificate details
        function loadCertificateDetails(studentId, modalBody) {
            console.log('Loading certificate details for student ID:', studentId);
            
            // First, get the student's enrollment to find the certificate ID
            fetch(`/admin/student-details/${studentId}`)
                .then(response => response.json())
                .then(data => {
                    console.log('Student details data:', data);
                    if (data.success && data.student.certificate_id) {
                        // Now fetch the actual certificate details using the certificate ID
                        fetch(`/admin/certificate-details/${data.student.certificate_id}`)
                            .then(response => response.json())
                            .then(certData => {
                                console.log('Certificate details data:', certData);
                                if (certData.success) {
                                    displayGeneratedCertificate(certData.certificate, modalBody);
                                } else {
                                    // Fallback to basic certificate display
                                    displayCertificateDetails(data.student, modalBody);
                                }
                            })
                            .catch(error => {
                                console.error('Error loading certificate details:', error);
                                // Fallback to basic certificate display
                                displayCertificateDetails(data.student, modalBody);
                            });
                    } else {
                        modalBody.innerHTML = `
                            <div style="text-align: center; padding: 60px 20px; color: #ef4444;">
                                <div style="font-size: 48px; margin-bottom: 20px;">❌</div>
                                <h3 style="color: #ef4444; margin-bottom: 10px;">No Certificate Found</h3>
                                <p style="color: #6b7280;">This student does not have a generated certificate yet.</p>
                            </div>
                        `;
                    }
                })
                .catch(error => {
                    console.error('Error loading student details:', error);
                    modalBody.innerHTML = `
                        <div style="text-align: center; padding: 60px 20px; color: #ef4444;">
                            <div style="font-size: 48px; margin-bottom: 20px;">⚠️</div>
                            <h3 style="color: #ef4444; margin-bottom: 10px;">Network Error</h3>
                            <p style="color: #6b7280;">Failed to load certificate information. Please try again.</p>
                        </div>
                    `;
                });
        }

        // Function to display certificate details
        function displayCertificateDetails(student, modalBody) {
            console.log('Displaying certificate details:', student);
            
            const certificateDate = new Date(student.certificate_issue_date).toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
            
            modalBody.innerHTML = `
                <div style="text-align: center; margin-bottom: 30px;">
                    <div style="background: linear-gradient(135deg, #fefce8 0%, #fef3c7 100%); border-radius: 16px; padding: 40px; border: 2px solid #fde68a; margin-bottom: 20px;">
                        <div style="font-size: 48px; margin-bottom: 20px;">🏆</div>
                        <h2 style="font-size: 32px; font-weight: 700; color: #0f172a; margin-bottom: 10px;">Certificate of Completion</h2>
                        <p style="font-size: 18px; color: #64748b; margin-bottom: 30px;">This certifies that</p>
                        
                        <div style="background: white; border-radius: 12px; padding: 30px; margin: 20px 0; border: 1px solid #e5e7eb;">
                            <h3 style="font-size: 28px; font-weight: 600; color: #0f172a; margin-bottom: 10px;">${student.name}</h3>
                            <p style="font-size: 16px; color: #64748b;">has successfully completed the program</p>
                            <p style="font-size: 20px; font-weight: 600; color: #f59e0b; margin-top: 10px;">${student.program_name}</p>
                        </div>
                        
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-top: 30px;">
                            <div>
                                <p style="font-size: 14px; color: #6b7280; margin-bottom: 5px;">Certificate Number</p>
                                <p style="font-size: 16px; font-weight: 600; color: #0f172a; font-family: monospace;">${student.certificate_number}</p>
                            </div>
                            <div>
                                <p style="font-size: 14px; color: #6b7280; margin-bottom: 5px;">Issue Date</p>
                                <p style="font-size: 16px; font-weight: 600; color: #0f172a;">${certificateDate}</p>
                            </div>
                        </div>
                        
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-top: 20px;">
                            <div>
                                <p style="font-size: 14px; color: #6b7280; margin-bottom: 5px;">Issued By</p>
                                <p style="font-size: 16px; font-weight: 600; color: #0f172a;">${student.certificate_issued_by}</p>
                            </div>
                            <div>
                                <p style="font-size: 14px; color: #6b7280; margin-bottom: 5px;">Instructor</p>
                                <p style="font-size: 16px; font-weight: 600; color: #0f172a;">${student.certificate_instructor}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div style="display: flex; gap: 16px; justify-content: center;">
                        <button onclick="printCertificate()" style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); color: white; padding: 12px 24px; border-radius: 8px; border: none; cursor: pointer; font-weight: 600; display: flex; align-items: center; gap: 8px; transition: all 0.2s;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
                            <span>🖨️</span>
                            Print Certificate
                        </button>
                        <button onclick="downloadCertificate()" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 12px 24px; border-radius: 8px; border: none; cursor: pointer; font-weight: 600; display: flex; align-items: center; gap: 8px; transition: all 0.2s;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
                            <span>📥</span>
                            Download PDF
                        </button>
                    </div>
                </div>
            `;
        }

        // Function to display generated certificate
        function displayGeneratedCertificate(certificate, modalBody) {
            console.log('Displaying generated certificate:', certificate);
            
            // Check if certificate has an image
            if (certificate.certificate_image) {
                // Display the actual certificate image
                modalBody.innerHTML = `
                    <div style="text-align: center; margin-bottom: 30px;">
                        <!-- Actual Certificate Image -->
                        <div style="background: white; border-radius: 8px; padding: 20px; margin: 20px auto; max-width: 100%; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                            <img src="/storage/${certificate.certificate_image}" alt="Certificate" style="max-width: 100%; height: auto; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                        </div>
                        
                        <!-- Certificate Info -->
                        <div style="background: #f8fafc; border-radius: 8px; padding: 20px; margin: 20px auto; max-width: 600px;">
                            <h3 style="color: #1f2937; margin-bottom: 16px; font-size: 18px;">Certificate Information</h3>
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; text-align: left;">
                                <div>
                                    <p style="font-size: 14px; color: #6b7280; margin-bottom: 4px;">Certificate Number</p>
                                    <p style="font-size: 16px; font-weight: 600; color: #111827; font-family: monospace;">${certificate.certificate_number || 'N/A'}</p>
                                </div>
                                <div>
                                    <p style="font-size: 14px; color: #6b7280; margin-bottom: 4px;">Issue Date</p>
                                    <p style="font-size: 16px; font-weight: 600; color: #111827;">${certificate.issue_date ? new Date(certificate.issue_date).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' }) : 'N/A'}</p>
                                </div>
                                <div>
                                    <p style="font-size: 14px; color: #6b7280; margin-bottom: 4px;">Issued By</p>
                                    <p style="font-size: 16px; font-weight: 600; color: #111827;">${certificate.issued_by || 'N/A'}</p>
                                </div>
                                <div>
                                    <p style="font-size: 14px; color: #6b7280; margin-bottom: 4px;">Instructor</p>
                                    <p style="font-size: 16px; font-weight: 600; color: #111827;">${certificate.instructor_name || 'N/A'}</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div style="display: flex; gap: 16px; justify-content: center; margin-top: 30px;">
                            <button onclick="printCertificate()" style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); color: white; padding: 12px 24px; border-radius: 8px; border: none; cursor: pointer; font-weight: 600; display: flex; align-items: center; gap: 8px; transition: all 0.2s;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
                                <span>🖨️</span>
                                Print Certificate
                            </button>
                            <button onclick="downloadCertificatePDF('${certificate.id}')" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 12px 24px; border-radius: 8px; border: none; cursor: pointer; font-weight: 600; display: flex; align-items: center; gap: 8px; transition: all 0.2s;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
                                <span>📥</span>
                                Download PDF
                            </button>
                        </div>
                    </div>
                `;
            } else {
                // Fallback: Show message that certificate image is not available
                modalBody.innerHTML = `
                    <div style="text-align: center; padding: 60px 20px; color: #ef4444;">
                        <div style="font-size: 48px; margin-bottom: 20px;">📄</div>
                        <h3 style="color: #ef4444; margin-bottom: 10px;">Certificate Image Not Available</h3>
                        <p style="color: #6b7280;">The certificate image for ${certificate.certificate_number || 'this certificate'} has not been generated yet.</p>
                        <div style="margin-top: 20px;">
                            <p style="color: #6b7280; font-size: 14px;">Certificate Number: <strong>${certificate.certificate_number || 'N/A'}</strong></p>
                            <p style="color: #6b7280; font-size: 14px;">Issue Date: <strong>${certificate.issue_date ? new Date(certificate.issue_date).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' }) : 'N/A'}</strong></p>
                        </div>
                    </div>
                `;
            }
        }

        // Function to print certificate
        function printCertificate() {
            window.print();
        }

        // Function to download certificate as PDF
        function downloadCertificatePDF(certificateId) {
            if (certificateId) {
                // Open the PDF download URL using certificate ID
                window.open(`/admin/certificates/${certificateId}/pdf`, '_blank');
            } else {
                alert('Certificate ID not found');
            }
        }

        // Function to close student records modal when clicking outside
        function closeStudentRecordsModalOnOutside(event) {
            if (event.target === event.currentTarget) {
                closeStudentRecordsModal();
            }
        }

        // Function to load student records
        function loadStudentRecords() {
            console.log('Loading student records...');
            const tbody = document.getElementById('studentRecordsTableBody');
            if (!tbody) {
                console.error('Student records table body not found!');
                return;
            }
            
            tbody.innerHTML = '<tr><td colspan="4" class="px-6 py-4 text-center text-gray-500">Loading...</td></tr>';

            fetch('/admin/student-records')
                .then(response => {
                    console.log('Response status:', response.status);
                    return response.json();
                })
            .then(data => {
                console.log('Student records data:', data);
                console.log('Data success:', data.success);
                console.log('Data students:', data.students);
                if (data.success) {
                    console.log('Calling displayStudentRecords with:', data.students);
                    displayStudentRecords(data.students);
                } else {
                    console.log('API returned error, showing error message');
                    tbody.innerHTML = '<tr><td colspan="4" class="px-6 py-4 text-center text-red-500">Error loading student records</td></tr>';
                }
            })
                .catch(error => {
                    console.error('Error loading student records:', error);
                    tbody.innerHTML = '<tr><td colspan="4" class="px-6 py-4 text-center text-red-500">Error loading student records</td></tr>';
                });
        }

        // Function to display student records
        function displayStudentRecords(students) {
            console.log('displayStudentRecords called with:', students);
            const tbody = document.getElementById('studentRecordsTableBody');
            console.log('Table body element:', tbody);
            
            if (!tbody) {
                console.error('studentRecordsTableBody not found!');
                return;
            }
            
            if (students.length === 0) {
                console.log('No students found, showing empty message');
                tbody.innerHTML = '<tr><td colspan="4" class="px-6 py-4 text-center text-gray-500">No completed students found</td></tr>';
                return;
            }
            
            console.log('Rendering', students.length, 'students');

            const htmlContent = students.map(student => `
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-user-graduate text-green-600 text-sm"></i>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-900">${student.name}</div>
                                <div class="text-sm text-gray-500">ID: ${student.student_id}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            <i class="fas fa-graduation-cap mr-1"></i>${student.program_name}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        ${new Date(student.certificate_issue_date).toLocaleDateString('en-US', {
                            year: 'numeric',
                            month: 'long',
                            day: 'numeric'
                        })}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <button onclick="viewStudentDetails(${student.id})" class="inline-flex items-center px-3 py-2 border border-transparent text-xs leading-4 font-medium rounded-md text-white bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200">
                            <i class="fas fa-eye mr-1"></i>View Details
                        </button>
                    </td>
                </tr>
            `).join('');
            
            console.log('Generated HTML content:', htmlContent);
            tbody.innerHTML = htmlContent;
            console.log('Table body innerHTML set, current content:', tbody.innerHTML);
        }

        // Function to view student details
        function viewStudentDetails(studentId) {
            document.getElementById('studentDetailsModal').classList.remove('hidden');
            loadStudentDetails(studentId);
        }

        // Function to close student details modal
        function closeStudentDetailsModal() {
            document.getElementById('studentDetailsModal').classList.add('hidden');
        }

        // Function to close student details modal when clicking outside
        function closeStudentDetailsModalOnOutside(event) {
            if (event.target === event.currentTarget) {
                closeStudentDetailsModal();
            }
        }

        // Function to load student details
        function loadStudentDetails(studentId) {
            const content = document.getElementById('studentDetailsContent');
            content.innerHTML = '<div class="text-center py-8"><div class="animate-spin rounded-full h-8 w-8 border-b-2 border-green-600 mx-auto"></div><p class="mt-2 text-gray-600">Loading student details...</p></div>';

            fetch(`/admin/student-details/${studentId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        displayStudentDetails(data.student);
                    } else {
                        content.innerHTML = '<div class="text-center py-8 text-red-500">Error loading student details</div>';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    content.innerHTML = '<div class="text-center py-8 text-red-500">Error loading student details</div>';
                });
        }

        // Function to display student details
        function displayStudentDetails(student) {
            const title = document.getElementById('studentDetailsTitle');
            const content = document.getElementById('studentDetailsContent');
            
            title.textContent = `${student.name} - Complete Records`;
            
            content.innerHTML = `
                <div class="space-y-6">
                    <!-- Account Information -->
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg p-6">
                        <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-user mr-2 text-blue-600"></i>Account Information
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Full Name</label>
                                <p class="mt-1 text-sm text-gray-900">${student.name}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Student ID</label>
                                <p class="mt-1 text-sm text-gray-900">${student.student_id}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Email</label>
                                <p class="mt-1 text-sm text-gray-900">${student.email}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Phone</label>
                                <p class="mt-1 text-sm text-gray-900">${student.phone || 'N/A'}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Enrollment Date</label>
                                <p class="mt-1 text-sm text-gray-900">${new Date(student.enrollment_date).toLocaleDateString('en-US', {
                                    year: 'numeric',
                                    month: 'long',
                                    day: 'numeric'
                                })}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Status</label>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i>Completed
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Program Information -->
                    <div class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-lg p-6">
                        <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-graduation-cap mr-2 text-purple-600"></i>Program Information
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Program Name</label>
                                <p class="mt-1 text-sm text-gray-900">${student.program_name}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Duration</label>
                                <p class="mt-1 text-sm text-gray-900">${student.program_duration} sessions</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Instructor Name</label>
                                <p class="mt-1 text-sm text-gray-900">${student.certificate_instructor || 'N/A'}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Attendance Records -->
                    <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-lg p-6">
                        <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-calendar-check mr-2 text-green-600"></i>Attendance Records
                        </h4>
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Session Number</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Date Attended</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Amount Paid</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">OR/Reference Number</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Marked by</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    ${student.attendances.map(attendance => `
                                        <tr>
                                            <td class="px-4 py-2 text-sm text-gray-900 font-semibold">Session ${attendance.session_number}</td>
                                            <td class="px-4 py-2 text-sm text-gray-900">${new Date(attendance.date).toLocaleDateString('en-US', {
                                                year: 'numeric',
                                                month: 'long',
                                                day: 'numeric'
                                            })}</td>
                                            <td class="px-4 py-2">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${attendance.status === 'present' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">
                                                    <i class="fas ${attendance.status === 'present' ? 'fa-check' : 'fa-times'} mr-1"></i>
                                                    ${attendance.status.charAt(0).toUpperCase() + attendance.status.slice(1)}
                                                </span>
                                            </td>
                                            <td class="px-4 py-2 text-sm text-gray-900 font-semibold text-green-600">₱${parseFloat(attendance.amount_paid || 0).toFixed(2)}</td>
                                            <td class="px-4 py-2 text-sm text-gray-900 font-mono">${attendance.reference_number || 'N/A'}</td>
                                            <td class="px-4 py-2 text-sm text-gray-900">${attendance.marked_by || 'N/A'}</td>
                                        </tr>
                                    `).join('')}
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="bg-white rounded-lg p-4 border border-gray-200">
                                <div class="text-sm font-medium text-gray-500">Total Sessions</div>
                                <div class="text-2xl font-bold text-gray-900">${student.attendances.length}</div>
                            </div>
                            <div class="bg-white rounded-lg p-4 border border-gray-200">
                                <div class="text-sm font-medium text-gray-500">Present Sessions</div>
                                <div class="text-2xl font-bold text-green-600">${student.attendances.filter(a => a.status === 'present').length}</div>
                            </div>
                            <div class="bg-white rounded-lg p-4 border border-gray-200">
                                <div class="text-sm font-medium text-gray-500">Attendance Rate</div>
                                <div class="text-2xl font-bold text-blue-600">${Math.round((student.attendances.filter(a => a.status === 'present').length / student.attendances.length) * 100)}%</div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Records -->
                    <div class="bg-gradient-to-r from-yellow-50 to-orange-50 rounded-lg p-6">
                        <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-credit-card mr-2 text-yellow-600"></i>Payment Records
                        </h4>
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Method</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Reference/OR Number</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    ${student.payments.map(payment => `
                                        <tr>
                                            <td class="px-4 py-2 text-sm text-gray-900">${new Date(payment.created_at).toLocaleDateString('en-US', {
                                                year: 'numeric',
                                                month: 'short',
                                                day: 'numeric'
                                            })}</td>
                                            <td class="px-4 py-2 text-sm text-gray-900">₱${parseFloat(payment.amount).toFixed(2)}</td>
                                            <td class="px-4 py-2">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${payment.payment_method.toLowerCase() === 'gcash' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800'}">
                                                    ${payment.payment_method}
                                                </span>
                                            </td>
                                            <td class="px-4 py-2 text-sm text-gray-900">${payment.or_number || 'N/A'}</td>
                                            <td class="px-4 py-2">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${payment.status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'}">
                                                    <i class="fas ${payment.status === 'completed' ? 'fa-check' : 'fa-clock'} mr-1"></i>
                                                    ${payment.status.charAt(0).toUpperCase() + payment.status.slice(1)}
                                                </span>
                                            </td>
                                        </tr>
                                    `).join('')}
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="bg-white rounded-lg p-4 border border-gray-200">
                                <div class="text-sm font-medium text-gray-500">Total Payments</div>
                                <div class="text-2xl font-bold text-gray-900">₱${student.payments.reduce((sum, payment) => sum + parseFloat(payment.amount), 0).toFixed(2)}</div>
                            </div>
                            <div class="bg-white rounded-lg p-4 border border-gray-200">
                                <div class="text-sm font-medium text-gray-500">Payment Count</div>
                                <div class="text-2xl font-bold text-blue-600">${student.payments.length}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Certificate Information -->
                    <div class="bg-gradient-to-r from-indigo-50 to-blue-50 rounded-lg p-6">
                        <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-certificate mr-2 text-indigo-600"></i>Certificate Information
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Certificate Number</label>
                                <p class="mt-1 text-sm text-gray-900">${student.certificate_number}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Issue Date</label>
                                <p class="mt-1 text-sm text-gray-900">${new Date(student.certificate_issue_date).toLocaleDateString('en-US', {
                                    year: 'numeric',
                                    month: 'long',
                                    day: 'numeric'
                                })}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Issued By</label>
                                <p class="mt-1 text-sm text-gray-900">${student.certificate_issued_by}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Instructor</label>
                                <p class="mt-1 text-sm text-gray-900">${student.certificate_instructor || 'N/A'}</p>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }

        // Add search functionality for student records
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM loaded, setting up student records functionality...');
            
            // Test if the function is accessible
            if (typeof openStudentRecordsModal === 'function') {
                console.log('openStudentRecordsModal function is available');
            } else {
                console.error('openStudentRecordsModal function is NOT available');
            }
            
            const searchInput = document.getElementById('studentSearchInput');
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    const searchTerm = this.value.toLowerCase();
                    const rows = document.querySelectorAll('#studentRecordsTableBody tr');
                    
                    rows.forEach(row => {
                        const text = row.textContent.toLowerCase();
                        if (text.includes(searchTerm)) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    });
                });
            }
        });

        // Handle certificate generation form submission
        const generateForm = document.getElementById('generateCertificateForm');
        if (generateForm) {
            generateForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            console.log('Form submission started');
            
            const enrollmentId = this.dataset.enrollmentId;
            console.log('Enrollment ID:', enrollmentId);
            
            if (!enrollmentId) {
                alert('Enrollment ID not found. Please try again.');
                return;
            }
            
            // Show loading state
            const submitBtn = document.getElementById('generateSubmitBtn');
            const generateIcon = document.getElementById('generateIcon');
            const generateText = document.getElementById('generateText');
            const generateSpinner = document.getElementById('generateSpinner');
            
            submitBtn.disabled = true;
            generateIcon.classList.add('hidden');
            generateText.textContent = 'Generating...';
            generateSpinner.classList.remove('hidden');
            
            const formData = new FormData(this);
            
            // Get CSRF token with fallback
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                             document.querySelector('input[name="_token"]')?.value;
            
            console.log('CSRF Token found:', !!csrfToken);
            
            if (!csrfToken) {
                alert('CSRF token not found. Please refresh the page and try again.');
                // Reset loading state
                submitBtn.disabled = false;
                generateIcon.classList.remove('hidden');
                generateText.textContent = 'Generate Certificate';
                generateSpinner.classList.add('hidden');
                return;
            }
            
            fetch(`/admin/certificates/generate/${enrollmentId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    closeGenerateModal();
                    showSuccessMessage(data.message);
                    
                    // Update button state
                    const btn = document.querySelector(`[data-enrollment-id="${enrollmentId}"]`);
                    if (btn) {
                        btn.dataset.generated = 'true';
                        btn.textContent = 'Preview Certificate';
                        
                        // Move to issued tab
                        const row = btn.closest('tr');
                        setTimeout(() => {
                            moveToIssuedTab(row);
                        }, 1000);
                    }
                } else {
                    alert(data.message || 'Error generating certificate');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error generating certificate: ' + error.message);
            })
            .finally(() => {
                // Reset loading state
                submitBtn.disabled = false;
                generateIcon.classList.remove('hidden');
                generateText.textContent = 'Generate Certificate';
                generateSpinner.classList.add('hidden');
            });
        });
        } else {
            console.error('Generate certificate form not found');
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

        // Logout functionality
        document.getElementById('logoutButton').addEventListener('click', function(e) {
            e.preventDefault();
            document.getElementById('logout-form').submit();
        });

        // Search and Filter
        document.getElementById('searchInput').addEventListener('input', function() {
            let val = this.value.toLowerCase();
            document.querySelectorAll('#pendingTable tbody tr').forEach(row => {
                let name = row.getAttribute('data-name').toLowerCase();
                let id = row.getAttribute('data-id').toLowerCase();
                row.style.display = (name.includes(val) || id.includes(val)) ? '' : 'none';
            });
        });
        document.getElementById('programFilter').addEventListener('change', function() {
            let val = this.value;
            document.querySelectorAll('#pendingTable tbody tr').forEach(row => {
                row.style.display = (!val || row.getAttribute('data-program') === val) ? '' : 'none';
            });
        });

        window.addEventListener('DOMContentLoaded', () => {
            const sidebar = document.querySelector('.sidebar');
            const contentArea = document.querySelector('.content-area');
            const toggleIcon = document.querySelector('#toggleSidebar i');
            if (localStorage.getItem('sidebar-collapsed') === 'true') {
                sidebar.classList.add('sidebar-collapsed');
                contentArea.classList.remove('ml-1');
                contentArea.classList.add('ml-1');
                if (toggleIcon.classList.contains('fa-chevron-left')) {
                    toggleIcon.classList.remove('fa-chevron-left');
                    toggleIcon.classList.add('fa-chevron-right');
                }
            }
        });
    </script>

    <!-- Include Admin Profile Modal -->
    <?php echo $__env->make('Admin.Top.profile', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <!-- Student Records Modal -->
    <div id="studentRecordsModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full" style="z-index: 9999;" onclick="closeStudentRecordsModalOnOutside(event)">
        <div class="relative mx-auto shadow-xl rounded-xl bg-white max-w-6xl w-full mx-4 mt-10 mb-10" onclick="event.stopPropagation()">
            <div class="flex justify-between items-center p-6 bg-gradient-to-r from-purple-50 to-indigo-50 border-b border-gray-200">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-users text-purple-600"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800">Student Records - Completed Programs</h3>
                </div>
                <button onclick="closeStudentRecordsModal()" class="text-gray-500 hover:text-gray-700 transition-colors">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
            <div class="p-6">
                <div class="mb-6">
                    <div class="relative">
                        <input type="text" id="studentSearchInput" class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors" placeholder="Search students by name or ID">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white border border-gray-200 rounded-lg overflow-hidden">
                        <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider border-b border-gray-200">Student</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider border-b border-gray-200">Program</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider border-b border-gray-200">Certificate Issued</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider border-b border-gray-200">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200" id="studentRecordsTableBody">
                            <!-- Student records will be loaded here -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Individual Student Details Modal -->
    <div id="studentDetailsModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" onclick="closeStudentDetailsModalOnOutside(event)">
        <div class="relative mx-auto shadow-xl rounded-xl bg-white max-w-4xl w-full mx-4 mt-10 mb-10" onclick="event.stopPropagation()">
            <div class="flex justify-between items-center p-6 bg-gradient-to-r from-green-50 to-emerald-50 border-b border-gray-200">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-user-graduate text-green-600"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800" id="studentDetailsTitle">Student Details</h3>
                </div>
                <button onclick="closeStudentDetailsModal()" class="text-gray-500 hover:text-gray-700 transition-colors">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
            <div class="p-6" id="studentDetailsContent">
                <!-- Student details will be loaded here -->
            </div>
        </div>
    </div>

    <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" class="hidden">
        <?php echo csrf_field(); ?>
    </form>
    <?php echo $__env->yieldPushContent('scripts'); ?>
    
    <!-- Loading System Integration -->
    <?php echo $__env->make('partials.loading-integration', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</body>
</html><?php /**PATH C:\Users\gucor\OneDrive\Documents\Herd\Modular_System\resources\views/Admin/certificate.blade.php ENDPATH**/ ?>