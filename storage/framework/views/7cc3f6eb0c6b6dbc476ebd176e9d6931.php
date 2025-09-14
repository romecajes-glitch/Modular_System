<!-- Profile Modal Content -->
<!-- CSRF Token for Profile Functions -->
<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

<div id="profileModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-xl shadow-lg overflow-hidden profile-tab max-w-4xl w-full mx-4 max-h-[90vh] overflow-y-auto custom-scrollbar">
        <!-- Profile Header -->
        <div class="bg-gradient-to-r from-blue-500 to-indigo-600 p-6 text-white">
            <div class="flex flex-col md:flex-row items-center justify-between">
                <div class="flex flex-1 items-center">
                    <div class="relative group mr-6" title="Click to change profile picture">
                        <?php if(Auth::user()->photo): ?>
                            <img src="<?php echo e(asset('storage/' . Auth::user()->photo)); ?>" alt="Profile" class="w-24 h-24 rounded-full border-4 border-white shadow-md object-cover hover:opacity-80 transition-all duration-300 hover:scale-105">
                        <?php else: ?>
                            <div class="w-24 h-24 rounded-full border-4 border-white shadow-md bg-gradient-to-r from-blue-600 to-blue-400 flex items-center justify-center hover:opacity-80 transition-all duration-300 hover:scale-105">
                                <i class="fas fa-user text-white text-3xl"></i>
                            </div>
                        <?php endif; ?>
                        <div class="absolute inset-0 bg-black bg-opacity-30 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300 z-10 cursor-pointer" onclick="console.log('Camera overlay clicked'); const fileInput = document.getElementById('profile-pic-upload'); console.log('File input found:', !!fileInput); if(fileInput) fileInput.click(); else console.error('File input not found!');">
                            <i class="fas fa-camera text-white text-xl"></i>
                        </div>
                        <!-- Profile Picture Upload (Hidden) -->
                        <input type="file" id="profile-pic-upload" accept="image/*" class="hidden" onchange="console.log('File input changed'); uploadProfilePicture(this)">
                    </div>
                    <div class="flex flex-col space-y-2">
                        <div class="relative">
                            <h2 class="text-2xl font-bold text-white" style="text-shadow: 0 0 20px rgba(30,58,138,0.8), 0 0 40px rgba(30,58,138,0.6), 0 0 60px rgba(30,58,138,0.4); filter: drop-shadow(0 0 10px rgba(30,58,138,0.5));"><?php echo e(Auth::user()->name); ?></h2>
                        </div>
                        <div class="relative">
                            <p class="text-blue-100 font-bold" style="text-shadow: 0 0 15px rgba(30,58,138,0.7), 0 0 30px rgba(30,58,138,0.5), 0 0 45px rgba(30,58,138,0.3); filter: drop-shadow(0 0 8px rgba(30,58,138,0.4));">Admin</p>
                        </div>
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
                    <!-- Email -->
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Email Address</label>
                        <div class="flex items-center">
                            <span id="email-display" class="flex-1 px-3 py-2 border border-gray-300 rounded-md bg-gray-50 text-gray-900"><?php echo e(Auth::user()->email ?? 'Not set'); ?></span>
                            <button onclick="toggleEdit('email')" class="ml-2 text-blue-600 hover:text-blue-800 transition-colors duration-200">
                                <i class="fas fa-edit"></i>
                            </button>
                        </div>
                        <div id="email-edit" class="hidden flex items-center">
                            <input type="email" id="profile-email-input" value="<?php echo e(Auth::user()->email ?? ''); ?>" class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
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
                            <span id="phone-display" class="flex-1 px-3 py-2 border border-gray-300 rounded-md bg-gray-50 text-gray-900"><?php echo e(Auth::user()->phone ?? 'Not set'); ?></span>
                            <button onclick="toggleEdit('phone')" class="ml-2 text-blue-600 hover:text-blue-800 transition-colors duration-200">
                                <i class="fas fa-edit"></i>
                            </button>
                        </div>
                        <div id="phone-edit" class="hidden flex items-center">
                            <input type="tel" id="profile-phone-input" value="<?php echo e(Auth::user()->phone ?? ''); ?>" maxlength="11" pattern="09[0-9]{9}" placeholder="09XXXXXXXXX" class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" oninput="validatePhoneInput(this)" onkeypress="return isNumberKey(event)">
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
                            <span id="birthdate-display" class="flex-1 px-3 py-2 border border-gray-300 rounded-md bg-gray-50 text-gray-900"><?php echo e(Auth::user()->birthdate ? \Carbon\Carbon::parse(Auth::user()->birthdate)->format('M d, Y') : 'Not set'); ?></span>
                            <button onclick="toggleEdit('birthdate')" class="ml-2 text-blue-600 hover:text-blue-800 transition-colors duration-200">
                                <i class="fas fa-edit"></i>
                            </button>
                        </div>
                        <div id="birthdate-edit" class="hidden flex items-center">
                            <input type="date" id="birthdate-input" value="<?php echo e(Auth::user()->birthdate ?? ''); ?>" class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
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
                            <span id="gender-display" class="flex-1 px-3 py-2 border border-gray-300 rounded-md bg-gray-50 text-gray-900"><?php echo e(Auth::user()->gender ? ucfirst(Auth::user()->gender) : 'Not set'); ?></span>
                            <button onclick="toggleEdit('gender')" class="ml-2 text-blue-600 hover:text-blue-800 transition-colors duration-200">
                                <i class="fas fa-edit"></i>
                            </button>
                        </div>
                        <div id="gender-edit" class="hidden flex items-center">
                            <select id="gender-input" class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Select Gender</option>
                                <option value="male" <?php echo e(Auth::user()->gender === 'male' ? 'selected' : ''); ?>>Male</option>
                                <option value="female" <?php echo e(Auth::user()->gender === 'female' ? 'selected' : ''); ?>>Female</option>
                                <option value="other" <?php echo e(Auth::user()->gender === 'other' ? 'selected' : ''); ?>>Other</option>
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
                            <span id="address-display" class="flex-1 px-3 py-2 border border-gray-300 rounded-md bg-gray-50 text-gray-900"><?php echo e(Auth::user()->address ?? 'Not set'); ?></span>
                            <button onclick="toggleEdit('address')" class="ml-2 text-blue-600 hover:text-blue-800 transition-colors duration-200">
                                <i class="fas fa-edit"></i>
                            </button>
                        </div>
                        <div id="address-edit" class="hidden flex items-center">
                            <textarea id="address-input" rows="2" class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"><?php echo e(Auth::user()->address ?? ''); ?></textarea>
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
                            <label class="block text-sm font-medium text-gray-700">Account Created</label>
                            <p class="text-gray-900"><?php echo e(Auth::user()->created_at ? Auth::user()->created_at->format('M d, Y') : 'N/A'); ?></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Last Updated</label>
                            <p class="text-gray-900"><?php echo e(Auth::user()->updated_at ? Auth::user()->updated_at->format('M d, Y') : 'N/A'); ?></p>
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
                            <input type="password" id="profile-current-password" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">New Password</label>
                            <input type="password" id="profile-new-password" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Confirm New Password</label>
                            <input type="password" id="profile-confirm-password" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
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
    </div>
