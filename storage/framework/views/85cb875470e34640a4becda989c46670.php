<!-- Student Details Modal -->
<div id="studentDetailsModal" class="fixed inset-0 bg-black bg-opacity-60 hidden overflow-y-auto h-full w-full z-50 backdrop-blur-sm">
    <div class="relative top-10 mx-auto p-0 border w-full max-w-4xl shadow-lg rounded-lg bg-white max-h-[90vh] overflow-y-auto modal-scrollbar">
        <!-- Simple Header -->
        <div class="bg-gray-50 border-b border-gray-200 px-6 py-4">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-900">Student Details</h3>
                <button id="closeStudentDetailsModal" class="text-gray-400 hover:text-gray-600">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
        
        <div class="p-6">

        <!-- Enhanced Loading State -->
        <div id="loadingState" class="flex flex-col items-center justify-center py-16">
            <div class="relative">
                <div class="animate-spin rounded-full h-16 w-16 border-4 border-blue-200 border-t-blue-600 mb-6"></div>
                <div class="absolute inset-0 flex items-center justify-center">
                    <i class="fas fa-user-graduate text-blue-600 text-xl"></i>
                </div>
            </div>
            <h4 class="text-xl font-semibold text-gray-700 mb-2">Loading Student Details</h4>
            <p class="text-gray-500 text-center max-w-md">Please wait while we fetch the complete student information and attendance records...</p>
        </div>

        <!-- Enhanced Error State -->
        <div id="errorState" class="hidden flex flex-col items-center justify-center py-16">
            <div class="bg-red-50 border border-red-200 rounded-full p-6 mb-6">
                <i class="fas fa-exclamation-triangle text-red-500 text-4xl"></i>
            </div>
            <h4 class="text-xl font-semibold text-gray-700 mb-2">Unable to Load Details</h4>
            <p class="text-gray-600 text-center max-w-md" id="errorMessage">An error occurred while loading student details. Please try again.</p>
            <button onclick="location.reload()" class="mt-4 bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700 transition-colors duration-200">
                <i class="fas fa-refresh mr-2"></i>Retry
            </button>
        </div>

        <!-- Student Details Content -->
        <div id="studentDetailsContent" class="hidden">
            <!-- Student Photo and Basic Info Box -->
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-6 mb-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 mr-6">
                        <img id="studentPhoto" src="" alt="Student Photo" class="w-20 h-20 rounded-full object-cover border-2 border-gray-200" style="display: none;">
                        <div id="studentPhotoPlaceholder" class="w-20 h-20 bg-gray-200 rounded-full flex items-center justify-center">
                            <i class="fas fa-user text-gray-400 text-2xl"></i>
                        </div>
                    </div>
                    <div class="flex-1">
                        <h2 id="studentName" class="text-2xl font-semibold text-gray-900 mb-1">Loading...</h2>
                        <p id="studentProgram" class="text-gray-600 mb-1">Loading...</p>
                        <p id="studentAddress" class="text-sm text-gray-500">Loading...</p>
                    </div>
                </div>
            </div>

            <!-- Basic Information -->
            <div class="mb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Basic Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="flex items-center py-2 border-b border-gray-100">
                        <span class="text-sm font-medium text-gray-500 mr-3">Phone Number:</span>
                        <span id="studentPhone" class="text-sm text-gray-900">Loading...</span>
                    </div>
                </div>
            </div>

            <!-- Session Details -->
            <div class="mb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Session Details</h3>
                <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Session</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date Attended</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Day</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount Paid</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">OR Number</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reference Number</th>
                                </tr>
                            </thead>
                            <tbody id="attendanceTableContainer" class="bg-white divide-y divide-gray-200">
                                <!-- Attendance data will be populated dynamically -->
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center">
                                            <div class="animate-spin rounded-full h-8 w-8 border-2 border-gray-200 border-t-gray-600 mb-3"></div>
                                            <p class="text-gray-500 font-medium">Loading attendance records...</p>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Instructor Information -->
            <div class="mb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Instructor Information</h3>
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                    <div class="flex items-center py-2">
                        <span class="text-sm font-medium text-gray-500 mr-3">Instructor's Name:</span>
                        <span id="instructorName" class="text-sm text-gray-900" style="white-space: pre-line;">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</div>

<?php /**PATH C:\Users\gucor\OneDrive\Documents\Herd\Modular_System\resources\views/Admin/partials/student_details_modal.blade.php ENDPATH**/ ?>