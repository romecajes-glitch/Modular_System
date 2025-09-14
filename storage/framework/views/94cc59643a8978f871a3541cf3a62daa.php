<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Admin - Program & Schedule Settings</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        .sidebar {
            transition: all 0.3s ease;
            scrollbar-width: thin;
            scrollbar-color: rgba(255, 255, 255, 0.2) transparent;
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
            <!-- Logo (Collapse Button) -->
            <button id="toggleSidebar" class="p-3 flex items-center justify-center border-b border-blue-800 w-full hover:bg-blue-800/80 transition-colors">
                <div class="bg-blue-800 p-1 rounded-lg">
                    <img src="<?php echo e(asset('pictures/logo.png')); ?>" alt="Logo" class="w-10 h-10 object-contain">
                </div>
                <span class="logo-text ml-3 font-bold text-xl">Student Portal</span>
                <div class="ml-auto">
                    <i class="fas fa-chevron-left text-blue-300"></i>
                </div>
            </button>

            <!-- User Profile -->
            <div class="user-profile p-4 flex items-center border-b border-blue-800">
                <?php if($enrollment && $enrollment->photo): ?>
                    <img src="<?php echo e(asset('storage/' . $enrollment->photo)); ?>" alt="Profile" class="h-12 w-12 rounded-full object-cover">
                <?php elseif($student && $student->photo): ?>
                    <img src="<?php echo e(asset('storage/' . $student->photo)); ?>" alt="Profile" class="h-12 w-12 rounded-full object-cover">
                <?php else: ?>
                    <div class="h-12 w-12 rounded-full bg-gradient-to-r from-blue-600 to-blue-400 flex items-center justify-center shadow">
                        <i class="fas fa-user text-white"></i>
                    </div>
                <?php endif; ?>
                <div class="ml-3 user-details">
                    <div class="font-medium text-white"><?php echo e($student->name); ?></div>
                    <div class="text-xs text-blue-200"><?php echo e($program->name ?? 'No Program Enrolled'); ?></div>
                </div>
            </div>

            <!-- Navigation -->
            <?php echo $__env->make('Student.partials.navigation', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

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
                        Session Attendance
                    </h2>
                </div>

                <div class="flex items-center space-x-6">
                    
                    <div class="border-l h-8 border-gray-200"></div>

                    <div class="relative group">
                        <button id="adminDropdown" class="flex items-center focus:outline-none space-x-3 bg-gradient-to-r from-blue-500 to-purple-600 text-white px-4 py-2 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300">
                            <div class="h-8 w-8 rounded-full bg-white bg-opacity-20 flex items-center justify-center">
                                <?php if($enrollment && $enrollment->photo): ?>
                                    <img src="<?php echo e(asset('storage/' . $enrollment->photo)); ?>" alt="Profile" class="w-8 h-8 rounded-full object-cover">
                                <?php elseif($student && $student->photo): ?>
                                    <img src="<?php echo e(asset('storage/' . $student->photo)); ?>" alt="Profile" class="w-8 h-8 rounded-full object-cover">
                                <?php else: ?>
                                    <i class="fas fa-user text-white text-sm"></i>
                                <?php endif; ?>
                            </div>
                            <div class="hidden md:block text-left">
                                <p class="text-sm font-medium"><?php echo e(auth()->user()->name); ?></p>
                                <p class="text-xs opacity-75">Student</p>
                            </div>
                            <i class="fas fa-chevron-down text-xs opacity-75 hidden md:block transition-transform group-hover:rotate-180"></i>
                        </button>
                        <div id="dropdownMenu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-xl py-2 z-50 border border-gray-100">
                            <a href="#" onclick="openProfileModal(); return false;" class="block px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                                <i class="fas fa-user mr-3"></i>Profile
                            </a>
                            <form action="<?php echo e(route('logout')); ?>" method="POST" style="display: inline;">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="block px-4 py-3 text-sm text-gray-700 hover:bg-red-50 hover:text-red-600 transition-colors border-t border-gray-100 w-full text-left">
                                    <i class="fas fa-sign-out-alt mr-3"></i>Log Out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Attendance Content -->
            <div class="p-6">
                <!-- Registration Fee Payment Modal -->
                <?php
                    $registrationFeePaid = app('App\Http\Controllers\StudentController')->checkRegistrationFeeStatus(auth()->user());
                    $registrationFeeAmount = $enrollment && $enrollment->program ? $enrollment->program->registration_fee : 0;
                    $enrollmentApproved = $enrollment && $enrollment->status === \App\Models\Enrollment::STATUS_APPROVED;
                ?>
                <?php if(!$registrationFeePaid && $registrationFeeAmount > 0 && $enrollmentApproved): ?>
                    <div id="registrationFeeModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                        <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
                            <div class="bg-red-600 text-white p-4 rounded-t-lg">
                                <div class="flex items-center">
                                    <i class="fas fa-exclamation-triangle text-2xl mr-3"></i>
                                    <h3 class="text-lg font-bold">Registration Fee Required</h3>
                                </div>
                            </div>
                            <div class="p-6">
                                <div class="text-center mb-6">
                                    <div class="bg-red-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                                        <i class="fas fa-lock text-red-600 text-2xl"></i>
                                    </div>
                                    <h4 class="text-xl font-semibold text-gray-800 mb-2">Access Blocked</h4>
                                    <p class="text-gray-600 mb-4">
                                        You must pay the registration fee of <span class="font-bold text-red-600">₱<?php echo e(number_format($registrationFeeAmount, 2)); ?></span>
                                        before you can access any features of the student portal.
                                    </p>
                                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
                                        <p class="text-sm text-yellow-800">
                                            <i class="fas fa-info-circle mr-2"></i>
                                            This is a mandatory requirement and cannot be bypassed.
                                            Please complete the payment to continue.
                                        </p>
                                    </div>
                                </div>
                                <div class="space-y-3">
                                    <button onclick="payRegistrationFee()" class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-4 rounded-lg transition duration-200 flex items-center justify-center">
                                        <i class="fas fa-credit-card mr-2"></i>
                                        Pay Registration Fee (₱<?php echo e(number_format($registrationFeeAmount, 2)); ?>)
                                    </button>
                                    <button onclick="contactAdmin()" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-lg transition duration-200 flex items-center justify-center">
                                        <i class="fas fa-envelope mr-2"></i>
                                        Contact Administrator
                                    </button>
                                </div>
                                <div class="mt-4 text-center">
                                    <p class="text-xs text-gray-500">
                                        After payment, you will have full access to attendance records, payment history, certificates, and all other features.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4"><?php echo e($program->name ?? 'No Program'); ?></h3>
                        <?php
                            $isDisabled = in_array($enrollment->status, ['pending', 'rejected']);
                        ?>
                        <button onclick="openPaymentModal()" 
                                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center justify-center gap-2 transition-colors <?php echo e($isDisabled ? 'opacity-50 cursor-not-allowed' : ''); ?>"
                                <?php echo e($isDisabled ? 'disabled' : ''); ?>>
                            <i class="fas fa-credit-card"></i>
                            <span>Pay for upcoming Session</span>
                        </button>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Session</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">OR Number</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reference Number</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount Paid</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Session Time</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Instructor</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200" id="attendanceTableBody">
                                <?php
                                    $paidSessions = $paidSessions ?? 0;
                                    $totalSessions = $attendance['total_sessions'] ?? 20;
                                    $allSessionsCompleted = $paidSessions >= $totalSessions;
                                ?>

                                <?php if($paidSessions > 0): ?>
                                    <?php for($i = 1; $i <= $paidSessions; $i++): ?>
                                        <?php
                                            // Find attendance record for this session
                                            $attendanceRecord = $attendanceRecords->where('session_number', $i)->first();
                                            
                                            if ($attendanceRecord) {
                                                $date = $attendanceRecord['date'];
                                                $orNumber = $attendanceRecord['or_number'];
                                                $referenceNumber = $attendanceRecord['reference_number'];
                                                $sessionTime = $attendanceRecord['session_time'];
                                                $instructor = $attendanceRecord['instructor'];
                                                
                                                // Get amount paid for this session
                                                $amountPaid = $attendanceRecord['amount_paid'] ?? null;
                                            } else {
                                                $date = '-';
                                                $sessionTime = '-';
                                                $instructor = '-';
                                                
                                                // Get payment record for this session
                                                $payment = $enrollment->user->payments()
                                                    ->where('status', 'completed')
                                                    ->where('session_count', '>=', $i)
                                                    ->orderBy('payment_date', 'desc')
                                                    ->first();
                                                
                                                // Determine payment type and show appropriate number
                                                if ($payment && $payment->transaction_id) {
                                                    // Online payment: Show reference number, OR number empty
                                                    $referenceNumber = $payment->transaction_id;
                                                    $orNumber = '-';
                                                    
                                                    // Calculate amount paid for this session
                                                    $program = $enrollment->program;
                                                    $amountPaid = $program ? $program->price_per_session : 500;
                                                } else {
                                                    // Onsite payment: Show OR number, reference number empty
                                                    $referenceNumber = '-';
                                                    $orNumber = $enrollment->or_number ?? '-';
                                                    
                                                    // Check if there's a saved amount_paid in attendance record
                                                    $existingAttendance = \App\Models\Attendance::where('enrollment_id', $enrollment->id)
                                                        ->where('session_number', $i)
                                                        ->first();
                                                    
                                                    if ($existingAttendance && $existingAttendance->amount_paid !== null) {
                                                        $amountPaid = $existingAttendance->amount_paid;
                                                    } else {
                                                        $amountPaid = null;
                                                    }
                                                }
                                            }
                                        ?>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Session <?php echo e($i); ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo e($date); ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo e($orNumber); ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo e($referenceNumber); ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?php if($amountPaid): ?>
                                                <span class="font-medium text-blue-600">₱<?php echo e(number_format($amountPaid, 2)); ?></span>
                                            <?php else: ?>
                                                <span class="text-gray-400">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo e($sessionTime); ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo e($instructor); ?></td>
                                    </tr>
                                    <?php endfor; ?>

                                    <?php if($paidSessions < $totalSessions): ?>
                                        <?php for($i = $paidSessions + 1; $i <= $totalSessions; $i++): ?>
                                        <tr class="bg-gray-50 opacity-50">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-400">Session <?php echo e($i); ?></td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">Payment Required</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">Payment Required</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">Payment Required</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">Payment Required</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">Payment Required</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">Payment Required</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">Payment Required</td>
                                        </tr>
                                        <?php endfor; ?>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">
                                            No sessions available. Please complete your payment to access sessions.
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <script>
                        function updateAttendance(paidSessions, totalSessions = 20) {
                            const attendanceTableBody = document.getElementById('attendanceTableBody');
                            attendanceTableBody.innerHTML = ''; // Clear existing rows

                            // Display paid sessions
                            for (let i = 1; i <= paidSessions; i++) {
                                const row = `
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Session ${i}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Not marked</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">-</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Payment Reference</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <span class="font-medium text-blue-600">₱500.00</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Session Time</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo e($instructors->implode(', ') ?: 'N/A'); ?></td>
                                    </tr>
                                `;
                                attendanceTableBody.insertAdjacentHTML('beforeend', row);
                            }

                            // Display unpaid sessions (if any)
                            if (paidSessions < totalSessions) {
                                for (let i = paidSessions + 1; i <= totalSessions; i++) {
                                    const row = `
                                        <tr class="bg-gray-50 opacity-50">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-400">Session ${i}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">Payment Required</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">Payment Required</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">Payment Required</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">Payment Required</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">Payment Required</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">Payment Required</td>
                                        </tr>
                                    `;
                                    attendanceTableBody.insertAdjacentHTML('beforeend', row);
                                }
                            }
                        }

                        // Function to fetch updated attendance data via AJAX
                        function fetchUpdatedAttendance() {
                            fetch('<?php echo e(route("student.attendance.ajax")); ?>', {
                                method: 'GET',
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    updateAttendanceWithRecords(data.attendance_records, data.paid_sessions, data.total_sessions);
                                }
                            })
                            .catch(error => {
                                console.error('Error fetching attendance data:', error);
                            });
                        }

                        // Function to update attendance table with actual records
                        function updateAttendanceWithRecords(attendanceRecords, paidSessions, totalSessions = 20) {
                            const attendanceTableBody = document.getElementById('attendanceTableBody');
                            attendanceTableBody.innerHTML = ''; // Clear existing rows

                            // Display all paid sessions, with attendance data if available
                            for (let i = 1; i <= paidSessions; i++) {
                                // Find attendance record for this session
                                const attendanceRecord = attendanceRecords.find(record => record.session_number === i);
                                
                                let date, orNumber, referenceNumber, amountPaid, sessionTime, instructor;
                                
                                if (attendanceRecord) {
                                    date = attendanceRecord.date;
                                    orNumber = attendanceRecord.or_number;
                                    referenceNumber = attendanceRecord.reference_number;
                                    amountPaid = attendanceRecord.amount_paid;
                                    sessionTime = attendanceRecord.session_time;
                                    instructor = attendanceRecord.instructor;
                                } else {
                                    date = '-';
                                    orNumber = '-';
                                    referenceNumber = '-';
                                    amountPaid = '₱500.00'; // Default amount for paid sessions
                                    sessionTime = '-';
                                    instructor = '-';
                                }
                                
                                const row = `
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Session ${i}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${date}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${orNumber}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${referenceNumber}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            ${amountPaid ? `<span class="font-medium text-blue-600">${amountPaid}</span>` : '<span class="text-gray-400">-</span>'}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${sessionTime}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${instructor}</td>
                                    </tr>
                                `;
                                attendanceTableBody.insertAdjacentHTML('beforeend', row);
                            }

                            // Display unpaid sessions (if any)
                            if (paidSessions < totalSessions) {
                                for (let i = paidSessions + 1; i <= totalSessions; i++) {
                                    const row = `
                                        <tr class="bg-gray-50 opacity-50">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-400">Session ${i}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">Payment Required</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">Payment Required</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">Payment Required</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">Payment Required</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">Payment Required</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">Payment Required</td>
                                        </tr>
                                    `;
                                    attendanceTableBody.insertAdjacentHTML('beforeend', row);
                                }
                            }
                        }
                    </script>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Payment Modal -->
    <div id="paymentModal" class="fixed inset-0 bg-black bg-opacity-60 backdrop-blur-sm flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4 transform transition-all duration-300 scale-100">
            <!-- Header -->
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white p-6 rounded-t-2xl relative overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-r from-blue-400 to-blue-500 opacity-50"></div>
                <div class="relative flex items-center justify-center">
                    <div class="bg-white bg-opacity-20 p-3 rounded-full mr-4">
                        <i class="fas fa-credit-card text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold">Pay for Sessions</h3>
                </div>
            </div>
            
            <!-- Content -->
            <div class="p-6">
                <!-- Program Info -->
                <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl p-4 mb-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h4 class="font-semibold text-gray-800"><?php echo e($program->name ?? 'No Program'); ?></h4>
                            <p class="text-sm text-gray-600">Price per Session</p>
                        </div>
                        <div class="text-right">
                            <div class="text-2xl font-bold text-blue-600">₱<?php echo e(number_format($program->price_per_session ?? 500, 2)); ?></div>
                        </div>
                    </div>
                </div>
                
                <!-- Session Selection -->
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-3">Select Number of Sessions to Pay:</label>
                    <div class="relative">
                        <select id="sessionCount" 
                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 font-medium <?php echo e($allSessionsCompleted ? 'bg-gray-100 text-gray-500 cursor-not-allowed' : 'bg-white text-gray-700'); ?>"
                                <?php echo e($allSessionsCompleted ? 'disabled' : ''); ?>>
                            <?php
                                $totalSessions = $attendance['total_sessions'] ?? 20;
                                $nextSessionToPay = $paidSessions + 1;
                            ?>

                            <?php if($paidSessions < $totalSessions): ?>
                                <?php for($i = 1; $i <= ($totalSessions - $paidSessions); $i++): ?>
                                    <option value="<?php echo e($i); ?>"><?php echo e($i); ?> Session<?php echo e($i > 1 ? 's' : ''); ?> (Session<?php echo e($i == 1 ? '' : 's'); ?> <?php echo e($nextSessionToPay); ?><?php echo e($i > 1 ? '-' . ($nextSessionToPay + $i - 1) : ''); ?>)</option>
                                <?php endfor; ?>
                            <?php else: ?>
                                <option value="" disabled selected>All sessions have been paid for</option>
                            <?php endif; ?>
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <i class="fas <?php echo e($allSessionsCompleted ? 'fa-check-circle text-green-500' : 'fa-chevron-down text-gray-400'); ?>"></i>
                        </div>
                    </div>
                    <?php if($allSessionsCompleted): ?>
                        <div class="mt-3 p-3 bg-green-50 border border-green-200 rounded-lg">
                            <div class="flex items-center">
                                <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                <p class="text-sm text-green-700 font-medium">Congratulations! You have completed all sessions for this program.</p>
                            </div>
                        </div>
                    <?php else: ?>
                        <p class="text-xs text-gray-500 mt-2">
                            <i class="fas fa-info-circle mr-1"></i>
                            Sessions must be paid sequentially (no skipping)
                        </p>
                    <?php endif; ?>
                </div>
                
                <!-- Total Amount -->
                <div class="bg-gradient-to-r <?php echo e($allSessionsCompleted ? 'from-gray-50 to-gray-100 border-2 border-gray-200' : 'from-green-50 to-green-100 border-2 border-green-200'); ?> rounded-xl p-4 mb-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="<?php echo e($allSessionsCompleted ? 'bg-gray-500' : 'bg-green-500'); ?> p-2 rounded-full mr-3">
                                <i class="fas <?php echo e($allSessionsCompleted ? 'fa-check-circle' : 'fa-calculator'); ?> text-white"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium <?php echo e($allSessionsCompleted ? 'text-gray-800' : 'text-green-800'); ?>">
                                    <?php echo e($allSessionsCompleted ? 'Program Completed' : 'Total Amount'); ?>

                                </p>
                                <p class="text-xs <?php echo e($allSessionsCompleted ? 'text-gray-600' : 'text-green-600'); ?>">
                                    <?php echo e($allSessionsCompleted ? 'All sessions have been paid for' : 'Including all selected sessions'); ?>

                                </p>
                            </div>
                        </div>
                        <div class="text-right">
                            <?php if($allSessionsCompleted): ?>
                                <div class="text-2xl font-bold text-gray-600">Complete</div>
                            <?php else: ?>
                                <div id="totalAmount" class="text-2xl font-bold text-green-600">₱<?php echo e(number_format($program->price_per_session ?? 500, 2)); ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <!-- Payment Form -->
                <form id="paymentForm" action="<?php echo e(route('payment.checkout')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="amount" id="paymentAmount" value="<?php echo e($program->price_per_session ?? '500'); ?>">
                    <input type="hidden" name="email" value="<?php echo e($student->email); ?>">
                    <input type="hidden" name="session_count" id="sessionCountValue" value="1">
                    <input type="hidden" name="session_numbers" id="sessionNumbers" value="">
                    <input type="hidden" name="payment_type" value="session">
                    
                    <!-- Payment Button -->
                    <button type="button" 
                            onclick="submitPayment()" 
                            id="paymentButton"
                            class="w-full font-semibold py-4 px-6 rounded-xl transition-all duration-300 flex items-center justify-center shadow-lg mb-3 <?php echo e($allSessionsCompleted ? 'bg-gray-400 text-gray-600 cursor-not-allowed opacity-60' : 'bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white hover:shadow-xl transform hover:-translate-y-1'); ?>"
                            <?php echo e($allSessionsCompleted ? 'disabled' : ''); ?>>
                        <i class="fas <?php echo e($allSessionsCompleted ? 'fa-check-circle' : 'fa-wallet'); ?> mr-3 text-lg"></i>
                        <span class="text-lg">
                            <?php echo e($allSessionsCompleted ? 'All Sessions Completed' : 'Proceed to Payment via GCash'); ?>

                        </span>
                    </button>
                </form>
                
                <!-- Cancel Button -->
                <button onclick="closePaymentModal()" class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-3 px-6 rounded-xl transition-all duration-200 flex items-center justify-center">
                    <i class="fas fa-times mr-2"></i>
                    Cancel
                </button>
                
                <!-- Security Notice -->
                <div class="mt-4 text-center">
                    <p class="text-xs text-gray-500">
                        <i class="fas fa-shield-alt mr-1"></i>
                        Secure payment processing via GCash
                    </p>
                </div>
            </div>
        </div>
    </div>
    <?php $__env->startPush('scripts'); ?>
    <script>
        // Payment Modal Functions
        function openPaymentModal() {
            document.getElementById('paymentModal').classList.remove('hidden');
            updateTotalAmount();
        }

        function closePaymentModal() {
            document.getElementById('paymentModal').classList.add('hidden');
        }

        function updateTotalAmount() {
            const sessionCount = parseInt(document.getElementById('sessionCount').value) || 1;
            const pricePerSession = <?php echo e($program->price_per_session ?? 500); ?>;
            const paidSessions = <?php echo e($paidSessions ?? 0); ?>;
            
            // Calculate amount for the selected sessions
            const totalAmount = sessionCount * pricePerSession;
            
            // Generate session numbers (sequential from next unpaid session)
            const sessionNumbers = [];
            for (let i = 1; i <= sessionCount; i++) {
                sessionNumbers.push(paidSessions + i);
            }
            
            document.getElementById('totalAmount').textContent = '₱' + totalAmount.toLocaleString('en-US', {minimumFractionDigits: 2});
            document.getElementById('paymentAmount').value = totalAmount;
            document.getElementById('sessionCountValue').value = sessionCount;
            document.getElementById('sessionNumbers').value = sessionNumbers.join(',');
        }

        function submitPayment() {
            // Check if all sessions are completed
            const paymentButton = document.getElementById('paymentButton');
            if (paymentButton.disabled) {
                alert('All sessions have been completed. No additional payment is required.');
                return;
            }

            const formData = new FormData(document.getElementById('paymentForm'));
            fetch('<?php echo e(route("payment.direct")); ?>', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    // Fetch updated attendance data via AJAX
                    fetchUpdatedAttendance();
                    // Close the modal after successful payment
                    closePaymentModal();
                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Payment failed. Please try again or contact support.');
            });
        }

        // Initialize payment modal functionality
        document.addEventListener('DOMContentLoaded', function() {
            // Add event listener to session count dropdown
            const sessionCountSelect = document.getElementById('sessionCount');
            if (sessionCountSelect) {
                sessionCountSelect.addEventListener('change', updateTotalAmount);
            }

            // Close modal when clicking outside
            document.getElementById('paymentModal').addEventListener('click', function(e) {
                if (e.target.id === 'paymentModal') {
                    closePaymentModal();
                }
            });
        });

        // student dropdown toggle
        document.getElementById('adminDropdown').addEventListener('click', function(e) {
            e.stopPropagation();
            document.getElementById('dropdownMenu').classList.toggle('hidden');
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function() {
            document.getElementById('dropdownMenu').classList.add('hidden');
        });
        // Toggle sidebar collapse
        document.getElementById('toggleSidebar').addEventListener('click', function () {
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
                        window.location.href = url;
                    }
                }
            });
        });

        // Function to set active nav item based on current URL
        function setActiveNavItem() {
            const currentPath = window.location.pathname;
            navItems.forEach(item => {
                const url = item.getAttribute('data-url');
                if (url && currentPath === url) {
                    // Remove active class from all items
                    navItems.forEach(nav => nav.classList.remove('active-nav'));
                    // Add active class to current page item
                    item.classList.add('active-nav');
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
        const logoutButton = document.getElementById('logoutButton');
        if (logoutButton) {
            logoutButton.addEventListener('click', function(e) {
                e.preventDefault();
                document.getElementById('logout-form').submit();
            });
        }

        window.addEventListener('DOMContentLoaded', () => {
            const sidebar = document.querySelector('.sidebar');
            const contentArea = document.querySelector('.content-area');
            const toggleIcon = document.querySelector('#toggleSidebar i');

            if (localStorage.getItem('sidebar-collapsed') === 'true') {
                sidebar.classList.add('sidebar-collapsed');
                contentArea.classList.remove('ml-1');
                contentArea.classList.add('ml-1');

                // Set the correct icon direction
                if (toggleIcon.classList.contains('fa-chevron-left')) {
                    toggleIcon.classList.remove('fa-chevron-left');
                    toggleIcon.classList.add('fa-chevron-right');
                }
            }

            // Show registration fee modal if it exists
            const modal = document.getElementById('registrationFeeModal');
            if (modal) {
                modal.style.display = 'flex';
            }
        });

        // Registration fee payment functions
        function payRegistrationFee() {
            // Redirect to payment page with registration fee flag
            window.location.href = '<?php echo e(route("student.payment")); ?>?type=registration_fee';
        }

        function contactAdmin() {
            // Open email client or redirect to contact page
            const subject = encodeURIComponent('Registration Fee Payment Inquiry');
            const body = encodeURIComponent('Hello,\n\nI would like to inquire about the registration fee payment process.\n\nThank you.');
            window.location.href = 'mailto:admin@school.com?subject=' + subject + '&body=' + body;
        }
    </script>
    <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" class="hidden">
        <?php echo csrf_field(); ?>
    </form>
    
    <?php echo $__env->make('Student.Top.profile', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</body>
</html>
<?php /**PATH C:\Users\gucor\OneDrive\Documents\Herd\Modular_System\resources\views/Student/attendance.blade.php ENDPATH**/ ?>