</div>

<script>
console.log('🚀 Profile.blade.php script is loading...');
// Profile Modal Functions
function openProfileModal() {
    console.log('openProfileModal called');
    const modal = document.getElementById('profileModal');
    if (modal) {
        console.log('Profile modal found, showing...');
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        
        // Check if file input exists
        const fileInput = document.getElementById('profile-pic-upload');
        console.log('File input exists in modal:', !!fileInput);
        if (fileInput) {
            console.log('File input found:', fileInput);
        } else {
            console.error('File input not found in modal!');
        }
    } else {
        console.error('Profile modal not found!');
    }
}

function closeProfileModal() {
    console.log('closeProfileModal called');
    const modal = document.getElementById('profileModal');
    if (modal) {
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
}

// Make modal functions globally available immediately
window.openProfileModal = openProfileModal;
window.closeProfileModal = closeProfileModal;
console.log('✅ Modal functions assigned to window immediately');
console.log('✅ window.openProfileModal type:', typeof window.openProfileModal);
console.log('✅ window.openProfileModal function:', window.openProfileModal);

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
    const inputId = field === 'email' || field === 'phone' ? 'profile-' + field + '-input' : field + '-input';
    document.getElementById(inputId).focus();
}

function cancelEdit(field) {
    document.getElementById(field + '-display').classList.remove('hidden');
    document.getElementById(field + '-edit').classList.add('hidden');
    // Reset input value
    const inputId = field === 'email' || field === 'phone' ? 'profile-' + field + '-input' : field + '-input';
    document.getElementById(inputId).value = document.getElementById(field + '-display').textContent;
}

