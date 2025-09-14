<!-- Edit User Modal -->
<div id="editUserModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full" style="display: none; z-index: 40;">
    <div class="relative top-4 sm:top-10 mx-auto p-4 sm:p-6 border w-full max-w-xs sm:max-w-2xl lg:max-w-4xl shadow-xl rounded-lg bg-white m-4">
        <div class="mt-2">
            <!-- Header -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 sm:mb-6 pb-4 border-b border-gray-200 gap-4">
                <div class="flex-1">
                    <h3 class="text-lg sm:text-xl font-semibold text-gray-900">Edit User Information</h3>
                    <p class="text-xs sm:text-sm text-gray-600 mt-1">Manage user details and account settings</p>
                </div>
                <button id="closeEditUserModal" class="text-gray-400 hover:text-gray-600 hover:bg-gray-100 p-2 rounded-full transition-colors self-end sm:self-auto">
                    <svg class="h-5 w-5 sm:h-6 sm:w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <form id="editUserForm">
                <?php echo csrf_field(); ?>

                <!-- User Information Section -->
                <div class="mb-4 sm:mb-6">
                    <h4 class="text-base sm:text-lg font-medium text-gray-800 mb-3 sm:mb-4 flex items-center">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        User Information
                    </h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                        <div>
                            <label class="block text-gray-700 text-sm font-medium mb-2" for="userFullName">
                                Full Name
                            </label>
                            <input class="w-full px-2 sm:px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50" id="userFullName" name="full_name" type="text" readonly>
                        </div>
                        <div>
                            <label class="block text-gray-700 text-sm font-medium mb-2" for="userUsername">
                                Username
                            </label>
                            <input class="w-full px-2 sm:px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50" id="userUsername" name="username" type="text" readonly>
                        </div>
                        <div>
                            <label class="block text-gray-700 text-sm font-medium mb-2" for="userRole">
                                Role
                            </label>
                            <input class="w-full px-2 sm:px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50" id="userRole" name="role" type="text" readonly>
                        </div>
                        <div>
                            <label class="block text-gray-700 text-sm font-medium mb-2" for="userEmail">
                                Email Address
                            </label>
                            <input class="w-full px-2 sm:px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50" id="userEmail" name="email" type="email" readonly>
                        </div>
                        <div>
                            <label class="block text-gray-700 text-sm font-medium mb-2" for="userPhone">
                                Phone Number
                            </label>
                            <input class="w-full px-2 sm:px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50" id="userPhone" name="phone" type="text" readonly>
                        </div>
                        <div>
                            <label class="block text-gray-700 text-sm font-medium mb-2" for="userStatus">
                                Account Status
                            </label>
                            <select class="w-full px-2 sm:px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" id="userStatus" name="status" required>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Account Details Section -->
                <div class="mb-6">
                    <h4 class="text-lg font-medium text-gray-800 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Account Details
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-gray-700 text-sm font-medium mb-2" for="userCreatedAt">
                                Account Created
                            </label>
                            <input class="w-full px-2 sm:px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50" id="userCreatedAt" name="created_at" type="text" readonly>
                        </div>
                        <div>
                            <label class="block text-gray-700 text-sm font-medium mb-2" for="userLastLogin">
                                Last Login
                            </label>
                            <input class="w-full px-2 sm:px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50" id="userLastLogin" name="last_login" type="text" readonly>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row justify-end items-center space-y-3 sm:space-y-0 sm:space-x-3 pt-6 border-t border-gray-200">
                    <button type="button" id="cancelEditUserBtn" class="w-full sm:w-auto bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-6 rounded-md transition-colors focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                        Cancel
                    </button>
                    <button type="button" id="resetPasswordBtn" class="w-full sm:w-auto bg-yellow-500 hover:bg-yellow-600 text-white font-medium py-2 px-6 rounded-md transition-colors focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 flex items-center justify-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                        </svg>
                        Reset Password
                    </button>
                    <button type="submit" id="saveEditUserBtn" class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-md transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 flex items-center justify-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Password Confirmation Modal -->
<div id="passwordConfirmationModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden" style="z-index: 60;">
    <div class="bg-white rounded-lg shadow-xl p-6 max-w-md w-full">
        <div class="text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 mb-4">
                <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2" id="passwordConfirmTitle">Confirm Your Password</h3>
            <p class="text-sm text-gray-500 mb-4" id="passwordConfirmMessage">Please enter your password to confirm this action.</p>

            <form id="passwordConfirmationForm">
                <div class="mb-4">
                    <label for="adminPassword" class="block text-sm font-medium text-gray-700 mb-2">Your Password</label>
                    <input type="password" id="adminPassword" name="password"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="Enter your password" required>
                    <span class="text-red-500 text-xs italic hidden" id="passwordError"></span>
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" id="cancelPasswordConfirm" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500">
                        Cancel
                    </button>
                    <button type="submit" id="confirmPasswordBtn" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        Confirm
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Password Reset Success Modal -->
<div id="passwordResetSuccessModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden" style="z-index: 60;">
    <div class="bg-white rounded-lg shadow-xl p-6 max-w-md w-full text-center">
        <h2 class="text-2xl font-bold text-green-700 mb-3">✅ Password Reset Successful!</h2>
        <p class="text-gray-700 mb-2">The user's password has been reset successfully.</p>
        <p class="text-gray-700 mb-2">Please provide these credentials to the user.</p>

        <div class="bg-blue-50 p-4 mt-4 rounded-md text-left">
            <p><strong>Username:</strong> <span id="reset-username" class="font-mono text-gray-800"></span></p>
            <p><strong>New Password:</strong> <span id="reset-password" class="font-mono text-gray-800"></span></p>
        </div>

        <button onclick="document.getElementById('passwordResetSuccessModal').classList.add('hidden')" class="mt-6 inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none">
            Close
        </button>
    </div>
</div>

<script>
    // Close the password reset success modal and also close the edit user modal
    document.getElementById('passwordResetSuccessModal').querySelector('button').addEventListener('click', function() {
        document.getElementById('passwordResetSuccessModal').classList.add('hidden');
        document.getElementById('editUserModal').classList.add('hidden');
    });
</script>
<?php /**PATH C:\Users\gucor\OneDrive\Documents\Herd\Modular_System\resources\views/Admin/edit_user_modal.blade.php ENDPATH**/ ?>