<!-- Login Modal -->
<div id="login-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black bg-opacity-50">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-md">
        <div class="p-6 <?php if($errors->any()): ?> shake <?php endif; ?>">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-2xl font-bold text-gray-800">Login</h3>
                <button id="close-login-modal" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <?php if($errors->has('username') || $errors->has('password')): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Login Failed!</strong>
                <span class="block sm:inline"> Invalid username or password.</span>
            </div>
            <?php endif; ?>

            <form class="space-y-4" method="POST" action="<?php echo e(route('custom.login')); ?>">
                <?php echo csrf_field(); ?>

                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                    <input name="username" type="text" id="username" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2" required>
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <div class="relative">
                        <input name="password" type="password" id="password" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2" required>
                        <button type="button" id="toggle-password" class="absolute inset-y-0 right-0 pr-3 flex items-center text-sm text-gray-500">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember-me" name="remember" type="checkbox" class="h-4 w-4 text-blue-600 border-gray-300 rounded">
                        <label for="remember-me" class="ml-2 block text-sm text-gray-700">Remember me</label>
                    </div>
                    <div class="text-sm">
                        <a href="#" class="font-medium text-blue-600 hover:text-blue-500">Forgot password?</a>
                    </div>
                </div>

                <div>
                    <button type="submit" id="login-submit-btn" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 relative">
                        <span id="login-button-text">Log In</span>
                        <span id="login-spinner" class="hidden absolute left-1/2 -translate-x-1/2">
                            <svg class=animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                            </svg>
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php /**PATH C:\Users\gucor\OneDrive\Documents\Herd\Modular_System\resources\views/partials/login_modal.blade.php ENDPATH**/ ?>