function saveEdit(field) {
    const inputId = field === 'email' || field === 'phone' ? 'profile-' + field + '-input' : field + '-input';
    const value = document.getElementById(inputId).value;
    const displayElement = document.getElementById(field + '-display');
    
    // Validate the value
    if (!value || value.trim() === '') {
        showNotification('Please enter a valid value', 'error');
        return;
    }
    
    // Special validation for phone number
    if (field === 'phone') {
        if (!value.startsWith('09')) {
            showNotification('Phone number must start with 09', 'error');
            return;
        }
        if (value.length !== 11) {
            showNotification('Phone number must be exactly 11 digits', 'error');
            return;
        }
        if (!/^09[0-9]{9}$/.test(value)) {
            showNotification('Please enter a valid phone number', 'error');
            return;
        }
    }
    
    // Special handling for email (requires OTP verification)
    if (field === 'email') {
        // Get the save button and add loading state
        const saveButton = document.querySelector('button[onclick*="saveEdit(\'email\')"]');
        if (saveButton) {
            const originalHTML = saveButton.innerHTML;
            saveButton.disabled = true;
            saveButton.style.opacity = '0.6';
            saveButton.style.cursor = 'not-allowed';
            saveButton.innerHTML = `
                <svg class="animate-spin h-4 w-4 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            `;
            saveButton.setAttribute('data-original-html', originalHTML);
        }
        
        sendEmailOTP(value);
        return;
    }
    
    // Update the field via AJAX
    updateProfileField(field, value, displayElement);
}

// Password Change Functions
function togglePasswordChange() {
    const passwordChange = document.getElementById('password-change');
    passwordChange.classList.toggle('hidden');
}

function cancelPasswordChange() {
    document.getElementById('password-change').classList.add('hidden');
    // Clear password fields
    document.getElementById('profile-current-password').value = '';
    document.getElementById('profile-new-password').value = '';
    document.getElementById('profile-confirm-password').value = '';
}

function savePasswordChange() {
    const currentPassword = document.getElementById('profile-current-password').value;
    const newPassword = document.getElementById('profile-new-password').value;
    const confirmPassword = document.getElementById('profile-confirm-password').value;
    
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
    
    // Send AJAX request to change password
    changePassword(currentPassword, newPassword);
}

