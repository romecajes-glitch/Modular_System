<!-- Profile Modal Content -->
<div id="profileModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-xl shadow-lg overflow-hidden profile-tab max-w-4xl w-full mx-4 max-h-[90vh] overflow-y-auto">
        <!-- Hidden file input for profile picture -->
        <input type="file" id="profile-pic-upload" accept="image/*" class="hidden">
        <div class="bg-gradient-to-r from-blue-500 to-indigo-600 p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold">Profile Settings</h2>
                </div>
                <button onclick="closeProfileModal()" class="text-white hover:text-gray-200 transition-colors">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>
        </div>
        <!-- Profile Content -->
        <div class="p-6">
            <!-- Student Information -->
            <div id="about" class="tab-content active">
                <div class="mb-6">
                    
                    <h3 class="text-lg font-semibold text-gray-600 mb-4">Personal Details</h3>
                    
                    <div class="bg-gray-50 rounded-lg p-6">
                        <div class="flex items-start space-x-4">
                            <!-- Student Photo -->
                            <div class="flex-shrink-0">
                                <?php if($enrollment && $enrollment->photo): ?>
                                    <img src="<?php echo e(asset('storage/' . $enrollment->photo)); ?>" alt="Profile" class="w-20 h-20 rounded-full object-cover border-4 border-white shadow-md">
                                <?php elseif($student && $student->photo): ?>
                                    <img src="<?php echo e(asset('storage/' . $student->photo)); ?>" alt="Profile" class="w-20 h-20 rounded-full object-cover border-4 border-white shadow-md">
                                <?php else: ?>
                                    <div class="w-20 h-20 rounded-full border-4 border-white shadow-md bg-gradient-to-r from-blue-600 to-blue-400 flex items-center justify-center">
                                        <i class="fas fa-user text-white text-2xl"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <!-- Student Details -->
                            <div class="flex-1">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Name:</label>
                                        <p class="text-lg font-semibold text-gray-900">
                                            <?php if($enrollment): ?>
                                                <?php echo e($enrollment->first_name ?? ''); ?> <?php echo e($enrollment->middle_name ?? ''); ?> <?php echo e($enrollment->last_name ?? ''); ?> <?php echo e($enrollment->suffix_name ?? ''); ?>

                                            <?php else: ?>
                                                <?php echo e($student->name ?? 'Student'); ?>

                                            <?php endif; ?>
                                        </p>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Email:</label>
                                        <div class="flex items-center space-x-2">
                                            <input type="email" id="emailInput" value="<?php echo e($student->email ?? 'N/A'); ?>" class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" readonly>
                                            <button id="editEmailBtn" onclick="toggleEmailEdit()" class="px-3 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        </div>
                                        <div id="emailEditActions" class="hidden mt-2 space-x-2">
                                            <button id="saveEmailBtn" onclick="saveEmail()" class="px-3 py-1 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors text-sm">
                                                <i class="fas fa-check mr-1"></i>Save
                                            </button>
                                            <button id="cancelEmailBtn" onclick="cancelEmailEdit()" class="px-3 py-1 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-colors text-sm">
                                                <i class="fas fa-times mr-1"></i>Cancel
                                            </button>
                                        </div>
                                        <div id="otpSection" class="hidden mt-2">
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Enter OTP sent to new email:</label>
                                            <div class="flex items-center space-x-2">
                                                <input type="text" id="otpInput" placeholder="Enter 6-digit OTP" class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" maxlength="6">
                                                <button id="verifyOtpBtn" onclick="verifyOtp()" class="px-3 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                                                    <i class="fas fa-check mr-1"></i>Verify
                                                </button>
                                            </div>
                                            <p class="text-xs text-gray-500 mt-1">Check your new email for the verification code</p>
                                        </div>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Phone:</label>
                                        <p class="text-gray-900"><?php echo e($enrollment->phone ?? ($student->phone ?? 'N/A')); ?></p>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">BirthDate:</label>
                                        <p class="text-gray-900"><?php echo e($enrollment->birthdate ? \Carbon\Carbon::parse($enrollment->birthdate)->format('M d, Y') : 'N/A'); ?></p>
                                    </div>
                                </div>
                                
                                <!-- View More Button -->
                                <div class="mt-4">
                                    <button onclick="openCompleteRecordsModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center">
                                        <i class="fas fa-eye mr-2"></i>
                                        View More
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <h2 class="text-xl font-semibold mb-4">Program Information</h2>
                        <ul class="space-y-3">
                            <li class="flex items-center">
                                <i class="fas fa-graduation-cap text-blue-500 mr-3 w-5"></i>
                                <span class="text-gray-600">Program: <span class="font-medium"><?php echo e($enrollment->program->name ?? 'No Program'); ?></span></span>
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-calendar-alt text-blue-500 mr-3 w-5"></i>
                                <span class="text-gray-600">Enrolled: <span class="font-medium"><?php echo e($enrollment->created_at->format('M d, Y') ?? 'N/A'); ?></span></span>
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-info-circle text-blue-500 mr-3 w-5"></i>
                                <span class="text-gray-600">Status: <span class="font-medium"><?php echo e($enrollment->status ?? 'N/A'); ?></span></span>
                            </li>
                        </ul>
                    </div>

                    <div>
                        <h2 class="text-xl font-semibold mb-4">Quick Stats</h2>
                        <ul class="space-y-3">
                            <li class="flex items-center">
                                <i class="fas fa-calendar-check text-blue-500 mr-3 w-5"></i>
                                <span class="text-gray-600">Attendance: <span class="font-medium"><?php echo e($enrollment->attendance_percentage ?? 0); ?>%</span></span>
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-money-bill-wave text-blue-500 mr-3 w-5"></i>
                                <span class="text-gray-600">Payment Status: <span class="font-medium"><?php echo e($enrollment->payment_status ?? 'Pending'); ?></span></span>
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-certificate text-blue-500 mr-3 w-5"></i>
                                <span class="text-gray-600">Certificate: <span class="font-medium"><?php echo e($enrollment->isEligibleForCertificate() ? 'Eligible' : 'Not Eligible'); ?></span></span>
                            </li>
                        </ul>
                    </div>
                </div>
                
                <!-- Security Section -->
                <div class="mt-8">
                    <h2 class="text-xl font-semibold mb-4 text-gray-800 border-b border-gray-200 pb-2">Security Settings</h2>
                    <div class="bg-gray-50 rounded-lg p-6">
                        <div class="space-y-4">
                            <div>
                                <h3 class="text-lg font-medium text-gray-700 mb-3">Change Password</h3>
                                <form id="changePasswordForm" class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Current Password:</label>
                                        <input type="password" id="currentPassword" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">New Password:</label>
                                        <input type="password" id="newPassword" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password:</label>
                                        <input type="password" id="confirmPassword" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                                    </div>
                                    <div class="flex space-x-3">
                                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md transition-colors">
                                            <i class="fas fa-lock mr-2"></i>Change Password
                                        </button>
                                        <button type="button" onclick="clearPasswordForm()" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md transition-colors">
                                            <i class="fas fa-times mr-2"></i>Clear
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Complete Records Modal -->
<div id="completeRecordsModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-xl shadow-lg overflow-hidden max-w-4xl w-full mx-4 max-h-[90vh] overflow-y-auto">
        <!-- Modal Header -->
        <div class="bg-gradient-to-r from-blue-500 to-indigo-600 p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold">Student Complete Records</h2>
                    <p class="text-blue-100">All enrollment information and personal details</p>
                </div>
                <button onclick="closeCompleteRecordsModal()" class="text-white hover:text-gray-200 transition-colors">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>
        </div>

        <!-- Modal Content -->
        <div class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Personal Information -->
                <div>
                    <h3 class="text-xl font-semibold mb-4 text-gray-800 border-b border-gray-200 pb-2">Personal Information</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Full Name:</label>
                            <p class="text-gray-900 font-medium">
                                <?php echo e($enrollment->first_name ?? ''); ?> <?php echo e($enrollment->middle_name ?? ''); ?> <?php echo e($enrollment->last_name ?? ''); ?> <?php echo e($enrollment->suffix_name ?? ''); ?>

                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email:</label>
                            <p class="text-gray-900"><?php echo e($student->email ?? 'N/A'); ?></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Phone:</label>
                            <p class="text-gray-900"><?php echo e($enrollment->phone ?? 'N/A'); ?></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Birthdate:</label>
                            <p class="text-gray-900"><?php echo e($enrollment->birthdate ? \Carbon\Carbon::parse($enrollment->birthdate)->format('M d, Y') : 'N/A'); ?></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Age:</label>
                            <p class="text-gray-900"><?php echo e($enrollment->age ?? 'N/A'); ?></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Gender:</label>
                            <p class="text-gray-900"><?php echo e($enrollment->gender ?? 'N/A'); ?></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Civil Status:</label>
                            <p class="text-gray-900"><?php echo e($enrollment->civil_status ?? 'N/A'); ?></p>
                        </div>
                        <?php if($enrollment->civil_status == 'Married'): ?>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Spouse Name:</label>
                            <p class="text-gray-900"><?php echo e($enrollment->spouse_name ?? 'N/A'); ?></p>
                        </div>
                        <?php endif; ?>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Citizenship:</label>
                            <p class="text-gray-900"><?php echo e($enrollment->citizenship ?? 'N/A'); ?></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Religion:</label>
                            <p class="text-gray-900"><?php echo e($enrollment->religion ?? 'N/A'); ?></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Place of Birth:</label>
                            <p class="text-gray-900"><?php echo e($enrollment->place_of_birth ?? 'N/A'); ?></p>
                        </div>
                    </div>
                </div>

                <!-- Family Information -->
                <div>
                    <h3 class="text-xl font-semibold mb-4 text-gray-800 border-b border-gray-200 pb-2">Family Information</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Father's Name:</label>
                            <p class="text-gray-900"><?php echo e($enrollment->father_name ?? 'N/A'); ?></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Mother's Name:</label>
                            <p class="text-gray-900"><?php echo e($enrollment->mother_name ?? 'N/A'); ?></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Guardian:</label>
                            <p class="text-gray-900"><?php echo e($enrollment->guardian ?? 'N/A'); ?></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Guardian Contact:</label>
                            <p class="text-gray-900"><?php echo e($enrollment->guardian_contact ?? 'N/A'); ?></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Home Address:</label>
                            <p class="text-gray-900"><?php echo e($enrollment->address ?? 'N/A'); ?></p>
                        </div>
                    </div>
                </div>

                <!-- Program Information -->
                <div>
                    <h3 class="text-xl font-semibold mb-4 text-gray-800 border-b border-gray-200 pb-2">Program Information</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Program:</label>
                            <p class="text-gray-900"><?php echo e($enrollment->program->name ?? 'N/A'); ?></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Enrollment Date:</label>
                            <p class="text-gray-900"><?php echo e($enrollment->created_at->format('M d, Y') ?? 'N/A'); ?></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status:</label>
                            <p class="text-gray-900"><?php echo e($enrollment->status ?? 'N/A'); ?></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Registration Fee:</label>
                            <p class="text-gray-900">₱<?php echo e(number_format($enrollment->program->registration_fee ?? 0, 2)); ?></p>
                        </div>
                    </div>
                </div>

                <!-- Academic Information -->
                <div>
                    <h3 class="text-xl font-semibold mb-4 text-gray-800 border-b border-gray-200 pb-2">Academic Information</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Attendance Percentage:</label>
                            <p class="text-gray-900"><?php echo e($enrollment->attendance_percentage ?? 0); ?>%</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Payment Status:</label>
                            <p class="text-gray-900"><?php echo e($enrollment->payment_status ?? 'Pending'); ?></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Certificate Eligibility:</label>
                            <p class="text-gray-900"><?php echo e($enrollment->isEligibleForCertificate() ? 'Eligible' : 'Not Eligible'); ?></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Last Updated:</label>
                            <p class="text-gray-900"><?php echo e($enrollment->updated_at->format('M d, Y H:i') ?? 'N/A'); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function openProfileModal() {
    document.getElementById('profileModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeProfileModal() {
    document.getElementById('profileModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

function openCompleteRecordsModal() {
    document.getElementById('completeRecordsModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeCompleteRecordsModal() {
    document.getElementById('completeRecordsModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// Close modal when clicking outside
document.getElementById('profileModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeProfileModal();
    }
});

// Close complete records modal when clicking outside
document.getElementById('completeRecordsModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeCompleteRecordsModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeProfileModal();
        closeCompleteRecordsModal();
    }
});

document.addEventListener('DOMContentLoaded', function() {
    const profileModal = document.getElementById('profileModal');
    if (!profileModal) return;
    
    const uploadBtn = profileModal.querySelector('.relative.group button');
    const profileContainer = profileModal.querySelector('.relative.group');
    const fileInput = document.getElementById('profile-pic-upload');
    
    // Make profile photo clickable - only within the modal
    const profileImg = profileModal.querySelector('.relative.group img');
    const profilePlaceholder = profileModal.querySelector('.relative.group > div:not(.absolute)');
    
    if (profileImg) {
        profileImg.addEventListener('click', function(e) {
            console.log('Profile image clicked in modal');
            fileInput.click();
        });
    }
    
    if (profilePlaceholder) {
        profilePlaceholder.addEventListener('click', function(e) {
            console.log('Profile placeholder clicked in modal');
            fileInput.click();
        });
    }
    
    // Also keep container click for fallback - only within modal
    if (profileContainer) {
        profileContainer.addEventListener('click', function(e) {
            console.log('Profile container clicked in modal', e.target);
            if (e.target.closest('button')) {
                console.log('Button clicked, ignoring');
                return; // Don't trigger if clicking the camera button
            }
            console.log('Triggering file input click from modal');
            fileInput.click();
        });
    }
    
    if (uploadBtn) {
        uploadBtn.addEventListener('click', function(e) {
            e.preventDefault();
            fileInput.click();
        });
    }
    
    fileInput.addEventListener('change', function(e) {
        if (e.target.files && e.target.files[0]) {
            const reader = new FileReader();
            const profileImg = profileModal.querySelector('.relative.group img');
            
            reader.onload = function(event) {
                if (profileImg) {
                    profileImg.src = event.target.result;
                }
                
                // Upload the file to server
                const formData = new FormData();
                formData.append('profile_photo', e.target.files[0]);
                formData.append('_token', '<?php echo e(csrf_token()); ?>');
                
                fetch('<?php echo e(route("student.profile.photo.update")); ?>', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show success message
                        showNotification('Profile photo updated successfully!', 'success');
                    } else {
                        // Show error message
                        showNotification(data.message || 'Failed to update profile photo', 'error');
                        // Revert to original image if upload failed
                        if (profileImg) {
                            profileImg.src = '<?php echo e($student->photo ? asset("storage/" . $student->photo) : ""); ?>';
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('An error occurred while uploading the photo', 'error');
                    // Revert to original image
                    if (profileImg) {
                        profileImg.src = '<?php echo e($student->photo ? asset("storage/" . $student->photo) : ""); ?>';
                    }
                });
            };
            
            reader.readAsDataURL(e.target.files[0]);
        }
    });
});

function showNotification(message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 transition-all duration-300 ${
        type === 'success' ? 'bg-green-500 text-white' :
        type === 'error' ? 'bg-red-500 text-white' :
        'bg-blue-500 text-white'
    }`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    // Remove notification after 3 seconds
    setTimeout(() => {
        notification.remove();
    }, 3000);
}

// Email editing functions
let originalEmail = '';
let pendingEmail = '';

function toggleEmailEdit() {
    const emailInput = document.getElementById('emailInput');
    const editBtn = document.getElementById('editEmailBtn');
    const editActions = document.getElementById('emailEditActions');
    
    if (emailInput.readOnly) {
        originalEmail = emailInput.value;
        emailInput.readOnly = false;
        emailInput.focus();
        editBtn.classList.add('hidden');
        editActions.classList.remove('hidden');
    }
}

function cancelEmailEdit() {
    const emailInput = document.getElementById('emailInput');
    const editBtn = document.getElementById('editEmailBtn');
    const editActions = document.getElementById('emailEditActions');
    const otpSection = document.getElementById('otpSection');
    
    emailInput.value = originalEmail;
    emailInput.readOnly = true;
    editBtn.classList.remove('hidden');
    editActions.classList.add('hidden');
    otpSection.classList.add('hidden');
}

function saveEmail() {
    const emailInput = document.getElementById('emailInput');
    const newEmail = emailInput.value.trim();
    
    if (!newEmail || newEmail === originalEmail) {
        showNotification('Please enter a new email address', 'error');
        return;
    }
    
    if (!isValidEmail(newEmail)) {
        showNotification('Please enter a valid email address', 'error');
        return;
    }
    
    // Send OTP to new email
    fetch('<?php echo e(route("student.send-email-otp")); ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
        },
        body: JSON.stringify({
            new_email: newEmail
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            pendingEmail = newEmail;
            document.getElementById('emailEditActions').classList.add('hidden');
            document.getElementById('otpSection').classList.remove('hidden');
            showNotification('OTP sent to your new email address', 'success');
        } else {
            showNotification(data.message || 'Failed to send OTP', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('An error occurred while sending OTP', 'error');
    });
}

function verifyOtp() {
    const otpInput = document.getElementById('otpInput');
    const otp = otpInput.value.trim();
    
    if (!otp || otp.length !== 6) {
        showNotification('Please enter a valid 6-digit OTP', 'error');
        return;
    }
    
    fetch('<?php echo e(route("student.verify-email-otp")); ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
        },
        body: JSON.stringify({
            new_email: pendingEmail,
            otp: otp
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            originalEmail = pendingEmail;
            document.getElementById('emailInput').readOnly = true;
            document.getElementById('editEmailBtn').classList.remove('hidden');
            document.getElementById('otpSection').classList.add('hidden');
            document.getElementById('otpInput').value = '';
            showNotification('Email updated successfully!', 'success');
        } else {
            showNotification(data.message || 'Invalid OTP', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('An error occurred while verifying OTP', 'error');
    });
}

function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

// Password change functions
document.getElementById('changePasswordForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const currentPassword = document.getElementById('currentPassword').value;
    const newPassword = document.getElementById('newPassword').value;
    const confirmPassword = document.getElementById('confirmPassword').value;
    
    if (newPassword !== confirmPassword) {
        showNotification('New passwords do not match', 'error');
        return;
    }
    
    if (newPassword.length < 8) {
        showNotification('New password must be at least 8 characters long', 'error');
        return;
    }
    
    fetch('<?php echo e(route("student.change-password")); ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
        },
        body: JSON.stringify({
            current_password: currentPassword,
            new_password: newPassword,
            new_password_confirmation: confirmPassword
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Password changed successfully!', 'success');
            clearPasswordForm();
        } else {
            showNotification(data.message || 'Failed to change password', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('An error occurred while changing password', 'error');
    });
});

function clearPasswordForm() {
    document.getElementById('currentPassword').value = '';
    document.getElementById('newPassword').value = '';
    document.getElementById('confirmPassword').value = '';
}
</script>
<?php /**PATH C:\Users\gucor\OneDrive\Documents\Herd\Modular_System\resources\views/Student/Top/profile.blade.php ENDPATH**/ ?>