<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Admin Enrolled Students</title>
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
                        Enrolled Students
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

            <!-- Enrolled Students Content -->
            <div class="p-6">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <!-- Header with search and add button -->
                        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                            <h2 class="text-2xl font-bold text-gray-800">Student Records</h2>
                            <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto items-center">
                                <div class="relative flex-grow">
                                    <input id="searchInput" type="text" placeholder="Search students..." class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                                </div>
                                <div class="flex space-x-2 items-center">
                                    <div class="relative">
                                        <button id="filterDropdownBtn" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center justify-center gap-2 transition-colors">
                                            <i class="fas fa-filter"></i>
                                            <span class="hidden sm:inline">Filter</span>
                                            <i class="fas fa-chevron-down ml-1"></i>
                                        </button>
                                        <div id="filterDropdownMenu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                                            <button id="filterAllOption" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Show All</button>
                                            <button id="filterProgramOption" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Filter by Program</button>
                                        </div>
                                    </div>
                                    <button id="manualEnrollmentBtn" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center justify-center gap-2 transition-colors">
                                        <i class="fas fa-user-plus"></i>
                                        <span>Manual Enrollment</span>
                                    </button>
                                </div>
                            </div>
                        </div>

                    <!-- Students Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Program</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Enrollment Date</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                                <tbody id="studentsTableBody" class="bg-white divide-y divide-gray-200">
                                    <?php $__empty_1 = true; $__currentLoopData = $enrolledStudents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <?php
                                            $enrollment = $student->enrollments->first();
                                        ?>
                                        <?php if($enrollment): ?>
                                        <tr class="hover:bg-gray-50" data-program="<?php echo e(strtolower($enrollment->program->name ?? '')); ?>">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-10 w-10 rounded-full overflow-hidden">
                                                        <?php if($enrollment->photo): ?>
                                                        <img src="<?php echo e(asset('storage/' . $enrollment->photo)); ?>" class="h-10 w-10 object-cover rounded-full" />
                                                        <?php elseif($enrollment->user && $enrollment->user->photo): ?>
                                                        <img src="<?php echo e(asset('storage/' . $enrollment->user->photo)); ?>" class="h-10 w-10 object-cover rounded-full" />
                                                        <?php else: ?>
                                                        <div class="h-10 w-10 bg-blue-100 flex items-center justify-center rounded-full">
                                                            <i class="fas fa-user text-blue-600"></i>
                                                        </div>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900"><?php echo e(ucwords($student->name)); ?></div>
                                                        <div class="text-sm text-gray-500"><?php echo e($student->email); ?></div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo e($enrollment->program->name ?? 'N/A'); ?></td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo e($enrollment->created_at ? $enrollment->created_at->format('Y-m-d') : 'N/A'); ?></td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Enrolled</span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <div class="flex justify-end gap-2">
                                                    <button class="text-blue-600 hover:text-blue-900" title="View Details" onclick="event.stopPropagation(); openStudentDetailsModal(<?php echo e($enrollment->id); ?>)">View Details
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr>
                                            <td colspan="6" class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">No enrolled students found.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                    </table>
                </div>

                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        const searchInput = document.getElementById('searchInput');
                        const filterDropdownBtn = document.getElementById('filterDropdownBtn');
                        const filterDropdownMenu = document.getElementById('filterDropdownMenu');
                        const filterAllOption = document.getElementById('filterAllOption');
                        const filterProgramOption = document.getElementById('filterProgramOption');

                        const tableBody = document.getElementById('studentsTableBody');

                        // Search function
                        searchInput.addEventListener('input', function () {
                            const filter = this.value.toLowerCase();
                            const rows = tableBody.querySelectorAll('tr');

                            rows.forEach(row => {
                                const name = row.querySelector('td:nth-child(1) .text-gray-900').textContent.toLowerCase();
                                const email = row.querySelector('td:nth-child(1) .text-gray-500').textContent.toLowerCase();
                                if (name.includes(filter) || email.includes(filter)) {
                                    row.style.display = '';
                                } else {
                                    row.style.display = 'none';
                                }
                            });
                        });

                        // Toggle dropdown
                        filterDropdownBtn.addEventListener('click', function (e) {
                            e.stopPropagation();
                            filterDropdownMenu.classList.toggle('hidden');
                        });

                        // Close dropdown when clicking outside
                        document.addEventListener('click', function () {
                            filterDropdownMenu.classList.add('hidden');
                        });

                        // Show All (reset filters)
                        filterAllOption.addEventListener('click', function () {
                            const rows = tableBody.querySelectorAll('tr');
                            rows.forEach(row => {
                                row.style.display = '';
                            });
                            filterDropdownMenu.classList.add('hidden');
                        });

                        // Filter by Program
                        filterProgramOption.addEventListener('click', function () {
                            const rows = tableBody.querySelectorAll('tr');
                            const programs = new Set();

                            // Collect all unique programs
                            rows.forEach(row => {
                                const program = row.getAttribute('data-program') || '';
                                if (program) programs.add(program);
                            });

                            // Create program selection modal or dropdown
                            showProgramFilterModal(Array.from(programs));
                            filterDropdownMenu.classList.add('hidden');
                        });



                        // Function to show program filter modal
                        function showProgramFilterModal(programs) {
                            // Remove existing modal if any
                            const existingModal = document.getElementById('programFilterModal');
                            if (existingModal) existingModal.remove();

                            const modal = document.createElement('div');
                            modal.id = 'programFilterModal';
                            modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
                            modal.innerHTML = `
                                <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6 relative">
                                    <button id="closeProgramFilterModal" class="absolute top-4 right-4 text-gray-600 hover:text-gray-900 text-2xl font-bold">&times;</button>
                                    <h3 class="text-xl font-semibold mb-4">Filter by Program</h3>
                                    <div class="space-y-2 max-h-60 overflow-y-auto">
                                        ${programs.map(program => `
                                            <label class="flex items-center space-x-2 cursor-pointer hover:bg-gray-50 p-2 rounded">
                                                <input type="checkbox" value="${program}" class="program-checkbox">
                                                <span class="text-sm">${program.charAt(0).toUpperCase() + program.slice(1)}</span>
                                            </label>
                                        `).join('')}
                                    </div>
                                    <div class="flex space-x-3 mt-6">
                                        <button id="applyProgramFilter" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">Apply Filter</button>
                                        <button id="cancelProgramFilter" class="flex-1 bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">Cancel</button>
                                    </div>
                                </div>
                            `;

                            document.body.appendChild(modal);

                            // Event listeners
                            document.getElementById('closeProgramFilterModal').addEventListener('click', () => modal.remove());
                            document.getElementById('cancelProgramFilter').addEventListener('click', () => modal.remove());
                            document.getElementById('applyProgramFilter').addEventListener('click', () => {
                                const selectedPrograms = Array.from(document.querySelectorAll('.program-checkbox:checked')).map(cb => cb.value);
                                filterByPrograms(selectedPrograms);
                                modal.remove();
                            });

                            // Close modal when clicking outside
                            modal.addEventListener('click', (e) => {
                                if (e.target === modal) modal.remove();
                            });
                        }



                        // Function to filter by programs
                        function filterByPrograms(selectedPrograms) {
                            const rows = tableBody.querySelectorAll('tr');
                            rows.forEach(row => {
                                const program = row.getAttribute('data-program') || '';
                                if (selectedPrograms.length === 0 || selectedPrograms.includes(program)) {
                                    row.style.display = '';
                                } else {
                                    row.style.display = 'none';
                                }
                            });
                        }


                    });
                </script>

                    <!-- Pagination -->
                    <?php if($enrolledStudents->total() > 0): ?>
                    <div class="flex items-center justify-between mt-6">
                        <div class="text-sm text-gray-500">
                            Showing <span class="font-medium"><?php echo e($enrolledStudents->firstItem() ?? 0); ?></span> to <span class="font-medium"><?php echo e($enrolledStudents->lastItem() ?? 0); ?></span> of <span class="font-medium"><?php echo e($enrolledStudents->total()); ?></span> entries
                        </div>
                        <div class="flex space-x-2">
                            <?php if($enrolledStudents->onFirstPage()): ?>
                                <span class="px-3 py-1 border rounded-md text-sm font-medium text-gray-400 bg-gray-100 cursor-not-allowed">Previous</span>
                            <?php else: ?>
                                <a href="<?php echo e($enrolledStudents->previousPageUrl()); ?>" class="px-3 py-1 border rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">Previous</a>
                            <?php endif; ?>

                            <?php $__currentLoopData = $enrolledStudents->getUrlRange(1, $enrolledStudents->lastPage()); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if($page == $enrolledStudents->currentPage()): ?>
                                    <span class="px-3 py-1 border rounded-md text-sm font-medium text-white bg-blue-600"><?php echo e($page); ?></span>
                                <?php else: ?>
                                    <a href="<?php echo e($url); ?>" class="px-3 py-1 border rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50"><?php echo e($page); ?></a>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                            <?php if($enrolledStudents->hasMorePages()): ?>
                                <a href="<?php echo e($enrolledStudents->nextPageUrl()); ?>" class="px-3 py-1 border rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">Next</a>
                            <?php else: ?>
                                <span class="px-3 py-1 border rounded-md text-sm font-medium text-gray-400 bg-gray-100 cursor-not-allowed">Next</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php else: ?>
                    
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>

    <!-- Manual Enrollment Modal -->
    <div id="manualEnrollmentModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-4xl max-h-[90vh] overflow-y-auto p-6 relative">
            <button id="closeManualEnrollmentModal" class="absolute top-4 right-4 text-gray-600 hover:text-gray-900 text-2xl font-bold">
                &times;
            </button>
            <h3 class="text-xl font-semibold mb-4">Manual Enrollment</h3>

            <form id="manualEnrollmentForm" enctype="multipart/form-data" class="space-y-4">
                <?php echo csrf_field(); ?>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="first_name" class="block font-medium">First Name</label>
                        <input type="text" id="first_name" name="first_name" required class="w-full border border-gray-300 rounded px-3 py-2" />
                        <p class="text-red-600 text-sm mt-1 error-message" data-field="first_name"></p>
                    </div>
                    <div>
                        <label for="last_name" class="block font-medium">Last Name</label>
                        <input type="text" id="last_name" name="last_name" required class="w-full border border-gray-300 rounded px-3 py-2" />
                        <p class="text-red-600 text-sm mt-1 error-message" data-field="last_name"></p>
                    </div>
                    <div>
                        <label for="middle_name" class="block font-medium">Middle Name</label>
                        <input type="text" id="middle_name" name="middle_name" class="w-full border border-gray-300 rounded px-3 py-2" />
                        <p class="text-red-600 text-sm mt-1 error-message" data-field="middle_name"></p>
                    </div>
                    <div>
                        <label for="suffix_name" class="block font-medium">Suffix Name</label>
                        <input type="text" id="suffix_name" name="suffix_name" class="w-full border border-gray-300 rounded px-3 py-2" />
                        <p class="text-red-600 text-sm mt-1 error-message" data-field="suffix_name"></p>
                    </div>
                    <div>
                        <label for="birthdate" class="block font-medium">Birthdate</label>
                        <input type="date" id="birthdate" name="birthdate" max="<?php echo e(date('Y-m-d')); ?>" required class="w-full border border-gray-300 rounded px-3 py-2" />
                        <p class="text-red-600 text-sm mt-1 error-message" data-field="birthdate"></p>
                    </div>
                    <div>
                        <label for="gender" class="block font-medium">Gender</label>
                        <select id="gender" name="gender" required class="w-full border border-gray-300 rounded px-3 py-2">
                            <option value="">Select Gender</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                        <p class="text-red-600 text-sm mt-1 error-message" data-field="gender"></p>
                    </div>
                    <div>
                        <label for="email" class="block font-medium">Email Address</label>
                        <input type="email" id="email" name="email" required class="w-full border border-gray-300 rounded px-3 py-2" />
                        <p class="text-red-600 text-sm mt-1 error-message" data-field="email"></p>
                    </div>
                    <div>
                        <label for="phone" class="block font-medium">Phone Number</label>
                        <input type="tel" id="phone" name="phone" maxlength="11" pattern="[0-9]{11}" inputmode="numeric" placeholder="09123456789" class="w-full border border-gray-300 rounded px-3 py-2" />
                        <p class="text-red-600 text-sm mt-1 error-message" data-field="phone"></p>
                    </div>
                    <div>
                        <label for="program_id" class="block font-medium">Program</label>
                        <select id="program_id" name="program_id" required class="w-full border border-gray-300 rounded px-3 py-2">
                            <option value="">Select a program</option>
                            <?php $__currentLoopData = \App\Models\Program::all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $program): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($program->id); ?>"><?php echo e($program->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <p class="text-red-600 text-sm mt-1 error-message" data-field="program_id"></p>
                    </div>
                    <div>
                        <label for="status" class="block font-medium">Status</label>
                        <select id="status" name="status" required class="w-full border border-gray-300 rounded px-3 py-2">
                            <option value="">Select Status</option>
                            <option value="enrolled">Enrolled</option>
                            <option value="approved">Approved</option>
                        </select>
                        <p class="text-red-600 text-sm mt-1 error-message" data-field="status"></p>
                    </div>
                    <div class="md:col-span-2">
                        <label for="address" class="block font-medium">Address</label>
                        <textarea id="address" name="address" rows="2" class="w-full border border-gray-300 rounded px-3 py-2" placeholder="Enter your complete address"></textarea>
                        <p class="text-red-600 text-sm mt-1 error-message" data-field="address"></p>
                    </div>
                </div>

                <!-- Submit Button -->
                <div>
                    <button type="submit" id="manualEnrollmentSubmitBtn" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded w-full">
                        Submit Enrollment
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Enrollment Confirmation Modal -->
    <div id="enrollmentConfirmationModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6 relative">
            <button id="closeConfirmationModal" class="absolute top-4 right-4 text-gray-600 hover:text-gray-900 text-2xl font-bold">
                &times;
            </button>
            <div class="text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100 mb-4">
                    <i class="fas fa-check text-green-600 text-xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Enrollment Successful!</h3>
                <p class="text-sm text-gray-600 mb-4">Student has been successfully enrolled. Here are the login credentials:</p>

                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-700">Username:</span>
                            <span id="studentUsername" class="text-sm font-mono bg-white px-2 py-1 rounded border text-gray-900"></span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-700">Password:</span>
                            <span id="studentPassword" class="text-sm font-mono bg-white px-2 py-1 rounded border text-gray-900"></span>
                        </div>
                    </div>
                </div>

                <div class="flex space-x-3">
                    <button id="copyCredentialsBtn" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                        <i class="fas fa-copy mr-2"></i>Copy Credentials
                    </button>
                    <button id="closeConfirmationBtn" class="flex-1 bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    

    <!-- Student Details Modal -->
    <?php echo $__env->make('Admin.partials.student_details_modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <!-- Notification Container -->
    <div id="notificationContainer" class="fixed top-4 right-4 space-y-2 z-50"></div>

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

        // Profile modal handler - now handled by profile.blade.php
        function handleProfileClick(e) {
            e.preventDefault();
            e.stopPropagation();

            // Close other modals first
            const studentDetails = document.getElementById('studentDetailsModal');
            if (studentDetails && !studentDetails.classList.contains('hidden')) {
                studentDetails.classList.add('hidden');
            }

            const dropdown = document.getElementById('dropdownMenu');
            if (dropdown) dropdown.classList.add('hidden');

            // Use the global function from profile.blade.php
            if (typeof window.openProfileModal === 'function') {
                window.openProfileModal();
            } else {
                console.log('Profile modal function not available');
            }
        }

        // Manual Enrollment Modal handlers
        const manualEnrollmentBtn = document.getElementById('manualEnrollmentBtn');
        const manualEnrollmentModal = document.getElementById('manualEnrollmentModal');
        const closeManualEnrollmentModal = document.getElementById('closeManualEnrollmentModal');
        const manualEnrollmentForm = document.getElementById('manualEnrollmentForm');
        const notificationContainer = document.getElementById('notificationContainer');

        // Enrollment Confirmation Modal handlers
        const enrollmentConfirmationModal = document.getElementById('enrollmentConfirmationModal');
        const closeConfirmationModal = document.getElementById('closeConfirmationModal');
        const closeConfirmationBtn = document.getElementById('closeConfirmationBtn');
        const copyCredentialsBtn = document.getElementById('copyCredentialsBtn');
        const studentUsername = document.getElementById('studentUsername');
        const studentPassword = document.getElementById('studentPassword');

        // Close confirmation modal and reload page
        function closeConfirmationModalAndReload() {
            enrollmentConfirmationModal.classList.add('hidden');
            window.location.reload();
        }

        // Event handlers for confirmation modal
        closeConfirmationModal.addEventListener('click', closeConfirmationModalAndReload);
        closeConfirmationBtn.addEventListener('click', closeConfirmationModalAndReload);

        // Close modal when clicking outside
        enrollmentConfirmationModal.addEventListener('click', (e) => {
            if (e.target === enrollmentConfirmationModal) {
                closeConfirmationModalAndReload();
            }
        });

        // Copy credentials to clipboard
        copyCredentialsBtn.addEventListener('click', () => {
            const username = studentUsername.textContent;
            const password = studentPassword.textContent;
            const credentialsText = `Username: ${username}\nPassword: ${password}`;

            navigator.clipboard.writeText(credentialsText).then(() => {
                // Show success notification
                showNotification('Credentials copied to clipboard!', 'success');
            }).catch(err => {
                console.error('Failed to copy credentials: ', err);
                showNotification('Failed to copy credentials', 'error');
            });
        });

        manualEnrollmentBtn.addEventListener('click', () => {
            manualEnrollmentModal.classList.remove('hidden');
        });

        closeManualEnrollmentModal.addEventListener('click', () => {
            manualEnrollmentModal.classList.add('hidden');
            clearFormErrors();
            manualEnrollmentForm.reset();
        });

        // Close modal when clicking outside
        manualEnrollmentModal.addEventListener('click', (e) => {
            if (e.target === manualEnrollmentModal) {
                manualEnrollmentModal.classList.add('hidden');
                clearFormErrors();
                manualEnrollmentForm.reset();
            }
        });

        // Clear form error messages
        function clearFormErrors() {
            manualEnrollmentForm.querySelectorAll('.error-message').forEach(el => {
                el.textContent = '';
            });
            manualEnrollmentForm.querySelectorAll('input, select').forEach(el => {
                el.classList.remove('border-red-600');
            });
        }

        // Show notification
        function showNotification(message, type = 'success') {
            const notif = document.createElement('div');
            notif.className = `px-4 py-2 rounded shadow text-white ${
                type === 'success' ? 'bg-green-600' : 'bg-red-600'
            }`;
            notif.textContent = message;
            notificationContainer.appendChild(notif);
            setTimeout(() => {
                notif.remove();
            }, 4000);
        }

        // AJAX form submission
        manualEnrollmentForm.addEventListener('submit', function (e) {
            e.preventDefault();
            clearFormErrors();

            const formData = new FormData(manualEnrollmentForm);
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch("<?php echo e(route('admin.manual-enroll')); ?>", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: formData,
            })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(data => {
                            throw data;
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    // Populate confirmation modal with credentials
                    studentUsername.textContent = data.username || 'N/A';
                    studentPassword.textContent = data.password || 'N/A';

                    // Hide enrollment modal and show confirmation modal
                    manualEnrollmentModal.classList.add('hidden');
                    enrollmentConfirmationModal.classList.remove('hidden');

                    // Clear form
                    manualEnrollmentForm.reset();
                })
                .catch(errorData => {
                    if (errorData.errors) {
                        for (const [field, messages] of Object.entries(errorData.errors)) {
                            const errorElem = manualEnrollmentForm.querySelector(`.error-message[data-field="${field}"]`);
                            const inputElem = manualEnrollmentForm.querySelector(`[name="${field}"]`);
                            if (errorElem) {
                                errorElem.textContent = messages[0];
                            }
                            if (inputElem) {
                                inputElem.classList.add('border-red-600');
                            }
                        }
                    } else {
                        showNotification('An error occurred during enrollment.', 'error');
                    }
                });
        });

        function openStudentDetailsModal(enrollmentId) {
            console.log(`Opening student details modal for enrollment ID: ${enrollmentId}`);

            // Close profile modal if open
            if (typeof window.closeProfileModal === 'function') {
                window.closeProfileModal();
            }

            // Show loading state, hide content and error
            document.getElementById('loadingState').classList.remove('hidden');
            document.getElementById('errorState').classList.add('hidden');
            document.getElementById('studentDetailsContent').classList.add('hidden');

            // Show modal
            document.getElementById('studentDetailsModal').classList.remove('hidden');

            // Fetch enrollment details
            fetch(`/admin/enrollments/${enrollmentId}/details`, {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Failed to fetch enrollment details');
                }
                return response.json();
            })
            .then(enrollmentData => {
                console.log('Enrollment data:', enrollmentData);

        // Populate student information
        document.getElementById('studentName').textContent = `${enrollmentData.last_name ? enrollmentData.last_name.charAt(0).toUpperCase() + enrollmentData.last_name.slice(1) : ''}, ${enrollmentData.first_name ? enrollmentData.first_name.charAt(0).toUpperCase() + enrollmentData.first_name.slice(1) : ''} ${enrollmentData.middle_name ? enrollmentData.middle_name.charAt(0).toUpperCase() + enrollmentData.middle_name.slice(1) : ''} ${enrollmentData.suffix_name ? enrollmentData.suffix_name.charAt(0).toUpperCase() + enrollmentData.suffix_name.slice(1) : ''}`.trim();
        document.getElementById('studentProgram').textContent = enrollmentData.program || 'N/A';
        document.getElementById('studentAddress').textContent = enrollmentData.address || 'N/A';
        document.getElementById('studentPhone').textContent = enrollmentData.phone || 'N/A';
        
        // Update student photo
        const studentPhoto = document.getElementById('studentPhoto');
        const studentPhotoPlaceholder = document.getElementById('studentPhotoPlaceholder');
        
        console.log('Photo URL:', enrollmentData.photo);
        
        if (enrollmentData.photo && enrollmentData.photo !== 'N/A' && enrollmentData.photo !== null) {
            studentPhoto.src = enrollmentData.photo;
            studentPhoto.style.display = 'block';
            studentPhotoPlaceholder.style.display = 'none';
            
            // Add error handling for photo loading
            studentPhoto.onerror = function() {
                console.log('Photo failed to load:', enrollmentData.photo);
                studentPhoto.style.display = 'none';
                studentPhotoPlaceholder.style.display = 'block';
            };
        } else {
            console.log('No photo available, showing placeholder');
            studentPhoto.style.display = 'none';
            studentPhotoPlaceholder.style.display = 'block';
        }

        // Fetch attendance and instructor data
        return fetch(`/admin/enrollments/${enrollmentId}/statement-of-account`, {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        });
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Failed to fetch attendance data');
                }
                return response.json();
            })
            .then(attendanceData => {
                console.log('Attendance data:', attendanceData);

                // Populate instructor information
                const instructorNameElement = document.getElementById('instructorName');
                if (instructorNameElement) {
                    if (attendanceData.instructors && attendanceData.instructors.length > 0) {
                        const instructorNames = attendanceData.instructors.map(instructor => instructor.name).join('\n');
                        instructorNameElement.textContent = instructorNames;
                        // Add CSS to preserve line breaks
                        instructorNameElement.style.whiteSpace = 'pre-line';
                    } else {
                        instructorNameElement.textContent = 'N/A';
                    }
                }

                // Populate attendance table
                const attendanceTableContainer = document.getElementById('attendanceTableContainer');
                if (attendanceData.attendances && attendanceData.attendances.length > 0) {
                    const attendanceRows = attendanceData.attendances.map(attendance => {
                        // Use session_date instead of created_at for the actual session date
                        const date = attendance.session_date ? 
                            new Date(attendance.session_date).toLocaleDateString('en-US', {
                                year: 'numeric',
                                month: 'short',
                                day: 'numeric'
                            }) : 'N/A';
                        
                        // Calculate the day of the week from session_date
                        const day = attendance.session_date ? 
                            new Date(attendance.session_date).toLocaleDateString('en-US', {
                                weekday: 'long'
                            }) : 'N/A';
                        
                        const amount = attendance.payment?.amount ? `₱${attendance.payment.amount}` : 'N/A';
                        const orNumber = attendance.or_number || 'N/A';
                        const referenceNumber = attendance.payment?.reference_number || 'N/A';

                        // Format time from attendance record
                        let timeDisplay = 'N/A';
                        if (attendance.start_time && attendance.end_time) {
                            const startTime = new Date('2000-01-01T' + attendance.start_time).toLocaleTimeString('en-US', {
                                hour: 'numeric',
                                minute: '2-digit',
                                hour12: true
                            });
                            const endTime = new Date('2000-01-01T' + attendance.end_time).toLocaleTimeString('en-US', {
                                hour: 'numeric',
                                minute: '2-digit',
                                hour12: true
                            });
                            timeDisplay = `${startTime} - ${endTime}`;
                        }

                        return `
                            <tr class="hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 transition-all duration-200 border-b border-gray-100">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="bg-indigo-100 text-indigo-800 text-xs font-bold px-2 py-1 rounded-full">
                                            ${attendance.session_number}
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${date}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 font-medium">${day}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">${timeDisplay}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-green-100 text-green-800">
                                        <i class="fas fa-money-bill-wave mr-1"></i>
                                        ${amount}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 font-mono">${orNumber}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 font-mono">${referenceNumber || orNumber}</td>
                            </tr>
                        `;
                    }).join('');

                    attendanceTableContainer.innerHTML = attendanceRows;
                } else {
                    attendanceTableContainer.innerHTML = `
                        <tr>
                            <td colspan="7" class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">No attendance records found.</td>
                        </tr>
                    `;
                }

                // Update last updated timestamp
                const lastUpdatedElement = document.getElementById('lastUpdated');
                if (lastUpdatedElement) {
                    lastUpdatedElement.textContent = new Date().toLocaleString();
                }

                // Hide loading, show content
                document.getElementById('loadingState').classList.add('hidden');
                document.getElementById('studentDetailsContent').classList.remove('hidden');
            })
            .catch(error => {
                console.error('Error loading student details:', error);

                // Hide loading, show error
                document.getElementById('loadingState').classList.add('hidden');
                document.getElementById('errorState').classList.remove('hidden');
                document.getElementById('errorMessage').textContent = 'Failed to load student details. Please try again.';
            });
        }

        function closeStudentDetailsModal() {
            document.getElementById('studentDetailsModal').classList.add('hidden');
        }

        // Add event listener for close button
        document.addEventListener('DOMContentLoaded', function() {
            const closeButton = document.getElementById('closeStudentDetailsModal');
            if (closeButton) {
                closeButton.addEventListener('click', function() {
                    closeStudentDetailsModal();
                });
            }
        });

        // Close modal when clicking outside
        document.getElementById('studentDetailsModal').addEventListener('click', function(e) {
            if (e.target.id === 'studentDetailsModal') {
                closeStudentDetailsModal();
            }
        });

        // Update OR number for manual enrollments
        function updateOrNumber(enrollmentId, orNumber) {
            fetch(`/api/admin/enrollments/${enrollmentId}/update-or-number`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                },
                body: JSON.stringify({ or_number: orNumber })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success message
                    showNotification('OR number updated successfully', 'success');
                } else {
                    showNotification('Failed to update OR number', 'error');
                }
            })
            .catch(error => {
                console.error('Error updating OR number:', error);
                showNotification('Error updating OR number', 'error');
            });
        }

        // View receipt for online enrollments
        function viewReceipt(enrollmentId) {
            // Open receipt in new tab/window
            window.open(`/admin/enrollments/${enrollmentId}/receipt`, '_blank');
        }

        // Show notification function
        function showNotification(message, type = 'info') {
            // Create notification element
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 px-4 py-2 rounded-md text-white z-50 ${
                type === 'success' ? 'bg-green-500' :
                type === 'error' ? 'bg-red-500' : 'bg-blue-500'
            }`;
            notification.textContent = message;

            // Add to page
            document.body.appendChild(notification);

            // Remove after 3 seconds
            setTimeout(() => {
                notification.remove();
            }, 3000);
        }
        
    </script>

    <!-- Include Admin Profile Modal -->
    <?php echo $__env->make('Admin.Top.profile', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" class="hidden">
        <?php echo csrf_field(); ?>
    </form>
    <?php echo $__env->yieldPushContent('scripts'); ?>
    
    <!-- Loading System Integration -->
    <?php echo $__env->make('partials.loading-integration', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</body>
</html>

<?php /**PATH C:\Users\gucor\OneDrive\Documents\Herd\Modular_System\resources\views/Admin/enrolled_student.blade.php ENDPATH**/ ?>