// Email OTP Functions
function sendEmailOTP(email) {
    const csrfToken = getCSRFToken();
    if (!csrfToken) {
        console.error('CSRF token not found');
        showNotification('CSRF token not found. Please refresh the page.', 'error');
        // Restore button state on error
        const saveButton = document.querySelector('button[onclick*="saveEdit(\'email\')"]');
        if (saveButton && saveButton.getAttribute('data-original-html')) {
            saveButton.disabled = false;
            saveButton.style.opacity = '1';
            saveButton.style.cursor = 'pointer';
            saveButton.innerHTML = saveButton.getAttribute('data-original-html');
        }
        return;
    }
    
    const formData = new FormData();
    formData.append('email', email);
    formData.append('_token', csrfToken);
    
    fetch('<?php echo e(route("admin.email.send_otp")); ?>', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': csrfToken
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('OTP sent to your email. Please check your inbox.', 'success');
            showOTPVerificationModal(email);
        } else {
            showNotification(data.message || 'Failed to send OTP', 'error');
            // Restore button state on error
            const saveButton = document.querySelector('button[onclick*="saveEdit(\'email\')"]');
            if (saveButton && saveButton.getAttribute('data-original-html')) {
                saveButton.disabled = false;
                saveButton.style.opacity = '1';
                saveButton.style.cursor = 'pointer';
                saveButton.innerHTML = saveButton.getAttribute('data-original-html');
            }
        }
    })
    .catch(error => {
        console.error('Error sending OTP:', error);
        showNotification('An error occurred while sending OTP', 'error');
        // Restore button state on error
        const saveButton = document.querySelector('button[onclick*="saveEdit(\'email\')"]');
        if (saveButton && saveButton.getAttribute('data-original-html')) {
            saveButton.disabled = false;
            saveButton.style.opacity = '1';
            saveButton.style.cursor = 'pointer';
            saveButton.innerHTML = saveButton.getAttribute('data-original-html');
        }
    });
}

