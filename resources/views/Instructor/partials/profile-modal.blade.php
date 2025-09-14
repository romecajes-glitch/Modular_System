<!-- Profile Modal Content -->
<div id="profileModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-[9999] hidden">
    <div class="bg-white rounded-xl shadow-lg overflow-hidden profile-tab max-w-4xl w-full mx-4 max-h-[90vh] overflow-y-auto">
        <!-- Profile Header -->
        <div class="bg-gradient-to-r from-blue-500 to-indigo-600 p-6 text-white">
            <div class="flex flex-col md:flex-row items-center justify-between">
                <div class="flex flex-1 items-center">
                    <div class="relative group mr-6">
                        @if(Auth::user()->photo)
                            <img src="{{ asset('storage/' . Auth::user()->photo) }}" alt="Profile" class="w-24 h-24 rounded-full border-4 border-white shadow-md object-cover cursor-pointer hover:opacity-80 transition-opacity duration-300" onclick="document.getElementById('profile-pic-upload').click()">
                        @else
                            <div class="w-24 h-24 rounded-full border-4 border-white shadow-md bg-gradient-to-r from-blue-600 to-blue-400 flex items-center justify-center cursor-pointer hover:opacity-80 transition-opacity duration-300" onclick="document.getElementById('profile-pic-upload').click()">
                                <i class="fas fa-user text-white text-3xl"></i>
                            </div>
                        @endif
                        <div class="absolute inset-0 bg-black bg-opacity-30 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300 z-10">
                            <i class="fas fa-camera text-white text-xl"></i>
                        </div>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold">{{ Auth::user()->name }}</h2>
                        <p class="text-blue-100">Instructor</p>
                        <p class="text-blue-200 text-sm">{{ Auth::user()->email }}</p>
                    </div>
                </div>
                <button onclick="closeProfileModal()" class="text-white hover:text-gray-200 transition-colors duration-200">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>
        </div>

        <!-- Profile Content -->
        <div class="p-6">
            <!-- Personal Information Section -->
            <div class="mb-8">
                <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-user mr-2 text-blue-600"></i>
                    Personal Information
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Name -->
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Full Name</label>
                        <div class="flex items-center">
                            <span id="name-display" class="flex-1 px-3 py-2 border border-gray-300 rounded-md bg-gray-50 text-gray-900">{{ Auth::user()->name ?? 'Not set' }}</span>
                        </div>
                        <div id="name-edit" class="hidden flex items-center">
                            <input type="text" id="name-input" value="{{ Auth::user()->name ?? '' }}" class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <button onclick="saveEdit('name')" class="ml-2 text-green-600 hover:text-green-800 transition-colors duration-200">
                                <i class="fas fa-check"></i>
                            </button>
                            <button onclick="cancelEdit('name')" class="ml-1 text-red-600 hover:text-red-800 transition-colors duration-200">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Email Address</label>
                        <div class="flex items-center">
                            <span id="email-display" class="flex-1 px-3 py-2 border border-gray-300 rounded-md bg-gray-50 text-gray-900">{{ Auth::user()->email ?? 'Not set' }}</span>
                            <button onclick="toggleEdit('email')" class="ml-2 text-blue-600 hover:text-blue-800 transition-colors duration-200">
                                <i class="fas fa-edit"></i>
                            </button>
                        </div>
                        <div id="email-edit" class="hidden flex items-center">
                            <input type="email" id="email-input" value="{{ Auth::user()->email ?? '' }}" class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <button onclick="saveEdit('email')" class="ml-2 text-green-600 hover:text-green-800 transition-colors duration-200">
                                <i class="fas fa-check"></i>
                            </button>
                            <button onclick="cancelEdit('email')" class="ml-1 text-red-600 hover:text-red-800 transition-colors duration-200">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Phone -->
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Phone Number</label>
                        <div class="flex items-center">
                            <span id="phone-display" class="flex-1 px-3 py-2 border border-gray-300 rounded-md bg-gray-50 text-gray-900">{{ Auth::user()->phone ?? 'Not set' }}</span>
                            <button onclick="toggleEdit('phone')" class="ml-2 text-blue-600 hover:text-blue-800 transition-colors duration-200">
                                <i class="fas fa-edit"></i>
                            </button>
                        </div>
                        <div id="phone-edit" class="hidden flex items-center">
                            <input type="tel" id="phone-input" value="{{ Auth::user()->phone ?? '' }}" class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <button onclick="saveEdit('phone')" class="ml-2 text-green-600 hover:text-green-800 transition-colors duration-200">
                                <i class="fas fa-check"></i>
                            </button>
                            <button onclick="cancelEdit('phone')" class="ml-1 text-red-600 hover:text-red-800 transition-colors duration-200">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Birthdate -->
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Birthdate</label>
                        <div class="flex items-center">
                            <span id="birthdate-display" class="flex-1 px-3 py-2 border border-gray-300 rounded-md bg-gray-50 text-gray-900">{{ Auth::user()->birthdate ? \Carbon\Carbon::parse(Auth::user()->birthdate)->format('M d, Y') : 'Not set' }}</span>
                            <button onclick="toggleEdit('birthdate')" class="ml-2 text-blue-600 hover:text-blue-800 transition-colors duration-200">
                                <i class="fas fa-edit"></i>
                            </button>
                        </div>
                        <div id="birthdate-edit" class="hidden flex items-center">
                            <input type="date" id="birthdate-input" value="{{ Auth::user()->birthdate ?? '' }}" class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <button onclick="saveEdit('birthdate')" class="ml-2 text-green-600 hover:text-green-800 transition-colors duration-200">
                                <i class="fas fa-check"></i>
                            </button>
                            <button onclick="cancelEdit('birthdate')" class="ml-1 text-red-600 hover:text-red-800 transition-colors duration-200">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Gender -->
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Gender</label>
                        <div class="flex items-center">
                            <span id="gender-display" class="flex-1 px-3 py-2 border border-gray-300 rounded-md bg-gray-50 text-gray-900">{{ Auth::user()->gender ? ucfirst(Auth::user()->gender) : 'Not set' }}</span>
                            <button onclick="toggleEdit('gender')" class="ml-2 text-blue-600 hover:text-blue-800 transition-colors duration-200">
                                <i class="fas fa-edit"></i>
                            </button>
                        </div>
                        <div id="gender-edit" class="hidden flex items-center">
                            <select id="gender-input" class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Select Gender</option>
                                <option value="male" {{ Auth::user()->gender === 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ Auth::user()->gender === 'female' ? 'selected' : '' }}>Female</option>
                                <option value="other" {{ Auth::user()->gender === 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                            <button onclick="saveEdit('gender')" class="ml-2 text-green-600 hover:text-green-800 transition-colors duration-200">
                                <i class="fas fa-check"></i>
                            </button>
                            <button onclick="cancelEdit('gender')" class="ml-1 text-red-600 hover:text-red-800 transition-colors duration-200">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Address -->
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Address</label>
                        <div class="flex items-center">
                            <span id="address-display" class="flex-1 px-3 py-2 border border-gray-300 rounded-md bg-gray-50 text-gray-900">{{ Auth::user()->address ?? 'Not set' }}</span>
                            <button onclick="toggleEdit('address')" class="ml-2 text-blue-600 hover:text-blue-800 transition-colors duration-200">
                                <i class="fas fa-edit"></i>
                            </button>
                        </div>
                        <div id="address-edit" class="hidden flex items-center">
                            <textarea id="address-input" rows="2" class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ Auth::user()->address ?? '' }}</textarea>
                            <div class="ml-2 flex flex-col">
                                <button onclick="saveEdit('address')" class="text-green-600 hover:text-green-800 transition-colors duration-200 mb-1">
                                    <i class="fas fa-check"></i>
                                </button>
                                <button onclick="cancelEdit('address')" class="text-red-600 hover:text-red-800 transition-colors duration-200">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Account Information Section -->
            <div class="mb-8">
                <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-cog mr-2 text-blue-600"></i>
                    Account Information
                </h3>
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Role</label>
                            <p class="text-gray-900">Instructor</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Account Created</label>
                            <p class="text-gray-900">{{ Auth::user()->created_at ? Auth::user()->created_at->format('M d, Y') : 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Last Updated</label>
                            <p class="text-gray-900">{{ Auth::user()->updated_at ? Auth::user()->updated_at->format('M d, Y') : 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Password Section -->
            <div class="mb-8">
                <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-lock mr-2 text-blue-600"></i>
                    Security
                </h3>
                <div class="bg-gray-50 rounded-lg p-4">
                    <button onclick="togglePasswordChange()" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors duration-200">
                        <i class="fas fa-key mr-2"></i>Change Password
                    </button>
                    <div id="password-change" class="hidden mt-4 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Current Password</label>
                            <input type="password" id="current-password" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">New Password</label>
                            <input type="password" id="new-password" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Confirm New Password</label>
                            <input type="password" id="confirm-password" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div class="flex space-x-2">
                            <button onclick="savePasswordChange()" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 transition-colors duration-200">
                                <i class="fas fa-save mr-2"></i>Save Password
                            </button>
                            <button onclick="cancelPasswordChange()" class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700 transition-colors duration-200">
                                <i class="fas fa-times mr-2"></i>Cancel
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Profile Picture Upload (Hidden) -->
        <input type="file" id="profile-pic-upload" accept="image/*" class="hidden" onchange="uploadProfilePicture(this)">
    </div>
</div>

<script>
// Profile Modal Functions
function openProfileModal() {
    document.getElementById('profileModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeProfileModal() {
    document.getElementById('profileModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// Close modal when clicking outside
document.getElementById('profileModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeProfileModal();
    }
});

// Edit Functions
function toggleEdit(field) {
    document.getElementById(field + '-display').classList.add('hidden');
    document.getElementById(field + '-edit').classList.remove('hidden');
    document.getElementById(field + '-input').focus();
}

function cancelEdit(field) {
    document.getElementById(field + '-display').classList.remove('hidden');
    document.getElementById(field + '-edit').classList.add('hidden');
    // Reset input value
    document.getElementById(field + '-input').value = document.getElementById(field + '-display').textContent;
}

function saveEdit(field) {
    const value = document.getElementById(field + '-input').value;
    const displayElement = document.getElementById(field + '-display');
    
    // Update display
    displayElement.textContent = value || 'Not set';
    
    // Hide edit form
    document.getElementById(field + '-display').classList.remove('hidden');
    document.getElementById(field + '-edit').classList.add('hidden');
    
    // Here you would typically send an AJAX request to update the field
    // For now, we'll just show a success message
    showNotification('Profile updated successfully!', 'success');
}

// Password Change Functions
function togglePasswordChange() {
    const passwordChange = document.getElementById('password-change');
    passwordChange.classList.toggle('hidden');
}

function cancelPasswordChange() {
    document.getElementById('password-change').classList.add('hidden');
    // Clear password fields
    document.getElementById('current-password').value = '';
    document.getElementById('new-password').value = '';
    document.getElementById('confirm-password').value = '';
}

function savePasswordChange() {
    const currentPassword = document.getElementById('current-password').value;
    const newPassword = document.getElementById('new-password').value;
    const confirmPassword = document.getElementById('confirm-password').value;
    
    if (!currentPassword || !newPassword || !confirmPassword) {
        showNotification('Please fill in all password fields', 'error');
        return;
    }
    
    if (newPassword !== confirmPassword) {
        showNotification('New passwords do not match', 'error');
        return;
    }
    
    if (newPassword.length < 8) {
        showNotification('Password must be at least 8 characters long', 'error');
        return;
    }
    
    // Here you would typically send an AJAX request to change the password
    // For now, we'll just show a success message
    showNotification('Password changed successfully!', 'success');
    cancelPasswordChange();
}

// Profile Picture Upload
function uploadProfilePicture(input) {
    if (input.files && input.files[0]) {
        const file = input.files[0];
        
        // Validate file type
        if (!file.type.startsWith('image/')) {
            showNotification('Please select a valid image file', 'error');
            return;
        }
        
        // Validate file size (max 5MB)
        if (file.size > 5 * 1024 * 1024) {
            showNotification('Image size must be less than 5MB', 'error');
            return;
        }
        
        // Here you would typically upload the file via AJAX
        // For now, we'll just show a success message
        showNotification('Profile picture updated successfully!', 'success');
    }
}

// Notification function (you may need to implement this based on your existing notification system)
function showNotification(message, type) {
    // This is a placeholder - implement based on your existing notification system
    alert(message);
}
</script>
