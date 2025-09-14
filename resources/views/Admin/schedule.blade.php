<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin - Program & Schedule Settings</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
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

        /* Success message styles */
        .success-message {
            position: fixed;
            top: 20px;
            right: 20px;
            background-color: #10b981;
            color: white;
            padding: 12px 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 8px;
            transform: translateX(400px);
            transition: transform 0.3s ease;
        }

        .success-message.show {
            transform: translateX(0);
        }

        .success-message i {
            font-size: 16px;
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
                        Program & Schedule Settings
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

            <!-- Program & Schedule Content -->
            <div class="p-6">
                <!-- Enhanced Program Management Section -->
                <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden mb-8">
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-4 border-b border-gray-100">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-graduation-cap text-blue-600"></i>
                                </div>
                                <h2 class="text-lg font-semibold text-gray-800">Program Management</h2>
                            </div>
                            <button class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white px-6 py-3 rounded-lg shadow-lg hover:shadow-xl transition-all duration-200 transform hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2" type="button" x-data x-on:click.prevent="$dispatch('open-modal', 'add-program')">
                                <i class="fas fa-plus mr-2"></i>Add New Program
                            </button>
                        </div>
                    </div>
                    <div class="p-6">
                        
                        <!-- Add Program Modal -->
                        <x-modal name="add-program" :show="false" maxWidth="md">
                            <div class="p-6">
                                <h2 class="text-xl font-semibold mb-4">Add New Program</h2>
                                <form action="{{ route('admin.programs.store') }}" method="POST">
                                    @csrf
                                    <div class="mb-6">
                                        <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Program Name</label>
                                        <input type="text" name="name" id="name" required
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                    </div>
                                    <div class="mb-6">
                                        <label for="duration" class="block text-sm font-semibold text-gray-700 mb-2">Number of Sessions</label>
                                        <input type="text" name="duration" id="duration"
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                    </div>
                                    <div class="mb-6">
                                        <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                                        <textarea name="description" id="description" rows="3"
                                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"></textarea>
                                    </div>
                                    <div class="mb-6">
                                        <label for="registration_fee" class="block text-sm font-semibold text-gray-700 mb-2">Registration Fee</label>
                                        <input type="number" name="registration_fee" id="registration_fee" step="0.01" min="0"
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                    </div>
                                    <div class="mb-6">
                                        <label for="price_per_session" class="block text-sm font-semibold text-gray-700 mb-2">Price per Session</label>
                                        <input type="number" name="price_per_session" id="price_per_session" step="0.01" min="0"
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                    </div>
                                    <div class="flex justify-end space-x-4 pt-4">
                                        <button type="button" x-on:click="$dispatch('close-modal', 'add-program')" class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-all duration-200">
                                            Cancel
                                        </button>
                                        <button type="submit" class="px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white rounded-lg shadow-lg hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200 transform hover:-translate-y-0.5">
                                            <i class="fas fa-plus mr-2"></i>Add Program
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </x-modal>

                        <!-- Add Schedule Modal -->
                        <x-modal name="add-schedule" :show="false" maxWidth="md">
                            <div class="p-6">
                                <h2 class="text-xl font-semibold mb-4">Create New Schedule</h2>
                        <form id="addScheduleForm">
                            @csrf
                            <div class="mb-6">
                                <label for="schedule_program_id" class="block text-sm font-semibold text-gray-700 mb-2">Program</label>
                                <select name="program_id" id="schedule_program_id" required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors">
                                    <option value="">Select Program</option>
                                    @foreach($programs as $program)
                                        <option value="{{ $program->id }}">{{ $program->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-6">
                                <label for="schedule_day" class="block text-sm font-semibold text-gray-700 mb-2">Day</label>
                                <select name="day" id="schedule_day" required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors">
                                    <option value="">Select Day</option>
                                    <option value="Monday">Monday</option>
                                    <option value="Tuesday">Tuesday</option>
                                    <option value="Wednesday">Wednesday</option>
                                    <option value="Thursday">Thursday</option>
                                    <option value="Friday">Friday</option>
                                    <option value="Saturday">Saturday</option>
                                    <option value="Sunday">Sunday</option>
                                </select>
                            </div>
                            <div class="grid grid-cols-2 gap-4 mb-6">
                                <div>
                                    <label for="schedule_start_time" class="block text-sm font-semibold text-gray-700 mb-2">Start Time</label>
                                    <input type="time" name="start_time" id="schedule_start_time" required
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors">
                                </div>
                                <div>
                                    <label for="schedule_end_time" class="block text-sm font-semibold text-gray-700 mb-2">End Time</label>
                                    <input type="time" name="end_time" id="schedule_end_time" required
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors">
                                </div>
                            </div>
                            <div class="flex justify-end space-x-4 pt-4">
                                <button type="button" x-on:click="$dispatch('close-modal', 'add-schedule')" class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-all duration-200">
                                    Cancel
                                </button>
                                <button type="submit" class="px-6 py-3 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white rounded-lg shadow-lg hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-all duration-200 transform hover:-translate-y-0.5">
                                    <i class="fas fa-plus mr-2"></i>Create Schedule
                                </button>
                            </div>
                        </form>
                            </div>
                        </x-modal>

                        <!-- Edit Program Modal -->
                        <x-modal name="edit-program" :show="false" maxWidth="md">
                            <div class="p-6">
                                <h2 class="text-xl font-semibold mb-4">Edit Program</h2>
                                <form id="editProgramForm">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" id="edit_program_id" name="program_id">
                                    <div class="mb-6">
                                        <label for="edit_program_name" class="block text-sm font-semibold text-gray-700 mb-2">Program Name</label>
                                        <input type="text" name="name" id="edit_program_name" required
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                    </div>
                                    <div class="mb-6">
                                        <label for="edit_program_duration" class="block text-sm font-semibold text-gray-700 mb-2">Number of Sessions</label>
                                        <input type="text" name="duration" id="edit_program_duration"
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                    </div>
                                    <div class="mb-6">
                                        <label for="edit_program_description" class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                                        <textarea name="description" id="edit_program_description" rows="3"
                                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"></textarea>
                                    </div>
                                    <div class="mb-6">
                                        <label for="edit_program_registration_fee" class="block text-sm font-semibold text-gray-700 mb-2">Registration Fee</label>
                                        <input type="number" name="registration_fee" id="edit_program_registration_fee" step="0.01" min="0"
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                    </div>
                                    <div class="mb-6">
                                        <label for="edit_program_price" class="block text-sm font-semibold text-gray-700 mb-2">Price per Session</label>
                                        <input type="number" name="price_per_session" id="edit_program_price" step="0.01" min="0"
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                    </div>
                                    <div class="flex justify-end space-x-4 pt-4">
                                        <button type="button" x-on:click="$dispatch('close-modal', 'edit-program')" class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-all duration-200">
                                            Cancel
                                        </button>
                                        <button type="submit" class="px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white rounded-lg shadow-lg hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200 transform hover:-translate-y-0.5">
                                            <i class="fas fa-save mr-2"></i>Update Program
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </x-modal>

                        <!-- Edit Schedule Modal -->
                        <x-modal name="edit-schedule" :show="false" maxWidth="md">
                            <div class="p-6">
                                <h2 class="text-xl font-semibold mb-4">Edit Schedule</h2>
                                <form id="editScheduleForm">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" id="edit_schedule_id" name="schedule_id">
                                    <div class="mb-6">
                                        <label for="edit_schedule_program_id" class="block text-sm font-semibold text-gray-700 mb-2">Program</label>
                                        <select name="program_id" id="edit_schedule_program_id" required
                                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors">
                                            <option value="">Select Program</option>
                                            @foreach($programs as $program)
                                                <option value="{{ $program->id }}">{{ $program->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-6">
                                        <label for="edit_schedule_day" class="block text-sm font-semibold text-gray-700 mb-2">Day</label>
                                        <select name="day" id="edit_schedule_day" required
                                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors">
                                            <option value="">Select Day</option>
                                            <option value="Monday">Monday</option>
                                            <option value="Tuesday">Tuesday</option>
                                            <option value="Wednesday">Wednesday</option>
                                            <option value="Thursday">Thursday</option>
                                            <option value="Friday">Friday</option>
                                            <option value="Saturday">Saturday</option>
                                            <option value="Sunday">Sunday</option>
                                        </select>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4 mb-6">
                                        <div>
                                            <label for="edit_schedule_start_time" class="block text-sm font-semibold text-gray-700 mb-2">Start Time</label>
                                            <input type="time" name="start_time" id="edit_schedule_start_time" required
                                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors">
                                        </div>
                                        <div>
                                            <label for="edit_schedule_end_time" class="block text-sm font-semibold text-gray-700 mb-2">End Time</label>
                                            <input type="time" name="end_time" id="edit_schedule_end_time" required
                                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors">
                                        </div>
                                    </div>
                                    <div class="flex justify-end space-x-4 pt-4">
                                        <button type="button" x-on:click="$dispatch('close-modal', 'edit-schedule')" class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-all duration-200">
                                            Cancel
                                        </button>
                                        <button type="submit" class="px-6 py-3 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white rounded-lg shadow-lg hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-all duration-200 transform hover:-translate-y-0.5">
                                            <i class="fas fa-save mr-2"></i>Update Schedule
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </x-modal>
                    </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white border border-gray-200 rounded-lg overflow-hidden">
                                <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                                    <tr>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider border-b border-gray-200">Program Name</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider border-b border-gray-200">Sessions</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider border-b border-gray-200">Registration Fee</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider border-b border-gray-200">Price per Session</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider border-b border-gray-200">Total Price</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider border-b border-gray-200">Description</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider border-b border-gray-200">Status</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider border-b border-gray-200">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($programs as $program)
                                    <tr class="{{ $program->status === 'inactive' ? 'bg-gray-50 opacity-60' : 'hover:bg-gray-50 transition-colors' }}">
                                        <td class="px-6 py-4 whitespace-nowrap {{ $program->status === 'inactive' ? 'text-gray-500' : 'text-gray-900' }}">
                                            <div class="flex items-center">
                                                <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                                    <i class="fas fa-graduation-cap text-blue-600 text-sm"></i>
                                                </div>
                                                <div>
                                                    <div class="text-sm font-medium">{{ $program->name }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap {{ $program->status === 'inactive' ? 'text-gray-500' : 'text-gray-900' }}">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                {{ $program->duration }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap {{ $program->status === 'inactive' ? 'text-gray-500' : 'text-gray-900' }}">
                                            <span class="text-sm font-semibold text-emerald-600">₱{{ number_format($program->registration_fee ?? 0, 2) }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap {{ $program->status === 'inactive' ? 'text-gray-500' : 'text-gray-900' }}">
                                            <span class="text-sm font-semibold text-blue-600">₱{{ number_format($program->price_per_session ?? 0, 2) }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap {{ $program->status === 'inactive' ? 'text-gray-500' : 'text-gray-900' }}">
                                            <span class="text-sm font-bold text-purple-600">₱{{ number_format(($program->price_per_session ?? 0) * (is_numeric($program->duration) ? (int)$program->duration : (preg_match('/(\d+)/', $program->duration, $matches) ? (int)$matches[1] : 0)), 2) }}</span>
                                        </td>
                                        <td class="px-6 py-4 {{ $program->status === 'inactive' ? 'text-gray-500' : 'text-gray-900' }}">
                                            <div class="text-sm max-w-xs truncate" title="{{ $program->description }}">{{ $program->description }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $program->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                <i class="fas {{ $program->status === 'active' ? 'fa-check-circle' : 'fa-times-circle' }} mr-1"></i>
                                                {{ ucfirst($program->status ?? 'active') }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex items-center space-x-2">
                                                <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200" title="Edit" onclick="openEditProgramModal({{ $program->id }})">
                                                    <i class="fas fa-edit mr-1"></i>Edit
                                                </button>
                                                <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white {{ $program->status === 'active' ? 'bg-gradient-to-r from-yellow-500 to-yellow-600 hover:from-yellow-600 hover:to-yellow-700' : 'bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700' }} focus:outline-none focus:ring-2 focus:ring-offset-2 {{ $program->status === 'active' ? 'focus:ring-yellow-500' : 'focus:ring-green-500' }} transition-all duration-200"
                                                        title="{{ $program->status === 'active' ? 'Deactivate' : 'Activate' }}"
                                                        onclick="toggleProgramStatus({{ $program->id }}, '{{ $program->name }}', '{{ $program->status }}')">
                                                    <i class="fas {{ $program->status === 'active' ? 'fa-pause' : 'fa-play' }} mr-1"></i>{{ $program->status === 'active' ? 'Deactivate' : 'Activate' }}
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Enhanced Schedule Management Section -->
                <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                    <div class="bg-gradient-to-r from-green-50 to-emerald-50 px-6 py-4 border-b border-gray-100">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-calendar-alt text-green-600"></i>
                                </div>
                                <h2 class="text-lg font-semibold text-gray-800">Schedule Management</h2>
                            </div>
                            <button class="bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white px-6 py-3 rounded-lg shadow-lg hover:shadow-xl transition-all duration-200 transform hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2" type="button" x-data x-on:click.prevent="$dispatch('open-modal', 'add-schedule')">
                                <i class="fas fa-plus mr-2"></i>Create New Schedule
                            </button>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white border border-gray-200 rounded-lg overflow-hidden">
                                <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                                    <tr>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider border-b border-gray-200">Program</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider border-b border-gray-200">Day</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider border-b border-gray-200">Time</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider border-b border-gray-200">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($schedules as $schedule)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                                    <i class="fas fa-graduation-cap text-green-600 text-sm"></i>
                                                </div>
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900">{{ $schedule->program?->name ?? 'N/A' }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                <i class="fas fa-calendar-day mr-1"></i>{{ $schedule->day }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <div class="flex items-center">
                                                <i class="fas fa-clock text-gray-400 mr-2"></i>
                                                <span class="font-medium">{{ $schedule->start_time }} - {{ $schedule->end_time }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <button class="inline-flex items-center px-4 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200" title="Settings" onclick="openEditScheduleModal({{ $schedule->id }})">
                                                <i class="fas fa-cog mr-2"></i>Settings
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
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

        // User Type Tab switching functionality
        const userTypeTabs = ['students', 'instructors', 'cashiers'];
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

        // Original Tab switching functionality
        const tabs = ['pending', 'approved', 'rejected'];
        tabs.forEach(tab => {
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
                    tabs.forEach(t => {
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
        document.addEventListener('DOMContentLoaded', function() {
            const logoutButton = document.getElementById('logoutButton');
            const logoutForm = document.getElementById('logout-form');

            if (logoutButton && logoutForm) {
                logoutButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    logoutForm.submit();
                });
            }
        });

        // Initialize sidebar state
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
    <script>
        // Success message function
        function showSuccessMessage(message) {
            const successDiv = document.createElement('div');
            successDiv.className = 'success-message';
            successDiv.innerHTML = `
                <i class="fas fa-check-circle"></i>
                <span>${message}</span>
            `;
            document.body.appendChild(successDiv);

            // Show the message
            setTimeout(() => {
                successDiv.classList.add('show');
            }, 100);

            // Hide and remove after 3 seconds
            setTimeout(() => {
                successDiv.classList.remove('show');
                setTimeout(() => {
                    if (successDiv.parentNode) {
                        successDiv.parentNode.removeChild(successDiv);
                    }
                }, 300);
            }, 3000);
        }

        // Handle schedule creation from modal form
        document.getElementById('addScheduleForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const form = e.target;
            const formData = new FormData(form);

            fetch('/admin/schedules', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Close modal
                    form.dispatchEvent(new CustomEvent('close-modal', { bubbles: true }));
                    // Show success message
                    showSuccessMessage(data.message || 'Schedule created successfully!');
                    // Reload the page to show the new schedule
                    setTimeout(() => location.reload(), 1000);
                } else {
                    alert(data.message || 'Error creating schedule');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error creating schedule');
            });
        });

        // Open Edit Program Modal and populate data
        function openEditProgramModal(programId) {
            fetch(`/admin/programs/${programId}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('edit_program_id').value = data.id;
                    document.getElementById('edit_program_name').value = data.name;
                    document.getElementById('edit_program_duration').value = data.duration;
                    document.getElementById('edit_program_description').value = data.description;
                    document.getElementById('edit_program_registration_fee').value = data.registration_fee;
                    document.getElementById('edit_program_price').value = data.price_per_session;
                    // Open the modal
                    window.dispatchEvent(new CustomEvent('open-modal', { detail: 'edit-program' }));
                })
                .catch(error => {
                    console.error('Error fetching program data:', error);
                    alert('Failed to load program data.');
                });
        }

        // Handle edit program form submission with password confirmation
        document.getElementById('editProgramForm').addEventListener('submit', function(e) {
            e.preventDefault();

            // Create a modal for password input
            const modalHtml = `
                <div id="programPasswordModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
                    <div class="bg-white rounded-lg p-6 w-96">
                        <h3 class="text-lg font-semibold mb-4">Confirm Password</h3>
                        <p class="mb-4">Please enter your password to update the program.</p>
                        <input type="password" id="programPasswordInput" class="w-full border rounded px-3 py-2 mb-4 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Password" />
                        <div class="flex justify-end space-x-3">
                            <button id="cancelProgramPasswordBtn" class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400">Cancel</button>
                            <button id="confirmProgramPasswordBtn" class="px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700">Confirm</button>
                        </div>
                        <p id="programPasswordError" class="text-red-600 mt-2 hidden">Incorrect password. Please try again.</p>
                    </div>
                </div>
            `;

            // Append modal to body
            document.body.insertAdjacentHTML('beforeend', modalHtml);

            const modal = document.getElementById('programPasswordModal');
            const passwordInput = document.getElementById('programPasswordInput');
            const cancelBtn = document.getElementById('cancelProgramPasswordBtn');
            const confirmBtn = document.getElementById('confirmProgramPasswordBtn');
            const passwordError = document.getElementById('programPasswordError');

            // Cancel button closes modal
            cancelBtn.addEventListener('click', () => {
                modal.remove();
            });

            // Confirm button verifies password
            confirmBtn.addEventListener('click', () => {
                const password = passwordInput.value.trim();
                if (!password) {
                    passwordError.textContent = 'Password cannot be empty.';
                    passwordError.classList.remove('hidden');
                    return;
                }

                // Send password to server for verification
                fetch('/admin/verify-password', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ password })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Password correct, proceed with program update
                        modal.remove();
                        proceedWithProgramUpdate();
                    } else {
                        // Password incorrect
                        passwordError.textContent = 'Incorrect password. Please try again.';
                        passwordError.classList.remove('hidden');
                    }
                })
                .catch(error => {
                    console.error('Error verifying password:', error);
                    passwordError.textContent = 'Error verifying password. Please try again later.';
                    passwordError.classList.remove('hidden');
                });
            });

            // Function to proceed with program update after password verification
            function proceedWithProgramUpdate() {
                const programId = document.getElementById('edit_program_id').value;
                const formData = new FormData(document.getElementById('editProgramForm'));

                // Convert FormData to URLSearchParams for proper PUT request handling
                const params = new URLSearchParams();
                for (let [key, value] of formData.entries()) {
                    params.append(key, value);
                }

                fetch(`/admin/programs/${programId}`, {
                    method: 'PUT',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'Accept': 'application/json',
                    },
                    body: params.toString()
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Close modal
                        document.getElementById('editProgramForm').dispatchEvent(new CustomEvent('close-modal', { bubbles: true }));
                        // Show confirmation message
                        alert(data.message || 'Program updated successfully.');
                        // Reload the page to show updated program
                        location.reload();
                    } else {
                        alert(data.message || 'Error updating program');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error updating program');
                });
            }
        });

        // Handle delete program
        function deleteProgram(programId, programName) {
            if (confirm(`Are you sure you want to delete the program "${programName}"? This action cannot be undone.`)) {
                fetch(`/admin/programs/${programId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message || 'Program deleted successfully.');
                        location.reload();
                    } else {
                        alert(data.message || 'Error deleting program.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error deleting program.');
                });
            }
        }

        // Open Edit Schedule Modal and populate data
        function openEditScheduleModal(scheduleId) {
            fetch(`/admin/schedules/${scheduleId}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('edit_schedule_id').value = data.id;
                    document.getElementById('edit_schedule_program_id').value = data.program_id;
                    document.getElementById('edit_schedule_day').value = data.day;
                    document.getElementById('edit_schedule_start_time').value = data.start_time;
                    document.getElementById('edit_schedule_end_time').value = data.end_time;
                    // Open the modal
                    window.dispatchEvent(new CustomEvent('open-modal', { detail: 'edit-schedule' }));
                })
                .catch(error => {
                    console.error('Error fetching schedule data:', error);
                    alert('Failed to load schedule data.');
                });
        }

        // Handle edit schedule form submission with password confirmation
        document.getElementById('editScheduleForm').addEventListener('submit', function(e) {
            e.preventDefault();

            // Create a modal for password input
            const modalHtml = `
                <div id="schedulePasswordModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
                    <div class="bg-white rounded-lg p-6 w-96">
                        <h3 class="text-lg font-semibold mb-4">Confirm Password</h3>
                        <p class="mb-4">Please enter your password to update the schedule.</p>
                        <input type="password" id="schedulePasswordInput" class="w-full border rounded px-3 py-2 mb-4 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Password" />
                        <div class="flex justify-end space-x-3">
                            <button id="cancelSchedulePasswordBtn" class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400">Cancel</button>
                            <button id="confirmSchedulePasswordBtn" class="px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700">Confirm</button>
                        </div>
                        <p id="schedulePasswordError" class="text-red-600 mt-2 hidden">Incorrect password. Please try again.</p>
                    </div>
                </div>
            `;

            // Append modal to body
            document.body.insertAdjacentHTML('beforeend', modalHtml);

            const modal = document.getElementById('schedulePasswordModal');
            const passwordInput = document.getElementById('schedulePasswordInput');
            const cancelBtn = document.getElementById('cancelSchedulePasswordBtn');
            const confirmBtn = document.getElementById('confirmSchedulePasswordBtn');
            const passwordError = document.getElementById('schedulePasswordError');

            // Cancel button closes modal
            cancelBtn.addEventListener('click', () => {
                modal.remove();
            });

            // Confirm button verifies password
            confirmBtn.addEventListener('click', () => {
                const password = passwordInput.value.trim();
                if (!password) {
                    passwordError.textContent = 'Password cannot be empty.';
                    passwordError.classList.remove('hidden');
                    return;
                }

                // Send password to server for verification
                fetch('/admin/verify-password', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ password })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Password correct, proceed with schedule update
                        modal.remove();
                        proceedWithScheduleUpdate();
                    } else {
                        // Password incorrect
                        passwordError.textContent = 'Incorrect password. Please try again.';
                        passwordError.classList.remove('hidden');
                    }
                })
                .catch(error => {
                    console.error('Error verifying password:', error);
                    passwordError.textContent = 'Error verifying password. Please try again later.';
                    passwordError.classList.remove('hidden');
                });
            });

            // Function to proceed with schedule update after password verification
            function proceedWithScheduleUpdate() {
                const scheduleId = document.getElementById('edit_schedule_id').value;
                const formData = new FormData(document.getElementById('editScheduleForm'));

                // Convert FormData to URLSearchParams for proper PUT request handling
                const params = new URLSearchParams();
                for (let [key, value] of formData.entries()) {
                    params.append(key, value);
                }

                fetch(`/admin/schedules/${scheduleId}`, {
                    method: 'PUT',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'Accept': 'application/json',
                    },
                    body: params.toString()
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Close modal
                        document.getElementById('editScheduleForm').dispatchEvent(new CustomEvent('close-modal', { bubbles: true }));
                        // Show confirmation message
                        alert(data.message || 'Schedule updated successfully.');
                        // Reload the page to show updated schedule
                        location.reload();
                    } else {
                        alert(data.message || 'Error updating schedule');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error updating schedule');
                });
            }
        });
    </script>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
        @csrf
    </form>
    <script>
        // Toggle program status (activate/deactivate) with password confirmation
        function toggleProgramStatus(programId, programName, currentStatus) {
            const action = currentStatus === 'active' ? 'deactivate' : 'activate';

            // Create a modal for password input
            const modalHtml = `
                <div id="passwordModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
                    <div class="bg-white rounded-lg p-6 w-96">
                        <h3 class="text-lg font-semibold mb-4">Confirm Password</h3>
                        <p class="mb-4">Please enter your password to ${action} the program "<strong>${programName}</strong>".</p>
                        <input type="password" id="passwordInput" class="w-full border rounded px-3 py-2 mb-4 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Password" />
                        <div class="flex justify-end space-x-3">
                            <button id="cancelPasswordBtn" class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400">Cancel</button>
                            <button id="confirmPasswordBtn" class="px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700">Confirm</button>
                        </div>
                        <p id="passwordError" class="text-red-600 mt-2 hidden">Incorrect password. Please try again.</p>
                    </div>
                </div>
            `;

            // Append modal to body
            document.body.insertAdjacentHTML('beforeend', modalHtml);

            const modal = document.getElementById('passwordModal');
            const passwordInput = document.getElementById('passwordInput');
            const cancelBtn = document.getElementById('cancelPasswordBtn');
            const confirmBtn = document.getElementById('confirmPasswordBtn');
            const passwordError = document.getElementById('passwordError');

            // Cancel button closes modal
            cancelBtn.addEventListener('click', () => {
                modal.remove();
            });

            // Confirm button verifies password
            confirmBtn.addEventListener('click', () => {
                const password = passwordInput.value.trim();
                if (!password) {
                    passwordError.textContent = 'Password cannot be empty.';
                    passwordError.classList.remove('hidden');
                    return;
                }

                // Send password to server for verification
                fetch('/admin/verify-password', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ password })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Password correct, proceed with toggle
                        modal.remove();
                        if (confirm(`Are you sure you want to ${action} the program "${programName}"?`)) {
                            fetch(`/admin/programs/${programId}/toggle-status`, {
                                method: 'PATCH',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                    'Accept': 'application/json',
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    alert(data.message || `Program ${action}d successfully.`);
                                    location.reload();
                                } else {
                                    alert(data.message || `Error trying to ${action} program.`);
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                alert(`Error trying to ${action} program.`);
                            });
                        }
                    } else {
                        // Password incorrect
                        passwordError.textContent = 'Incorrect password. Please try again.';
                        passwordError.classList.remove('hidden');
                    }
                })
                .catch(error => {
                    console.error('Error verifying password:', error);
                    passwordError.textContent = 'Error verifying password. Please try again later.';
                    passwordError.classList.remove('hidden');
                });
            });
        }
    </script>
    @stack('scripts')

    <!-- Include Admin Profile Modal -->
    @include('Admin.Top.profile')
    
    <!-- Loading System Integration -->
    @include('partials.loading-integration')
    
</body>
</html>
