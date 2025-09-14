<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Admin - User Management</title>
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
                        User Management
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
                            <a href="#" id="profileLink" class="block px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                                <i class="fas fa-user mr-3"></i>Profile
                            </a>
                            <a href="#" id="logoutButton" class="block px-4 py-3 text-sm text-gray-700 hover:bg-red-50 hover:text-red-600 transition-colors border-t border-gray-100">
                                <i class="fas fa-sign-out-alt mr-3"></i>Log Out
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- User Management Content -->
            <div class="p-6">
                <div class="bg-white rounded-lg shadow">
                    <!-- User Type Tabs -->
                    <div class="border-b border-gray-200">
                        <nav class="flex -mb-px">
                            <button id="studentsTab" class="tab-button mr-8 py-4 px-6 border-b-2 font-medium text-sm border-blue-500 text-blue-600">
                                Students
                            </button>
                            <button id="instructorsTab" class="tab-button mr-8 py-4 px-1 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                                Instructors
                            </button>
                        </nav>
                    </div>

                    <!-- Tables for each user type -->
                    <div class="p-4">
                        <!-- Students Table -->
                        <div id="studentsTable">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-medium">Student List</h3>
                                <div class="flex items-center space-x-2">
                                    <input type="text" id="studentSearchInput" placeholder="Search students..." class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <button id="studentSearchBtn" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User Name</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <?php $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center space-x-3">
                                                    <div class="w-10 h-10 rounded-full overflow-hidden">
                                                        <?php if($student->photo): ?>
                                                            <img src="<?php echo e(asset('storage/' . $student->photo)); ?>" alt="Profile Photo" class="w-10 h-10 object-cover rounded-full">
                                                        <?php else: ?>
                                                            <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                                                                <i class="fas fa-user text-white text-sm"></i>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="text-sm font-medium text-gray-900"><?php echo e(ucwords($student->name)); ?></div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo e($student->email); ?></td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <?php if($student->status == 'active'): ?>
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    Active
                                                </span>
                                                <?php else: ?>
                                                <div class="flex flex-col">
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 mb-1">
                                                        Inactive
                                                    </span>
                                                    <?php if($student->scheduled_deletion_at): ?>
                                                    <span class="text-xs text-red-600 countdown-timer" data-deletion-date="<?php echo e($student->scheduled_deletion_at->toISOString()); ?>">
                                                        Deleting in: <span class="font-semibold">-- days</span>
                                                    </span>
                                                    <?php endif; ?>
                                                </div>
                                                <?php endif; ?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <button class="text-blue-600 hover:text-blue-900 mr-2 edit-user-btn" data-user-id="<?php echo e($student->id); ?>" data-user-type="student">
                                                    <i class="fas fa-edit"></i> Edit
                                                </button>
                                            </td>
                                        </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Instructors Table (hidden by default) -->
                        <div id="instructorsTable" class="hidden">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-medium">Instructor List</h3>
                                <div class="flex items-center space-x-2">
                                    <input type="text" id="instructorSearchInput" placeholder="Search instructors..." class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <button id="instructorSearchBtn" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                                        <i class="fas fa-search"></i>
                                    </button>
                                    <button id="addInstructorBtn" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                                        <i class="fas fa-plus mr-2"></i>Add Instructor
                                    </button>
                                </div>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User Name</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Program Handled</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <?php $__currentLoopData = $instructors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $instructor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center space-x-3">
                                                    <div class="w-10 h-10 rounded-full overflow-hidden">
                                                        <?php if($instructor->photo): ?>
                                                            <img src="<?php echo e(asset('storage/' . $instructor->photo)); ?>" alt="Profile Photo" class="w-10 h-10 object-cover rounded-full">
                                                        <?php else: ?>
                                                            <div class="w-10 h-10 bg-gradient-to-r from-cyan-500 to-blue-600 rounded-full flex items-center justify-center">
                                                                <i class="fas fa-user text-white text-sm"></i>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="text-sm font-medium text-gray-900"><?php echo e(ucwords($instructor->name)); ?></div>
                                                </div>
                                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo e($instructor->email); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo e($instructor->program->name ?? 'No Program'); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?php if($instructor->status == 'active'): ?>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Active
                                </span>
                                <?php else: ?>
                                <div class="flex flex-col">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 mb-1">
                                        Inactive
                                    </span>
                                    <?php if($instructor->scheduled_deletion_at): ?>
                                    <span class="text-xs text-red-600 countdown-timer" data-deletion-date="<?php echo e($instructor->scheduled_deletion_at->toISOString()); ?>">
                                        Deleting in: <span class="font-semibold">-- days</span>
                                    </span>
                                    <?php endif; ?>
                                </div>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <button class="text-blue-600 hover:text-blue-900 mr-2 edit-user-btn" data-user-id="<?php echo e($instructor->id); ?>" data-user-type="instructor">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                            </td>
                        </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php echo $__env->make('Admin.edit_user_modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->make('Admin.instructor_modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->make('Admin.Top.profile', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php $__env->startPush('scripts'); ?>
    <script>
        // Function to close all other modals
        function closeAllOtherModals() {
            // Close instructor modal
            const instructorModal = document.getElementById('addInstructorModal');
            if (instructorModal && !instructorModal.classList.contains('hidden')) {
                instructorModal.classList.add('hidden');
            }
            
            // Close edit user modal
            const editUserModal = document.getElementById('editUserModal');
            if (editUserModal && !editUserModal.classList.contains('hidden')) {
                editUserModal.classList.add('hidden');
            }
            
            // Close profile modal
            const profileModal = document.getElementById('profileModal');
            if (profileModal && !profileModal.classList.contains('hidden')) {
                profileModal.classList.add('hidden');
            }
        }

        // Initialize user management loading system
        function initUserManagementLoading() {
            // Wait for loading manager to be available
            const waitForLoadingManager = () => {
                if (typeof window.loadingManager !== 'undefined') {
                    setupUserManagementLoading();
                } else {
                    setTimeout(waitForLoadingManager, 100);
                }
            };
            waitForLoadingManager();
        }

        function setupUserManagementLoading() {
            console.log('Setting up user management loading...');
            console.log('window.loadingManager exists:', typeof window.loadingManager);
            
            // Add loading for navigation links
            const navItems = document.querySelectorAll('.nav-item');
            console.log('Navigation items found:', navItems.length);
            navItems.forEach(link => {
                link.addEventListener('click', function(e) {
                    console.log('Navigation item clicked');
                    if (typeof window.loadingManager !== 'undefined') {
                        const navText = this.querySelector('.nav-text').textContent.trim();
                        console.log('Showing loading for:', navText);
                        window.loadingManager.show('Loading ' + navText, 'Please wait while we navigate to ' + navText);
                    } else {
                        console.error('loadingManager not available');
                    }
                });
            });

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
                    window.addEventListener('load', function() {
                        setTimeout(() => {
                            window.loadingManager.hide();
                        }, 500);
                    });
                }
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Initialize user management loading system
            initUserManagementLoading();
            
            // Profile link functionality - ensure it runs after all other scripts
            setTimeout(() => {
                const profileLink = document.getElementById('profileLink');
                console.log('Profile link found:', !!profileLink);
                if (profileLink) {
                    // Remove any existing event listeners to prevent conflicts
                    profileLink.removeEventListener('click', handleProfileClick);
                    profileLink.addEventListener('click', handleProfileClick);
                } else {
                    console.error('Profile link not found!');
                }
                
                // Check if profile modal exists
                const profileModal = document.getElementById('profileModal');
                console.log('Profile modal found:', !!profileModal);
            }, 100);
            
            // Profile click handler function
            function handleProfileClick(e) {
                e.preventDefault();
                e.stopPropagation();
                console.log('Profile link clicked');
                console.log('window.openProfileModal exists:', typeof window.openProfileModal);
                
                // Close any other open modals first
                closeAllOtherModals();
                
                if (window.openProfileModal) {
                    console.log('Calling openProfileModal');
                    window.openProfileModal();
                } else {
                    console.error('openProfileModal not available');
                    // Try to wait for it
                    setTimeout(() => {
                        if (window.openProfileModal) {
                            window.openProfileModal();
                        } else {
                            console.error('openProfileModal still not available after delay');
                        }
                    }, 500);
                }
            }

            // Admin dropdown toggle
            document.getElementById('adminDropdown').addEventListener('click', function(e) {
                e.stopPropagation();
                document.getElementById('dropdownMenu').classList.toggle('hidden');
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', function(e) {
                // Check if click was outside the dropdown
                if (!document.getElementById('adminDropdown').contains(e.target)) {
                    document.getElementById('dropdownMenu').classList.add('hidden');
                }
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
                        // Navigation loading is now handled by the standard loading system
                        window.location.href = url;
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

            // User Type Tab switching functionality
            const userTypeTabs = ['students', 'instructors'];
            userTypeTabs.forEach(tab => {
                const tabElement = document.getElementById(`${tab}Tab`);
                if (tabElement) {
                    tabElement.addEventListener('click', function() {
                        // Update active tab styling
                        document.querySelectorAll('.tab-button').forEach(btn => {
                            btn.classList.remove('border-blue-500', 'text-blue-600');
                            btn.classList.add('border-transparent', 'text-gray-500');
                        });
                        this.classList.add('border-blue-500', 'text-blue-600');
                        this.classList.remove('border-transparent', 'text-gray-500');

                        // Show/hide tables
                        userTypeTabs.forEach(t => {
                            const table = document.getElementById(`${t}Table`);
                            if (table) {
                                if (t === tab) {
                                    table.classList.remove('hidden');
                                } else {
                                    table.classList.add('hidden');
                                }
                            }
                        });
                    });
                }
            });

            // Logout functionality
            const logoutButton = document.getElementById('logoutButton');
            if (logoutButton) {
                logoutButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    document.getElementById('logout-form').submit();
                });
            }

            // Load sidebar state
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

        // Instructor Modal Functionality
        const addInstructorBtn = document.getElementById('addInstructorBtn');
        const addInstructorModal = document.getElementById('addInstructorModal');
        const closeInstructorModal = document.getElementById('closeInstructorModal');
        const cancelInstructorBtn = document.getElementById('cancelInstructorBtn');
        const addInstructorForm = document.getElementById('addInstructorForm');

        // Open instructor modal
        addInstructorBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            console.log('Opening instructor modal');
            
            // Close any other open modals first
            closeAllOtherModals();
            
            addInstructorModal.classList.remove('hidden');
        });

        // Close instructor modal
        function closeInstructorModalFunction() {
            addInstructorModal.classList.add('hidden');
            addInstructorForm.reset();
            clearInstructorErrors();
        }

        closeInstructorModal.addEventListener('click', closeInstructorModalFunction);
        cancelInstructorBtn.addEventListener('click', closeInstructorModalFunction);

        // Close instructor modal when clicking outside
        if (addInstructorModal) {
            addInstructorModal.addEventListener('click', function(event) {
                // Check if click is on the backdrop (the modal container itself)
                if (event.target === addInstructorModal) {
                    closeInstructorModalFunction();
                }
            });
            
            // Alternative approach: use document click with modal check
            document.addEventListener('click', function(event) {
                // Only close if modal is open and click is outside modal content
                if (!addInstructorModal.classList.contains('hidden')) {
                    const modalContent = addInstructorModal.querySelector('.relative');
                    if (modalContent && !modalContent.contains(event.target) && !addInstructorBtn.contains(event.target)) {
                        console.log('Closing modal due to document click outside');
                        closeInstructorModalFunction();
                    }
                }
            });
        } else {
            console.error('addInstructorModal element not found');
        }

        // Clear instructor error messages
        function clearInstructorErrors() {
            const errorElements = [
                'instructorFirstNameError',
                'instructorLastNameError', 
                'instructorMiddleNameError',
                'instructorSuffixNameError',
                'instructorProgramHandledError',
                'instructorEmailError',
                'instructorBirthdateError',
                'instructorPasswordError'
            ];
            
            errorElements.forEach(id => {
                const element = document.getElementById(id);
                if (element) {
                    element.classList.add('hidden');
                    element.textContent = '';
                }
            });
            
            // Clear general error
            const generalError = document.getElementById('instructorGeneralError');
            if (generalError) {
                generalError.classList.add('hidden');
            }
        }


        // Instructor form submission
        addInstructorForm.addEventListener('submit', async function(e) {
            e.preventDefault();

            // Generate username as full name without spaces, lowercase, only first, last, suffix
            const firstName = document.getElementById('instructorFirstName').value.trim();
            const lastName = document.getElementById('instructorLastName').value.trim();
            const suffixName = document.getElementById('instructorSuffixName').value.trim();
            let username = firstName + lastName;
            if (suffixName) {
                username += suffixName;
            }
            username = username.toLowerCase().replace(/\s+/g, ''); // Remove all spaces
            document.getElementById('instructorUsername').value = username;

            // Set password to birthdate
            const birthdate = document.getElementById('instructorBirthdate').value;
            document.getElementById('instructorPassword').value = birthdate;

            const formData = new FormData(addInstructorForm);
            const saveBtn = document.getElementById('saveInstructorBtn');
            const originalText = saveBtn.innerHTML;

            // Disable button and show loading
            saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Saving...';
            saveBtn.disabled = true;

            try {
                const response = await fetch('<?php echo e(route("admin.users.store")); ?>', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                const data = await response.json();

                if (response.ok) {
                    // Close the main instructor modal
                    closeInstructorModalFunction();
                    
                    // Refresh the instructor list
                    refreshInstructorList();
                } else {
                    // Handle validation errors

                    if (data.errors) {
                        // Clear all errors first
                        clearInstructorErrors();
                        
                        if (data.errors.first_name) {
                            const errorEl = document.getElementById('instructorFirstNameError');
                            if (errorEl) {
                                errorEl.textContent = data.errors.first_name[0];
                                errorEl.classList.remove('hidden');
                            }
                        }
                        if (data.errors.last_name) {
                            const errorEl = document.getElementById('instructorLastNameError');
                            if (errorEl) {
                                errorEl.textContent = data.errors.last_name[0];
                                errorEl.classList.remove('hidden');
                            }
                        }
                        if (data.errors.middle_name) {
                            const errorEl = document.getElementById('instructorMiddleNameError');
                            if (errorEl) {
                                errorEl.textContent = data.errors.middle_name[0];
                                errorEl.classList.remove('hidden');
                            }
                        }
                        if (data.errors.suffix_name) {
                            const errorEl = document.getElementById('instructorSuffixNameError');
                            if (errorEl) {
                                errorEl.textContent = data.errors.suffix_name[0];
                                errorEl.classList.remove('hidden');
                            }
                        }
                        if (data.errors.email) {
                            const errorEl = document.getElementById('instructorEmailError');
                            if (errorEl) {
                                errorEl.textContent = data.errors.email[0];
                                errorEl.classList.remove('hidden');
                            }
                        }
                        if (data.errors.password) {
                            const errorEl = document.getElementById('instructorPasswordError');
                            if (errorEl) {
                                errorEl.textContent = data.errors.password[0];
                                errorEl.classList.remove('hidden');
                            }
                        }
                        if (data.errors.birthdate) {
                            const errorEl = document.getElementById('instructorBirthdateError');
                            if (errorEl) {
                                errorEl.textContent = data.errors.birthdate[0];
                                errorEl.classList.remove('hidden');
                            }
                        }
                        if (data.errors.program_handled) {
                            const errorEl = document.getElementById('instructorProgramHandledError');
                            if (errorEl) {
                                errorEl.textContent = data.errors.program_handled[0];
                                errorEl.classList.remove('hidden');
                            }
                        }
                        if (data.errors.role) {
                            // Role error - show as general error since role is hidden
                            const generalError = document.getElementById('instructorGeneralError');
                            const generalErrorText = document.getElementById('instructorGeneralErrorText');
                            if (generalError && generalErrorText) {
                                generalErrorText.textContent = 'Role validation error: ' + data.errors.role[0];
                                generalError.classList.remove('hidden');
                            }
                        }
                    } else {
                        // Show general error for non-validation errors
                        const generalError = document.getElementById('instructorGeneralError');
                        const generalErrorText = document.getElementById('instructorGeneralErrorText');
                        if (generalError && generalErrorText) {
                            generalErrorText.textContent = data.message || 'An error occurred. Please try again.';
                            generalError.classList.remove('hidden');
                        }
                    }
                }
            } catch (error) {
                console.error('Error:', error);
                // Show error in general error display
                const generalError = document.getElementById('instructorGeneralError');
                const generalErrorText = document.getElementById('instructorGeneralErrorText');
                if (generalError && generalErrorText) {
                    generalErrorText.textContent = 'Network error occurred. Please check your connection and try again.';
                    generalError.classList.remove('hidden');
                }
            } finally {
                // Re-enable button
                saveBtn.innerHTML = originalText;
                saveBtn.disabled = false;
            }
        });



        // Copy to clipboard function for individual fields
        function copyToClipboard(elementId) {
            const element = document.getElementById(elementId);
            if (element) {
                const text = element.textContent;
                navigator.clipboard.writeText(text).then(function() {
                    // Show a brief success message
                    const button = event.target.closest('button');
                    const originalIcon = button.innerHTML;
                    button.innerHTML = '<i class="fas fa-check"></i>';
                    button.classList.add('bg-green-600');
                    button.classList.remove('bg-blue-600');
                    
                    setTimeout(function() {
                        button.innerHTML = originalIcon;
                        button.classList.remove('bg-green-600');
                        button.classList.add('bg-blue-600');
                    }, 2000);
                }).catch(function(err) {
                    console.error('Could not copy text: ', err);
                    alert('Failed to copy to clipboard');
                });
            }
        }


        // Function to refresh the instructor list
        function refreshInstructorList() {
            // Get the current search term
            const searchInput = document.getElementById('instructorSearchInput');
            const searchTerm = searchInput ? searchInput.value : '';
            
            // Make AJAX request to refresh the instructor list
            fetch('<?php echo e(route("admin.users")); ?>?search=' + encodeURIComponent(searchTerm), {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.text())
            .then(html => {
                // Find the instructor table container and replace its content
                const instructorTableContainer = document.querySelector('#instructorsTable');
                if (instructorTableContainer) {
                    // Create a temporary div to parse the response
                    const tempDiv = document.createElement('div');
                    tempDiv.innerHTML = html;
                    
                    // Find the instructor table in the response
                    const newInstructorTable = tempDiv.querySelector('#instructorsTable');
                    if (newInstructorTable) {
                        instructorTableContainer.innerHTML = newInstructorTable.innerHTML;
                        console.log('Instructor list refreshed successfully');
                    }
                } else {
                    // Fallback: reload the page
                    console.log('Instructor table container not found, reloading page');
                    window.location.reload();
                }
            })
            .catch(error => {
                console.error('Error refreshing instructor list:', error);
                // Fallback: reload the page
                window.location.reload();
            });
        }

        // Edit User Modal Functionality
        const editUserModal = document.getElementById('editUserModal');
        const closeEditUserModal = document.getElementById('closeEditUserModal');
        const cancelEditUserBtn = document.getElementById('cancelEditUserBtn');
        const editUserForm = document.getElementById('editUserForm');
        const resetPasswordBtn = document.getElementById('resetPasswordBtn');

        // Check if modal elements exist
        if (!editUserModal) {
            console.error('Edit modal element not found!');
        }

        // Open edit user modal
        document.querySelectorAll('.edit-user-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                const userId = this.getAttribute('data-user-id');
                const userType = this.getAttribute('data-user-type');

                console.log('Edit button clicked:', { userId, userType });
                
                // Close any other open modals first
                closeAllOtherModals();

                // Fetch user data via AJAX
                fetch(`/admin/users/${userId}`, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => {
                    console.log('Response status:', response.status);
                    return response.json();
                })
                .then(data => {
                    console.log('Response data:', data);
                    
                    if (data.success) {
                        const user = data.user;
                        console.log('User data:', user);

                        // Populate modal fields
                        const fullNameEl = document.getElementById('userFullName');
                        const usernameEl = document.getElementById('userUsername');
                        const roleEl = document.getElementById('userRole');
                        const emailEl = document.getElementById('userEmail');
                        const phoneEl = document.getElementById('userPhone');
                        const statusEl = document.getElementById('userStatus');
                        const createdAtEl = document.getElementById('userCreatedAt');
                        const lastLoginEl = document.getElementById('userLastLogin');

                        if (fullNameEl) fullNameEl.value = user.name.charAt(0).toUpperCase() + user.name.slice(1).toLowerCase();
                        if (usernameEl) usernameEl.value = user.username;
                        if (roleEl) roleEl.value = user.role;
                        if (emailEl) emailEl.value = user.email;
                        if (phoneEl) phoneEl.value = user.phone || '';
                        if (statusEl) statusEl.value = user.status;
                        if (createdAtEl) createdAtEl.value = new Date(user.created_at).toLocaleDateString();
                        if (lastLoginEl) lastLoginEl.value = user.last_login ? new Date(user.last_login).toLocaleDateString() : 'Never';

                        // Set form action
                        editUserForm.action = `/admin/users/${userId}`;

                        // Show edit modal using the same working approach
                        console.log('Opening edit user modal...');
                        
                        // Create a new working edit modal
                        const newEditModal = document.createElement('div');
                        newEditModal.id = 'workingEditModal';
                        newEditModal.style.cssText = `
                            position: fixed;
                            top: 0;
                            left: 0;
                            width: 100%;
                            height: 100%;
                            background: rgba(0, 0, 0, 0.5);
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            z-index: 99998;
                        `;
                        
                        // Copy the content from the original edit modal
                        newEditModal.innerHTML = editUserModal.innerHTML;
                        
                        // Populate the form fields with user data in the new modal
                        const newFullNameEl = newEditModal.querySelector('#userFullName');
                        const newUsernameEl = newEditModal.querySelector('#userUsername');
                        const newRoleEl = newEditModal.querySelector('#userRole');
                        const newEmailEl = newEditModal.querySelector('#userEmail');
                        const newPhoneEl = newEditModal.querySelector('#userPhone');
                        const newStatusEl = newEditModal.querySelector('#userStatus');
                        const newCreatedAtEl = newEditModal.querySelector('#userCreatedAt');
                        const newLastLoginEl = newEditModal.querySelector('#userLastLogin');
                        const newEditForm = newEditModal.querySelector('#editUserForm');

                        if (newFullNameEl) newFullNameEl.value = user.name.charAt(0).toUpperCase() + user.name.slice(1).toLowerCase();
                        if (newUsernameEl) newUsernameEl.value = user.username;
                        if (newRoleEl) newRoleEl.value = user.role;
                        if (newEmailEl) newEmailEl.value = user.email;
                        if (newPhoneEl) newPhoneEl.value = user.phone || '';
                        if (newStatusEl) newStatusEl.value = user.status;
                        if (newCreatedAtEl) newCreatedAtEl.value = new Date(user.created_at).toLocaleDateString();
                        if (newLastLoginEl) newLastLoginEl.value = user.last_login ? new Date(user.last_login).toLocaleDateString() : 'Never';
                        if (newEditForm) newEditForm.action = `/admin/users/${userId}`;
                        
                        // Update close button event handlers in the new modal
                        const newCloseBtn = newEditModal.querySelector('#closeEditUserModal');
                        const newCancelBtn = newEditModal.querySelector('#cancelEditUserBtn');
                        
                        if (newCloseBtn) {
                            newCloseBtn.addEventListener('click', closeEditUserModalFunction);
                        }
                        if (newCancelBtn) {
                            newCancelBtn.addEventListener('click', closeEditUserModalFunction);
                        }
                        
                        // Add click outside to close functionality
                        newEditModal.addEventListener('click', function(e) {
                            if (e.target === newEditModal) {
                                closeEditUserModalFunction();
                            }
                        });
                        
                        document.body.appendChild(newEditModal);
                        console.log('Working edit modal created with data!');
                    } else {
                        console.error('API returned success: false', data);
                        alert('Error loading user data: ' + (data.message || 'Unknown error'));
                    }
                })
                .catch(error => {
                    console.error('Fetch error:', error);
                    alert('Error loading user data: ' + error.message);
                });
            });
        });

        // Close edit user modal
        function closeEditUserModalFunction() {
            // Close the working edit modal
            const workingEditModal = document.getElementById('workingEditModal');
            if (workingEditModal) {
                workingEditModal.remove();
            }
            
            // Also hide the original modal just in case
            editUserModal.classList.add('hidden');
            editUserModal.style.display = 'none';
            editUserForm.reset();
        }

        closeEditUserModal.addEventListener('click', closeEditUserModalFunction);
        cancelEditUserBtn.addEventListener('click', closeEditUserModalFunction);

        // Close edit user modal when clicking outside
        window.addEventListener('click', function(event) {
            if (event.target === editUserModal) {
                closeEditUserModalFunction();
            }
        });

        // Reset password functionality
        resetPasswordBtn.addEventListener('click', function() {
            // Show password confirmation modal for password reset
            document.getElementById('passwordConfirmTitle').textContent = 'Confirm Password Reset';
            document.getElementById('passwordConfirmMessage').textContent = 'Please enter your password to confirm resetting this user\'s password.';
            document.getElementById('passwordConfirmationModal').classList.remove('hidden');
            document.getElementById('adminPassword').focus();

        // Store the action type for the confirmation handler
        window.pendingAction = 'reset_password';
    });

    // Handle edit user form submission
    editUserForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const formData = new FormData(editUserForm);
        const saveBtn = document.getElementById('saveEditUserBtn');
        const originalText = saveBtn.innerHTML;

        // Disable button and show loading
        saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Saving...';
        saveBtn.disabled = true;

        try {
            const response = await fetch(editUserForm.action, {
                method: 'PUT',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            const data = await response.json();

            if (response.ok && data.success) {
                alert('User updated successfully!');
                closeEditUserModalFunction();
                location.reload(); // Reload to show updated data
            } else {
                alert('Error updating user: ' + (data.message || 'Unknown error'));
            }
        } catch (error) {
            console.error('Error updating user:', error);
            alert('Error updating user: ' + error.message);
        } finally {
            // Re-enable button
            saveBtn.innerHTML = originalText;
            saveBtn.disabled = false;
        }
    });

        // Handle password confirmation form submission
        document.getElementById('passwordConfirmationForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            const passwordInput = document.getElementById('adminPassword');
            const passwordError = document.getElementById('passwordError');
            passwordError.classList.add('hidden');
            passwordError.textContent = '';

            const password = passwordInput.value.trim();
            if (!password) {
                passwordError.textContent = 'Password is required.';
                passwordError.classList.remove('hidden');
                return;
            }

            // Verify password via API
            try {
                const response = await fetch('/admin/verify-password', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ password })
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    // Password verified, proceed with pending action
                    document.getElementById('passwordConfirmationModal').classList.add('hidden');
                    passwordInput.value = '';

                    if (window.pendingAction === 'reset_password') {
                        // Proceed with password reset
                        const userId = editUserForm.action.split('/').pop();

                        const resetResponse = await fetch(`/admin/users/${userId}/reset-password`, {
                            method: 'POST',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        });

                        const resetData = await resetResponse.json();

                        if (resetResponse.ok && resetData.success) {
                            document.getElementById('reset-username').textContent = resetData.username;
                            document.getElementById('reset-password').textContent = resetData.new_password;
                            document.getElementById('passwordResetSuccessModal').classList.remove('hidden');
                        } else {
                            alert('Error resetting password: ' + (resetData.message || 'Unknown error'));
                        }
                    } else if (window.pendingAction === 'save_changes') {
                        // Proceed with saving user changes
                        saveUserChanges();
                    }
                } else {
                    passwordError.textContent = data.message || 'Incorrect password.';
                    passwordError.classList.remove('hidden');
                }
            } catch (error) {
                console.error('Error verifying password:', error);
                passwordError.textContent = 'An error occurred while verifying password.';
                passwordError.classList.remove('hidden');
            }
        });

        // Save user changes function
        async function saveUserChanges() {
            const formData = new FormData(editUserForm);
            const saveBtn = document.getElementById('saveEditUserBtn');
            const originalText = saveBtn.innerHTML;

            // Disable button and show loading
            saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Saving...';
            saveBtn.disabled = true;

            try {
                const response = await fetch(editUserForm.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'X-HTTP-Method-Override': 'PUT' // Laravel method spoofing for PUT
                    }
                });

                const data = await response.json();

                if (response.ok) {
                    alert('User updated successfully!');
                    closeEditUserModalFunction();
                    location.reload();
                } else {
                    // Handle validation errors
                    console.log('Validation errors:', data.errors);
                    if (data.errors) {
                        let errorMessage = 'Please fix the following errors:\n';
                        Object.values(data.errors).forEach(errorArray => {
                            errorArray.forEach(error => {
                                errorMessage += '• ' + error + '\n';
                            });
                        });
                        alert(errorMessage);
                    } else {
                        alert('An error occurred. Please check the console for details.');
                    }
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred while updating the user. Please try again.');
            } finally {
                // Re-enable button
                saveBtn.innerHTML = originalText;
                saveBtn.disabled = false;
            }
        }

        // Save changes button click handler
        saveEditUserBtn.addEventListener('click', function(e) {
            e.preventDefault();
            // Show password confirmation modal for saving changes
            document.getElementById('passwordConfirmTitle').textContent = 'Confirm Save Changes';
            document.getElementById('passwordConfirmMessage').textContent = 'Please enter your password to confirm saving changes.';
            document.getElementById('passwordConfirmationModal').classList.remove('hidden');
            document.getElementById('adminPassword').focus();

            // Store the action type for the confirmation handler
            window.pendingAction = 'save_changes';
        });

        // Cancel password confirmation modal
        document.getElementById('cancelPasswordConfirm').addEventListener('click', function() {
            document.getElementById('passwordConfirmationModal').classList.add('hidden');
            document.getElementById('adminPassword').value = '';
            document.getElementById('passwordError').classList.add('hidden');
        });

        // Close password confirmation modal when clicking outside
        window.addEventListener('click', function(event) {
            const modal = document.getElementById('passwordConfirmationModal');
            if (event.target === modal) {
                modal.classList.add('hidden');
                document.getElementById('adminPassword').value = '';
                document.getElementById('passwordError').classList.add('hidden');
            }
        });

        // Countdown timer functionality for inactive users
        function updateCountdownTimers() {
            const countdownElements = document.querySelectorAll('.countdown-timer');

            countdownElements.forEach(element => {
                const deletionDateStr = element.getAttribute('data-deletion-date');
                if (!deletionDateStr) return;

                const deletionDate = new Date(deletionDateStr);
                const now = new Date();
                const timeDiff = deletionDate - now;

                if (timeDiff <= 0) {
                    element.innerHTML = '<span class="font-semibold text-red-700">Deleting soon</span>';
                    return;
                }

                const daysRemaining = Math.ceil(timeDiff / (1000 * 60 * 60 * 24));

                if (daysRemaining === 1) {
                    element.innerHTML = 'Deleting in: <span class="font-semibold text-red-700">1 day</span>';
                } else {
                    element.innerHTML = `Deleting in: <span class="font-semibold text-red-700">${daysRemaining} days</span>`;
                }
            });
        }

        // Update countdown timers immediately and then every minute
        updateCountdownTimers();
        setInterval(updateCountdownTimers, 60000); // Update every minute

        // Search functionality for Students table
        const studentSearchInput = document.getElementById('studentSearchInput');
        const studentSearchBtn = document.getElementById('studentSearchBtn');
        const studentsTable = document.getElementById('studentsTable');
        const studentRows = studentsTable.querySelectorAll('tbody tr');

        function filterStudents() {
            const searchTerm = studentSearchInput.value.toLowerCase().trim();

            studentRows.forEach(row => {
                const name = row.cells[0].textContent.toLowerCase();
                const email = row.cells[1].textContent.toLowerCase();

                if (name.includes(searchTerm) || email.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        studentSearchBtn.addEventListener('click', filterStudents);

        // Allow Enter key to trigger search for students
        studentSearchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                filterStudents();
            }
        });

        // Search functionality for Instructors table
        const instructorSearchInput = document.getElementById('instructorSearchInput');
        const instructorsTable = document.getElementById('instructorsTable');
        const instructorRows = instructorsTable.querySelectorAll('tbody tr');

        function filterInstructors() {
            const searchTerm = instructorSearchInput.value.toLowerCase().trim();

            instructorRows.forEach(row => {
                const name = row.cells[0].textContent.toLowerCase();
                const email = row.cells[1].textContent.toLowerCase();
                const program = row.cells[2].textContent.toLowerCase();

                if (name.includes(searchTerm) || email.includes(searchTerm) || program.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        // Search on every keystroke for instructors
        instructorSearchInput.addEventListener('input', filterInstructors);

        // Allow search button to trigger search for instructors
        instructorSearchBtn.addEventListener('click', filterInstructors);

        // Allow Enter key to trigger search for instructors
        instructorSearchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                filterInstructors();
            }
        });
    </script>
    <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" class="hidden">
        <?php echo csrf_field(); ?>
    </form>
    <!-- Loading System Integration -->
    <?php echo $__env->make('partials.loading-integration', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</body>
</html><?php /**PATH C:\Users\gucor\OneDrive\Documents\Herd\Modular_System\resources\views/Admin/user_management.blade.php ENDPATH**/ ?>