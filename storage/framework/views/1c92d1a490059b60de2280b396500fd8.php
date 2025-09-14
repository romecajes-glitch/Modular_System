<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Students - Instructor</title>
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
    <?php echo $__env->make('Instructor.partials.loading-integration', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <div class="sidebar bg-blue-900 text-white w-56 flex flex-col">
            <?php echo $__env->make('Instructor.partials.navigation', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
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
                        My Students
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

            <!-- Students Content -->
            <div class="p-6">
                <div class="bg-white rounded-lg shadow">
                    <div class="p-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-800">Enrolled Students</h3>
                    </div>
                    <div class="p-4">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Program</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <?php $__empty_1 = true; $__currentLoopData = $enrolledStudents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $enrollment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-full flex items-center justify-center">
                                                    <?php if($enrollment->user && $enrollment->user->photo): ?>
                                                    <img src="<?php echo e(asset('storage/' . $enrollment->user->photo)); ?>" alt="Photo" class="w-12 h-12 rounded-full object-cover">
                                                    <?php else: ?>
                                                    <i class="fas fa-user text-blue-600"></i>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900"><?php echo e(ucwords($enrollment->full_name)); ?></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo e($enrollment->program->name ?? 'N/A'); ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo e($enrollment->email); ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <button data-student-id="<?php echo e($enrollment->id); ?>" class="view-details-btn text-blue-600 hover:text-blue-900">View Details</button>
                                        </td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">No enrolled students found.</td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Container -->
    <div id="studentDetailsModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-4xl shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <!-- Modal Header -->
                <div class="flex items-center justify-between p-4 border-b">
                    <h3 class="text-lg font-semibold text-gray-900">Student Details</h3>
                    <button id="closeModal" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <!-- Modal Body -->
                <div id="modalContent" class="p-6">
                    <!-- Student details will be loaded here -->
                    <p class="text-center text-gray-500">Loading...</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Profile Modal -->
    <?php echo $__env->make('Instructor.partials.profile-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" class="hidden">
        <?php echo csrf_field(); ?>
    </form>

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
                    console.log('Navigation clicked, showing loading...');
                    
                    // Create and show loading overlay immediately
                    const loadingOverlay = document.createElement('div');
                    loadingOverlay.id = 'navigation-loading';
                    loadingOverlay.style.cssText = `
                        position: fixed;
                        top: 0;
                        left: 0;
                        width: 100%;
                        height: 100%;
                        background: rgba(0, 0, 0, 0.5);
                        backdrop-filter: blur(8px);
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        z-index: 9999;
                    `;
                    
                    const loadingContent = document.createElement('div');
                    loadingContent.style.cssText = `
                        text-align: center;
                        color: white;
                    `;
                    
                    const loadingImg = document.createElement('img');
                    loadingImg.src = '/pictures/loading.png';
                    loadingImg.style.cssText = `
                        width: 150px;
                        height: 150px;
                        border-radius: 15px;
                        animation: spin 2s linear infinite;
                    `;
                    
                    // Add spin animation
                    const style = document.createElement('style');
                    style.textContent = `
                        @keyframes spin {
                            0% { transform: rotate(360deg); }
                            100% { transform: rotate(0deg); }
                        }
                    `;
                    document.head.appendChild(style);
                    
                    loadingContent.appendChild(loadingImg);
                    loadingOverlay.appendChild(loadingContent);
                    document.body.appendChild(loadingOverlay);
                    
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
                    // Special handling for students URLs - check if current path starts with students
                    if (url.includes('/instructor/students') && currentPath.startsWith('/instructor/students')) {
                        // Remove active class from all items
                        navItems.forEach(nav => nav.classList.remove('active-nav'));
                        // Add active class to students item
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

        // Helper function to capitalize each word
        function capitalizeWords(str) {
            if (!str || typeof str !== 'string') return '';
            return str.replace(/\b\w/g, l => l.toUpperCase());
        }

        // Modal functionality
        const modal = document.getElementById('studentDetailsModal');
        const modalContent = document.getElementById('modalContent');
        const closeModalBtn = document.getElementById('closeModal');

        document.querySelectorAll('.view-details-btn').forEach(button => {
            button.addEventListener('click', () => {
                const studentId = button.getAttribute('data-student-id');
                modalContent.innerHTML = '<p class="text-center text-gray-500">Loading...</p>';
                modal.classList.remove('hidden');

                fetch(`/instructor/students/${studentId}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    // Build modal content HTML from data
                    let html = `
                        <div class="bg-white rounded-lg">
                            <div class="p-6">
                                <div class="flex items-center mb-6">
                                    <div class="flex-shrink-0 h-16 w-16 bg-blue-100 rounded-full flex items-center justify-center">
                                        ${data.user && data.user.photo ? `<img src="/storage/${data.user.photo}" alt="Photo" class="w-16 h-16 rounded-full object-cover">` : '<i class="fas fa-user text-blue-600 text-2xl"></i>'}
                                    </div>
                                    <div class="ml-6">
                                        <h3 class="text-xl font-bold text-gray-900">${capitalizeWords(data.full_name)}</h3>
                                        <p class="text-gray-600">${data.email}</p>
                                        <p class="text-sm text-gray-500">${data.program ? data.program.name : 'N/A'}</p>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <h4 class="text-lg font-semibold text-gray-800 mb-4">Personal Information</h4>
                                        <div class="space-y-3">
                                            <div>
                                                <label class="text-sm font-medium text-gray-500">Full Name</label>
                                                <p class="text-gray-900">${capitalizeWords(data.full_name)}</p>
                                            </div>
                                            <div>
                                                <label class="text-sm font-medium text-gray-500">Email</label>
                                                <p class="text-gray-900">${data.email}</p>
                                            </div>
                                            <div>
                                                <label class="text-sm font-medium text-gray-500">Phone</label>
                                                <p class="text-gray-900">${data.phone ?? 'N/A'}</p>
                                            </div>
                                            <div>
                                                <label class="text-sm font-medium text-gray-500">Birthdate</label>
                                                <p class="text-gray-900">${data.birthdate ? new Date(data.birthdate).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' }) : 'N/A'}</p>
                                            </div>
                                            <div>
                                                <label class="text-sm font-medium text-gray-500">Address</label>
                                                <p class="text-gray-900">${data.address ? capitalizeWords(data.address) : 'N/A'}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <h4 class="text-lg font-semibold text-gray-800 mb-4">Program Information</h4>
                                        <div class="space-y-3">
                                            <div>
                                                <label class="text-sm font-medium text-gray-500">Program</label>
                                                <p class="text-gray-900">${data.program ? data.program.name : 'N/A'}</p>
                                            </div>
                                            <div>
                                                <label class="text-sm font-medium text-gray-500">Status</label>
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    ${data.status ? data.status.charAt(0).toUpperCase() + data.status.slice(1) : ''}
                                                </span>
                                            </div>
                                            <div>
                                                <label class="text-sm font-medium text-gray-500">Paid Sessions</label>
                                                <p class="text-gray-900">${data.paid_sessions ?? 0} of ${data.total_sessions ?? 0}</p>
                                            </div>
                                            <div>
                                                <label class="text-sm font-medium text-gray-500">Enrollment Date</label>
                                                <p class="text-gray-900">${new Date(data.created_at).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' })}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-8">
                                    <h4 class="text-lg font-semibold text-gray-800 mb-4">Attendance History</h4>
                                    <div class="overflow-x-auto">
                                        <table class="min-w-full divide-y divide-gray-200">
                                            <thead class="bg-gray-50">
                                                <tr>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Session #</th>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">OR Number</th>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reference #</th>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Marked By</th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white divide-y divide-gray-200">
                    `;

                    if (data.attendances && data.attendances.length > 0) {
                        data.attendances.sort((a, b) => (a.session_number || 0) - (b.session_number || 0));
                        data.attendances.forEach(attendance => {
                            // Use the reference_number if available, otherwise use or_number
                            const orNumber = attendance.or_number || 'N/A';
                            const referenceNumber = attendance.reference_number || 'N/A';

                            // Get amount from program data if available
                            const amount = (data.program && data.program.price_per_session) ?
                                `₱${parseFloat(data.program.price_per_session).toLocaleString('en-US', { minimumFractionDigits: 2 })}` : 'N/A';

                            // Get marked by info - show user name if available, otherwise show 'System'
                            let markedBy = 'System';
                            if (attendance.marked_by_user && attendance.marked_by_user.name) {
                                markedBy = attendance.marked_by_user.name;
                            }

                            // Get time from created_at
                            const time = attendance.created_at ?
                                new Date(attendance.created_at).toLocaleTimeString('en-US', {
                                    hour: 'numeric',
                                    minute: '2-digit',
                                    hour12: true
                                }) : 'N/A';

                            html += `
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${attendance.session_number || 'N/A'}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${new Date(attendance.session_date).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' })}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${time}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${attendance.status === 'present' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">
                                            ${attendance.status.charAt(0).toUpperCase() + attendance.status.slice(1)}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${orNumber}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${referenceNumber}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${amount}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${markedBy}</td>
                                </tr>
                            `;
                        });
                    } else {
                        html += `
                            <tr>
                                <td colspan="8" class="px-6 py-4 text-center text-gray-500">No attendance records found.</td>
                            </tr>
                        `;
                    }

                    html += `
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;

                    modalContent.innerHTML = html;
                })
                .catch(error => {
                    modalContent.innerHTML = '<p class="text-center text-red-500">Failed to load student details.</p>';
                    console.error('Error fetching student details:', error);
                });
            });
        });

        closeModalBtn.addEventListener('click', () => {
            modal.classList.add('hidden');
        });

        // Close modal when clicking outside the modal content
        modal.addEventListener('click', (event) => {
            if (event.target === modal) {
                modal.classList.add('hidden');
            }
        });
    </script>
</body>
</html>
<?php /**PATH C:\Users\gucor\OneDrive\Documents\Herd\Modular_System\resources\views/Instructor/students.blade.php ENDPATH**/ ?>