function showOTPVerificationModal(email) {
    // Create OTP verification modal
    const modal = document.createElement('div');
    modal.id = 'otpVerificationModal';
    modal.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
    `;
    
    modal.innerHTML = `
        <div style="background: white; padding: 2rem; border-radius: 10px; max-width: 400px; width: 90%;">
            <h3 style="margin-bottom: 1rem; color: #333;">Verify Email Address</h3>
            <p style="margin-bottom: 1rem; color: #666;">Enter the 6-digit OTP sent to: <strong>${email}</strong></p>
            <input type="text" id="otpInput" placeholder="Enter OTP" maxlength="6" style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 5px; margin-bottom: 1rem; text-align: center; font-size: 1.2rem; letter-spacing: 0.5rem;">
            <div style="display: flex; gap: 1rem;">
                <button onclick="verifyEmailOTP('${email}')" style="flex: 1; padding: 0.75rem; background: #3b82f6; color: white; border: none; border-radius: 5px; cursor: pointer;">Verify</button>
                <button onclick="closeOTPModal()" style="flex: 1; padding: 0.75rem; background: #6b7280; color: white; border: none; border-radius: 5px; cursor: pointer;">Cancel</button>
            </div>
        </div>
    `;
    
    document.body.appendChild(modal);
    document.getElementById('otpInput').focus();
}

function verifyEmailOTP(email) {
    const otp = document.getElementById('otpInput').value;
    
    if (!otp || otp.length !== 6) {
        showNotification('Please enter a valid 6-digit OTP', 'error');
        return;
    }
    
    // Get the verify button and disable it to prevent multiple submissions
    const verifyButton = document.querySelector('button[onclick*="verifyEmailOTP"]');
    if (verifyButton) {
        const originalText = verifyButton.innerHTML;
        verifyButton.disabled = true;
        verifyButton.style.opacity = '0.6';
        verifyButton.style.cursor = 'not-allowed';
        verifyButton.innerHTML = `
            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Verifying...
        `;
        
        // Store original text for restoration
        verifyButton.setAttribute('data-original-text', originalText);
    }
    
    const csrfToken = getCSRFToken();
    if (!csrfToken) {
        console.error('CSRF token not found');
        showNotification('CSRF token not found. Please refresh the page.', 'error');
        // Restore button state
        if (verifyButton) {
            verifyButton.disabled = false;
            verifyButton.style.opacity = '1';
            verifyButton.style.cursor = 'pointer';
            verifyButton.innerHTML = verifyButton.getAttribute('data-original-text');
        }
        return;
    }
    
    const formData = new FormData();
    formData.append('email', email);
    formData.append('otp', otp);
    formData.append('_token', csrfToken);
    
    fetch('<?php echo e(route("admin.email.verify_otp")); ?>', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': csrfToken
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Email updated successfully!', 'success');
            closeOTPModal();
            // Update the display
            document.getElementById('email-display').textContent = email;
            document.getElementById('email-edit').classList.add('hidden');
            document.getElementById('email-display').classList.remove('hidden');
        } else {
            showNotification(data.message || 'Invalid OTP', 'error');
            // Restore button state on error
            if (verifyButton) {
                verifyButton.disabled = false;
                verifyButton.style.opacity = '1';
                verifyButton.style.cursor = 'pointer';
                verifyButton.innerHTML = verifyButton.getAttribute('data-original-text');
            }
        }
    })
    .catch(error => {
        console.error('Error verifying OTP:', error);
        showNotification('An error occurred while verifying OTP', 'error');
        // Restore button state on error
        if (verifyButton) {
            verifyButton.disabled = false;
            verifyButton.style.opacity = '1';
            verifyButton.style.cursor = 'pointer';
            verifyButton.innerHTML = verifyButton.getAttribute('data-original-text');
        }
    });
}

function closeOTPModal() {
    const modal = document.getElementById('otpVerificationModal');
    if (modal) {
        modal.remove();
    }
}

// Profile Field Update Function
function updateProfileField(field, value, displayElement) {
    const csrfToken = getCSRFToken();
    if (!csrfToken) {
        console.error('CSRF token not found');
        showNotification('CSRF token not found. Please refresh the page.', 'error');
        return;
    }
    
    let route = '';
    let data = { _token: csrfToken };
    
    switch(field) {
        case 'phone':
            route = '<?php echo e(route("admin.phone.update")); ?>';
            data.phone = value;
            break;
        case 'gender':
            route = '<?php echo e(route("admin.gender.update")); ?>';
            data.gender = value;
            break;
        case 'birthdate':
            route = '<?php echo e(route("admin.birthdate.update")); ?>';
            data.birthdate = value;
            break;
        case 'address':
            // Address doesn't have a specific route, we'll handle it with a generic update
            showNotification('Address update not implemented yet', 'error');
            return;
        default:
            showNotification('Unknown field', 'error');
            return;
    }
    
    const formData = new FormData();
    Object.keys(data).forEach(key => {
        formData.append(key, data[key]);
    });
    
    fetch(route, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': csrfToken
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update display based on field type
            if (field === 'birthdate') {
                const date = new Date(value);
                displayElement.textContent = date.toLocaleDateString('en-US', { 
                    year: 'numeric', 
                    month: 'short', 
                    day: 'numeric' 
                });
            } else if (field === 'gender') {
                displayElement.textContent = value.charAt(0).toUpperCase() + value.slice(1);
            } else {
                displayElement.textContent = value;
            }
            
            // Hide edit form
            document.getElementById(field + '-display').classList.remove('hidden');
            document.getElementById(field + '-edit').classList.add('hidden');
            
            showNotification('Profile updated successfully!', 'success');
        } else {
            showNotification(data.message || 'Failed to update profile', 'error');
        }
    })
    .catch(error => {
        console.error('Error updating profile:', error);
        showNotification('An error occurred while updating profile', 'error');
    });
}

// Password Change Function
function changePassword(currentPassword, newPassword) {
    const csrfToken = getCSRFToken();
    if (!csrfToken) {
        console.error('CSRF token not found');
        showNotification('CSRF token not found. Please refresh the page.', 'error');
        return;
    }
    
    const formData = new FormData();
    formData.append('current_password', currentPassword);
    formData.append('new_password', newPassword);
    formData.append('new_password_confirmation', newPassword);
    formData.append('_token', csrfToken);
    
    fetch('<?php echo e(route("admin.password.update")); ?>', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': csrfToken
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
    showNotification('Password changed successfully!', 'success');
    cancelPasswordChange();
        } else {
            showNotification(data.message || 'Failed to change password', 'error');
        }
    })
    .catch(error => {
        console.error('Error changing password:', error);
        showNotification('An error occurred while changing password', 'error');
    });
}

// Profile Picture Upload
function uploadProfilePicture(input) {
    console.log('uploadProfilePicture called with:', input);
    
    if (input.files && input.files[0]) {
        const file = input.files[0];
        console.log('File selected:', file.name, file.type, file.size);
        
        // Validate file type
        if (!file.type.startsWith('image/')) {
            showNotification('Please select a valid image file', 'error');
            return;
        }
        
        // Validate file size (max 2MB)
        if (file.size > 2 * 1024 * 1024) {
            showNotification('Image size must be less than 2MB', 'error');
            return;
        }
        
        // Create FormData for file upload
        const csrfToken = getCSRFToken();
        if (!csrfToken) {
            console.error('CSRF token not found');
            showNotification('CSRF token not found. Please refresh the page.', 'error');
            return;
        }
        
        const formData = new FormData();
        formData.append('profile_photo', file);
        formData.append('_token', csrfToken);
        
        // Show loading state
        const profileImg = document.querySelector('img[alt="Profile"]');
        const profileDiv = document.querySelector('.w-24.h-24.rounded-full');
        const originalContent = profileImg ? profileImg.outerHTML : profileDiv.innerHTML;
        
        if (profileImg) {
            profileImg.style.opacity = '0.5';
        } else if (profileDiv) {
            profileDiv.style.opacity = '0.5';
        }
        
        // Upload the file
        console.log('Uploading to:', '<?php echo e(route("admin.profile.photo.update")); ?>');
        console.log('FormData:', formData);
        
        fetch('<?php echo e(route("admin.profile.photo.update")); ?>', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken
            }
        })
        .then(response => {
            console.log('Response status:', response.status);
            return response.json();
        })
        .then(data => {
            console.log('Response data:', data);
            if (data.success) {
                console.log('Upload successful, updating UI...');
                
                // Update the profile image in the modal
                const profileImg = document.querySelector('img[alt="Profile"]');
                console.log('Profile image in modal found:', !!profileImg);
                if (profileImg) {
                    const newSrc = '<?php echo e(asset("storage/")); ?>/' + data.path + '?t=' + new Date().getTime();
                    console.log('Updating profile image src to:', newSrc);
                    profileImg.src = newSrc;
                }
                
                // Update the profile image in the top bar (if it exists)
                const topBarImg = document.querySelector('#adminDropdown img');
                console.log('Top bar image found:', !!topBarImg);
                if (topBarImg) {
                    const newSrc = '<?php echo e(asset("storage/")); ?>/' + data.path + '?t=' + new Date().getTime();
                    console.log('Updating top bar image src to:', newSrc);
                    topBarImg.src = newSrc;
                }
                
                // Update the profile image in the sidebar (if it exists)
                const sidebarImg = document.querySelector('.user-profile img');
                console.log('Sidebar image found:', !!sidebarImg);
                if (sidebarImg) {
                    const newSrc = '<?php echo e(asset("storage/")); ?>/' + data.path + '?t=' + new Date().getTime();
                    console.log('Updating sidebar image src to:', newSrc);
                    sidebarImg.src = newSrc;
                }
                
                // Also try to update any other profile images
                const allProfileImages = document.querySelectorAll('img[src*="storage/"]');
                console.log('Found', allProfileImages.length, 'profile images to update');
                allProfileImages.forEach((img, index) => {
                    if (img.src.includes('profile_photos') || img.alt === 'Profile Photo') {
                        const newSrc = '<?php echo e(asset("storage/")); ?>/' + data.path + '?t=' + new Date().getTime();
                        console.log(`Updating profile image ${index} src to:`, newSrc);
                        img.src = newSrc;
                    }
                });
                
                showNotification('Profile picture updated successfully!', 'success');
                
                // Restore button state after successful upload
                const saveButton = document.querySelector('button[onclick*="saveEdit(\'email\')"]');
                if (saveButton && saveButton.getAttribute('data-original-html')) {
                    saveButton.disabled = false;
                    saveButton.style.opacity = '1';
                    saveButton.style.cursor = 'pointer';
                    saveButton.innerHTML = saveButton.getAttribute('data-original-html');
                }
            } else {
                console.log('Upload failed:', data.message);
                showNotification(data.message || 'Failed to update profile picture', 'error');
                
                // Restore button state on error
                const saveButton = document.querySelector('button[onclick*="saveEdit(\'email\')"]');
                if (saveButton && saveButton.getAttribute('data-original-html')) {
                    saveButton.disabled = false;
                    saveButton.style.opacity = '1';
                    saveButton.style.cursor = 'pointer';
                    saveButton.innerHTML = saveButton.getAttribute('data-original-html');
                }
            }
        })
        .catch(error => {
            console.error('Upload error:', error);
            showNotification('An error occurred while uploading the image', 'error');
            
            // Restore button state on error
            const saveButton = document.querySelector('button[onclick*="saveEdit(\'email\')"]');
            if (saveButton && saveButton.getAttribute('data-original-html')) {
                saveButton.disabled = false;
                saveButton.style.opacity = '1';
                saveButton.style.cursor = 'pointer';
                saveButton.innerHTML = saveButton.getAttribute('data-original-html');
            }
        })
        .finally(() => {
            // Reset loading state
            if (profileImg) {
                profileImg.style.opacity = '1';
            } else if (profileDiv) {
                profileDiv.style.opacity = '1';
            }
            
            // Clear the input
            input.value = '';
        });
    }
}

// Notification function
function showNotification(message, type) {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 px-6 py-4 rounded-lg shadow-lg transition-all duration-300 transform translate-x-full`;
    
    // Set colors based on type
    if (type === 'success') {
        notification.className += ' bg-green-500 text-white';
    } else if (type === 'error') {
        notification.className += ' bg-red-500 text-white';
    } else {
        notification.className += ' bg-blue-500 text-white';
    }
    
    // Add icon
    const icon = type === 'success' ? 'fas fa-check-circle' : 
                 type === 'error' ? 'fas fa-exclamation-circle' : 
                 'fas fa-info-circle';
    
    notification.innerHTML = `
        <div class="flex items-center">
            <i class="${icon} mr-3"></i>
            <span>${message}</span>
            <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-white hover:text-gray-200">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;
    
    // Add to page
    document.body.appendChild(notification);
    
    // Animate in
    setTimeout(() => {
        notification.classList.remove('translate-x-full');
    }, 100);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        notification.classList.add('translate-x-full');
        setTimeout(() => {
            if (notification.parentElement) {
                notification.remove();
            }
        }, 300);
    }, 5000);
}

// Phone number validation functions
function isNumberKey(evt) {
    const charCode = (evt.which) ? evt.which : evt.keyCode;
    // Allow only numbers (0-9)
    if (charCode < 48 || charCode > 57) {
        evt.preventDefault();
        return false;
    }
    return true;
}

function validatePhoneInput(input) {
    let value = input.value;
    
    // Remove any non-numeric characters
    value = value.replace(/[^0-9]/g, '');
    
    // Ensure it starts with 09
    if (value.length > 0 && !value.startsWith('09')) {
        if (value.startsWith('9')) {
            value = '0' + value;
        } else if (!value.startsWith('0')) {
            value = '09' + value;
        }
    }
    
    // Limit to 11 characters
    if (value.length > 11) {
        value = value.substring(0, 11);
    }
    
    // Update the input value
    input.value = value;
    
    // Add visual feedback for invalid format
    if (value.length > 0 && value.length < 11) {
        input.style.borderColor = '#f59e0b'; // Orange for incomplete
    } else if (value.length === 11 && value.startsWith('09')) {
        input.style.borderColor = '#10b981'; // Green for valid
    } else if (value.length > 0) {
        input.style.borderColor = '#ef4444'; // Red for invalid
    } else {
        input.style.borderColor = '#d1d5db'; // Default gray
    }
}

// Helper function to get CSRF token
function getCSRFToken() {
    // First try to get from meta tag
    let csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (csrfToken) {
        return csrfToken.getAttribute('content');
    }
    
    // Fallback: try to get from any form with CSRF token
    const csrfInput = document.querySelector('input[name="_token"]');
    if (csrfInput) {
        return csrfInput.value;
    }
    
    // Last resort: try to get from window object if set elsewhere
    if (window.Laravel && window.Laravel.csrfToken) {
        return window.Laravel.csrfToken;
    }
    
    console.error('CSRF token not found anywhere');
    return null;
}

// Make all functions global and ensure they're available
document.addEventListener('DOMContentLoaded', function() {
    // Make all functions global
    window.toggleEdit = toggleEdit;
    window.cancelEdit = cancelEdit;
    window.saveEdit = saveEdit;
    window.togglePasswordChange = togglePasswordChange;
    window.cancelPasswordChange = cancelPasswordChange;
    window.savePasswordChange = savePasswordChange;
    window.uploadProfilePicture = uploadProfilePicture;
    window.sendEmailOTP = sendEmailOTP;
    window.verifyEmailOTP = verifyEmailOTP;
    window.closeOTPModal = closeOTPModal;
    window.updateProfileField = updateProfileField;
    window.changePassword = changePassword;
    window.showNotification = showNotification;
    window.getCSRFToken = getCSRFToken;
    window.isNumberKey = isNumberKey;
    window.validatePhoneInput = validatePhoneInput;
    // Modal functions already assigned above
    
    console.log('✅ Profile functions loaded and available globally');
    console.log('✅ CSRF Token available:', getCSRFToken() ? 'Yes' : 'No');
});

// Also make them available immediately (in case DOMContentLoaded already fired)
window.toggleEdit = toggleEdit;
window.cancelEdit = cancelEdit;
window.saveEdit = saveEdit;
window.togglePasswordChange = togglePasswordChange;
window.cancelPasswordChange = cancelPasswordChange;
window.savePasswordChange = savePasswordChange;
window.uploadProfilePicture = uploadProfilePicture;
window.sendEmailOTP = sendEmailOTP;
window.verifyEmailOTP = verifyEmailOTP;
window.closeOTPModal = closeOTPModal;
window.updateProfileField = updateProfileField;
window.changePassword = changePassword;
window.showNotification = showNotification;
window.getCSRFToken = getCSRFToken;
window.isNumberKey = isNumberKey;
window.validatePhoneInput = validatePhoneInput;
// Modal functions already assigned above
</script>

<style>
/* Spinning animation for loading states */
@keyframes spin {
    from {
        transform: rotate(0deg);
    }
    to {
        transform: rotate(360deg);
    }
}

.animate-spin {
    animation: spin 1s linear infinite;
}

/* Custom Scrollbar Styling */
.custom-scrollbar::-webkit-scrollbar {
    width: 8px;
}

.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
    border-radius: 4px;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
    background: rgba(156, 163, 175, 0.3);
    border-radius: 4px;
    transition: background 0.3s ease;
}

.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: rgba(156, 163, 175, 0.5);
}

.custom-scrollbar::-webkit-scrollbar-thumb:active {
    background: rgba(156, 163, 175, 0.7);
}

/* Firefox scrollbar styling */
.custom-scrollbar {
    scrollbar-width: thin;
    scrollbar-color: rgba(156, 163, 175, 0.3) transparent;
}

/* Additional subtle styling for better integration */
.custom-scrollbar {
    scrollbar-gutter: stable;
}
</style>
<?php /**PATH C:\Users\gucor\OneDrive\Documents\Herd\Modular_System\resources\views/Admin/Top/profile.blade.php ENDPATH**/ ?>