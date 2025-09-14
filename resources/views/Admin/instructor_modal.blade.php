<!-- Add Instructor Modal -->
<div id="addInstructorModal" class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm hidden overflow-y-auto h-full w-full z-50">
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-2xl mx-auto transform transition-all duration-300 scale-100 my-8">
        <!-- Modal Header -->
        <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-4 sm:px-8 py-4 sm:py-6 rounded-t-2xl">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-2 sm:space-x-3">
                    <div class="bg-white bg-opacity-20 p-2 sm:p-3 rounded-xl">
                        <i class="fas fa-chalkboard-teacher text-white text-lg sm:text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg sm:text-2xl font-bold text-white">Add New Instructor</h3>
                        <p class="text-blue-100 text-xs sm:text-sm hidden sm:block">Create a new instructor account for the system</p>
                    </div>
                </div>
                <button id="closeInstructorModal" class="text-white hover:text-gray-200 transition-colors p-2 hover:bg-white hover:bg-opacity-10 rounded-lg">
                    <i class="fas fa-times text-lg sm:text-xl"></i>
                </button>
            </div>
        </div>
        
        <!-- Modal Body -->
        <div class="p-4 sm:p-8">
            <form id="addInstructorForm" class="space-y-6">
                @csrf
                
                <!-- General Error Display -->
                <div id="instructorGeneralError" class="hidden bg-red-50 border border-red-200 rounded-lg p-4">
                    <div class="flex items-start">
                        <i class="fas fa-exclamation-triangle text-red-600 mt-0.5 mr-3"></i>
                        <div>
                            <p class="text-sm text-red-800 font-medium">Error:</p>
                            <p id="instructorGeneralErrorText" class="text-sm text-red-700"></p>
                        </div>
                    </div>
                </div>
                
                <!-- Personal Information Section -->
                <div class="bg-gray-50 rounded-xl p-4 sm:p-6">
                    <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-user mr-2 text-blue-600"></i>
                        Personal Information
                    </h4>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2" for="instructorFirstName">
                                First Name <span class="text-red-500">*</span>
                            </label>
                            <input class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200" id="instructorFirstName" name="first_name" type="text" placeholder="Enter first name" required>
                            <span class="text-red-500 text-xs mt-1 hidden" id="instructorFirstNameError"></span>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2" for="instructorLastName">
                                Last Name <span class="text-red-500">*</span>
                            </label>
                            <input class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200" id="instructorLastName" name="last_name" type="text" placeholder="Enter last name" required>
                            <span class="text-red-500 text-xs mt-1 hidden" id="instructorLastNameError"></span>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2" for="instructorMiddleName">
                                Middle Name
                            </label>
                            <input class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200" id="instructorMiddleName" name="middle_name" type="text" placeholder="Enter middle name">
                            <span class="text-red-500 text-xs mt-1 hidden" id="instructorMiddleNameError"></span>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2" for="instructorSuffixName">
                                Suffix Name
                            </label>
                            <input class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200" id="instructorSuffixName" name="suffix_name" type="text" placeholder="Jr, Sr, III, etc.">
                            <span class="text-red-500 text-xs mt-1 hidden" id="instructorSuffixNameError"></span>
                        </div>
                    </div>
                </div>

                <!-- Professional Information Section -->
                <div class="bg-gray-50 rounded-xl p-4 sm:p-6">
                    <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-briefcase mr-2 text-blue-600"></i>
                        Professional Information
                    </h4>
                    
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2" for="instructorProgramHandled">
                                Program Handled <span class="text-red-500">*</span>
                            </label>
                            <select class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200" id="instructorProgramHandled" name="program_handled" required>
                                <option value="">Select a program to handle</option>
                                @foreach($programs as $program)
                                    <option value="{{ $program->id }}">{{ $program->name }}</option>
                                @endforeach
                            </select>
                            <span class="text-red-500 text-xs mt-1 hidden" id="instructorProgramHandledError"></span>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2" for="instructorEmail">
                                Email Address <span class="text-red-500">*</span>
                            </label>
                            <input class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200" id="instructorEmail" name="email" type="email" placeholder="instructor@example.com" required>
                            <span class="text-red-500 text-xs mt-1 hidden" id="instructorEmailError"></span>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2" for="instructorBirthdate">
                                Birthdate <span class="text-red-500">*</span>
                            </label>
                            <input class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200" id="instructorBirthdate" name="birthdate" type="date" required>
                            <span class="text-red-500 text-xs mt-1 hidden" id="instructorBirthdateError"></span>
                            <span class="text-red-500 text-xs mt-1 hidden" id="instructorPasswordError"></span>
                            <div class="mt-2 p-3 bg-blue-50 rounded-lg border border-blue-200">
                                <p class="text-sm text-blue-800">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    <strong>Auto-generated credentials:</strong> Username will be generated from name, and password will be set to birthdate (YYYY-MM-DD format)
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Auto-generated fields (hidden) -->
                <input type="hidden" id="instructorUsername" name="username">
                <input type="hidden" id="instructorPassword" name="password">
                <input type="hidden" name="role" value="instructor">
            </form>
        </div>
        
        <!-- Modal Footer -->
        <div class="bg-gray-50 px-4 sm:px-8 py-4 sm:py-6 rounded-b-2xl border-t border-gray-200">
            <div class="flex flex-col sm:flex-row items-center justify-between space-y-3 sm:space-y-0">
                <div class="text-xs sm:text-sm text-gray-500 text-center sm:text-left">
                    <i class="fas fa-shield-alt mr-1"></i>
                    All information is securely stored and encrypted
                </div>
                <div class="flex items-center space-x-3 w-full sm:w-auto">
                    <button type="button" id="cancelInstructorBtn" class="flex-1 sm:flex-none px-4 sm:px-6 py-2 sm:py-3 bg-gray-500 hover:bg-gray-600 text-white font-semibold rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 text-sm sm:text-base">
                        <i class="fas fa-times mr-1 sm:mr-2"></i>Cancel
                    </button>
                    <button type="submit" id="saveInstructorBtn" form="addInstructorForm" class="flex-1 sm:flex-none px-4 sm:px-6 py-2 sm:py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 shadow-lg hover:shadow-xl text-sm sm:text-base">
                        <i class="fas fa-user-plus mr-1 sm:mr-2"></i>Create Instructor
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

