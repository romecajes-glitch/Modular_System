<!-- Logout Confirmation Modal -->
<div id="logoutConfirmationModal" class="fixed inset-0 bg-black bg-opacity-50 hidden overflow-y-auto h-full w-full z-[10000]" onclick="closeLogoutModal()">
    <div class="relative top-20 mx-auto p-0 border w-full max-w-md shadow-lg rounded-lg bg-white" onclick="event.stopPropagation()">
        <!-- Modal Header -->
        <div class="flex items-center justify-between p-6 border-b border-gray-200">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-sign-out-alt text-red-600 text-lg"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">Confirm Logout</h3>
                    <p class="text-sm text-gray-500">Are you sure you want to log out?</p>
                </div>
            </div>
            <button onclick="closeLogoutModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="p-6">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-exclamation-triangle text-yellow-600"></i>
                    </div>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-gray-700">
                        You will be logged out of your account and redirected to the login page. Any unsaved changes will be lost.
                    </p>
                </div>
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="flex items-center justify-end gap-3 p-6 border-t border-gray-200 bg-gray-50">
            <button onclick="closeLogoutModal()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                Cancel
            </button>
            <button onclick="confirmLogout()" class="px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                <i class="fas fa-sign-out-alt mr-2"></i>
                Logout
            </button>
        </div>
    </div>
</div>

<script>
function openLogoutModal() {
    document.getElementById('logoutConfirmationModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeLogoutModal() {
    document.getElementById('logoutConfirmationModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

function confirmLogout() {
    // Submit the logout form
    document.getElementById('logout-form').submit();
}

// Close modal when pressing Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const modal = document.getElementById('logoutConfirmationModal');
        if (modal && !modal.classList.contains('hidden')) {
            closeLogoutModal();
        }
    }
});
</script>
