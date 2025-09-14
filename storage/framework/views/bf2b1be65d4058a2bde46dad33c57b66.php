<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Records</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
                    <div class="text-xs text-blue-200"><?php echo e($enrollment->program->name ?? 'No Program Enrolled'); ?></div>
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
                        Payment Records
                    </h2>
                </div>
                
                <div class="flex items-center space-x-6">
                    <div class="flex space-x-5">
                        <div class="relative group">
                            <button class="text-gray-500 hover:text-gray-700 transition-colors relative">
                                <i class="fas fa-bell text-xl"></i>
                                <span class="absolute -top-1 -right-1 h-4 w-4 rounded-full bg-red-500 text-white text-xs flex items-center justify-center">3</span>
                            </button>
                            <div class="hidden group-hover:block absolute right-0 mt-2 w-72 bg-white rounded-lg shadow-xl z-50 border border-gray-200">
                                <div class="p-3 border-b border-gray-200">
                                    <h4 class="font-medium text-gray-800">Notifications (3)</h4>
                                </div>
                                <div class="max-h-96 overflow-y-auto">
                                    <a href="#" class="block px-4 py-3 hover:bg-gray-50 border-b border-gray-100">
                                        <div class="flex items-start">
                                            <div class="bg-blue-100 p-2 rounded-full mr-3">
                                                <i class="fas fa-calendar-check text-blue-600"></i>
                                            </div>
                                            <div>
                                                <p class="font-medium text-sm">New session scheduled</p>
                                                <p class="text-xs text-gray-500 mt-1">Web Development - Tomorrow 10AM</p>
                                            </div>
                                        </div>
                                    </a>
                                    <a href="#" class="block px-4 py-3 hover:bg-gray-50 border-b border-gray-100">
                                        <div class="flex items-start">
                                            <div class="bg-green-100 p-2 rounded-full mr-3">
                                                <i class="fas fa-money-bill-wave text-green-600"></i>
                                            </div>
                                            <div>
                                                <p class="font-medium text-sm">Payment received</p>
                                                <p class="text-xs text-gray-500 mt-1">$500 for 2 Sessions</p>
                                            </div>
                                        </div>
                                    </a>
                                    <a href="#" class="block px-4 py-3 hover:bg-gray-50">
                                        <div class="flex items-start">
                                            <div class="bg-purple-100 p-2 rounded-full mr-3">
                                                <i class="fas fa-certificate text-purple-600"></i>
                                            </div>
                                            <div>
                                                <p class="font-medium text-sm">Certificate available</p>
                                                <p class="text-xs text-gray-500 mt-1">JavaScript Fundamentals</p>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="p-3 text-center border-t border-gray-200">
                                    <a href="#" class="text-sm font-medium text-blue-600 hover:text-blue-800">View All</a>
                                </div>
                            </div>
                        </div>

                        <div class="relative group">
                            <button class="text-gray-500 hover:text-gray-700 transition-colors relative">
                                <i class="fas fa-envelope text-xl"></i>
                                <span class="absolute -top-1 -right-1 h-4 w-4 rounded-full bg-blue-500 text-white text-xs flex items-center justify-center">2</span>
                            </button>
                            <div class="hidden group-hover:block absolute right-0 mt-2 w-72 bg-white rounded-lg shadow-xl z-50 border border-gray-200">
                                <div class="p-3 border-b border-gray-200">
                                    <h4 class="font-medium text-gray-800">Messages (2)</h4>
                                </div>
                                <div class="max-h-96 overflow-y-auto">
                                    <a href="#" class="block px-4 py-3 hover:bg-gray-50 border-b border-gray-100">
                                        <div class="flex items-start">
                                            <div class="bg-blue-100 p-2 rounded-full mr-3">
                                                <i class="fas fa-user-graduate text-blue-600"></i>
                                            </div>
                                            <div>
                                                <p class="font-medium text-sm">From: Advisor</p>
                                                <p class="text-xs text-gray-500 mt-1">About your course selection...</p>
                                            </div>
                                        </div>
                                    </a>
                                    <a href="#" class="block px-4 py-3 hover:bg-gray-50">
                                        <div class="flex items-start">
                                            <div class="bg-yellow-100 p-2 rounded-full mr-3">
                                                <i class="fas fa-exclamation-triangle text-yellow-600"></i>
                                            </div>
                                            <div>
                                                <p class="font-medium text-sm">System Notification</p>
                                                <p class="text-xs text-gray-500 mt-1">Upcoming maintenance...</p>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="p-3 text-center border-t border-gray-200">
                                    <a href="#" class="text-sm font-medium text-blue-600 hover:text-blue-800">View All Messages</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="border-l h-8 border-gray-200"></div>

                    <div class="relative group">
                        <button id="adminDropdown" class="flex items-center focus:outline-none space-x-2">
                            <?php if($enrollment && $enrollment->photo): ?>
                                <img src="<?php echo e(asset('storage/' . $enrollment->photo)); ?>" alt="Profile" class="h-9 w-9 rounded-full object-cover">
                            <?php elseif($student && $student->photo): ?>
                                <img src="<?php echo e(asset('storage/' . $student->photo)); ?>" alt="Profile" class="h-9 w-9 rounded-full object-cover">
                            <?php else: ?>
                                <div class="h-9 w-9 rounded-full bg-gradient-to-r from-blue-500 to-blue-600 flex items-center justify-center shadow">
                                    <i class="fas fa-user text-white"></i>
                                </div>
                            <?php endif; ?>
                            <div class="hidden md:block text-left">
                                <p class="text-sm font-medium text-gray-700"><?php echo e(ucfirst(explode(' ', auth()->user()->name)[0])); ?></p>
                            </div>
                            <i class="fas fa-chevron-down text-xs text-gray-500 hidden md:block transition-transform group-hover:rotate-180"></i>
                        </button>
                        <div id="dropdownMenu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                            <a href="#" onclick="openProfileModal(); return false;" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
                            <a href="#" id="logoutButton" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 border-t border-gray-100">Log Out</a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Payment Records Content -->
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

                <?php if(session('success')): ?>
                    <div class="p-4 mb-4 text-green-800 rounded-lg bg-green-50" role="alert">
                        <span class="font-medium">Success!</span> <?php echo e(session('success')); ?>

                    </div>
                <?php endif; ?>

                <?php
                    $paymentDataToShow = $paymentData ?? $payments ?? null;
                ?>

                <?php if($paymentDataToShow): ?>
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <div class="p-4 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-800">Payment History</h3>
                        </div>
                        <div class="divide-y divide-gray-200">
                            <?php if(isset($paymentDataToShow['payment_records']) && $paymentDataToShow['payment_records']->count() > 0): ?>
                                <?php $__currentLoopData = $paymentDataToShow['payment_records']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="p-4 hover:bg-gray-50 transition-colors">
                                        <div class="flex justify-between items-center">
                                            <div>
                                                <h4 class="font-medium text-gray-800"><?php echo e($payment->program_name ?? 'Program Payment'); ?></h4>
                                                <p class="text-sm text-gray-500 mt-1">Paid on: <?php echo e(\Carbon\Carbon::parse($payment->payment_date)->format('F j, Y')); ?></p>
                                                <?php if($payment->transaction_id): ?>
                                                    <p class="text-xs text-gray-400 mt-1">Transaction ID: <?php echo e($payment->transaction_id); ?></p>
                                                <?php endif; ?>
                                                <?php if($payment->or_number): ?>
                                                    <p class="text-xs text-gray-400 mt-1">OR Number: <?php echo e($payment->or_number); ?></p>
                                                <?php endif; ?>
                                                <p class="text-xs text-gray-400">Payment Method: <?php echo e(ucfirst($payment->payment_method)); ?></p>
                                            </div>
                                            <div class="text-right">
                                                <p class="font-semibold text-blue-600">₱<?php echo e(number_format($payment->amount, 2)); ?></p>
                                                <p class="text-sm text-gray-500">
                                                    <?php if(isset($payment->payment_type) && $payment->payment_type === 'registration'): ?>
                                                        registration
                                                    <?php else: ?>
                                                        <?php echo e($payment->session_count); ?> session<?php echo e($payment->session_count > 1 ? 's' : ''); ?>

                                                    <?php endif; ?>
                                                </p>
                                                <button class="mt-2 text-blue-600 hover:text-blue-800 text-sm font-medium" onclick="viewReceipt(<?php echo e($payment->id); ?>)">
                                                    View Receipt
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php else: ?>
                                <div class="p-6 text-center">
                                    <div class="text-gray-400 mb-4">
                                        <i class="fas fa-receipt text-4xl"></i>
                                    </div>
                                    <p class="text-gray-500">No payment records found.</p>
                                    <p class="text-sm text-gray-400 mt-1">Your payment history will appear here once you make a payment.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Receipt Modal -->
    <div id="receiptModal" class="fixed inset-0 bg-black bg-opacity-60 backdrop-blur-sm flex items-center justify-center z-50 hidden" onclick="closeModalOnOutsideClick(event)">
        <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full mx-4 transform transition-all duration-300 scale-100" onclick="event.stopPropagation()">
            <!-- Header -->
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white p-6 rounded-t-2xl relative overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-r from-blue-400 to-blue-500 opacity-50"></div>
                <div class="relative flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="bg-white bg-opacity-20 p-3 rounded-full mr-4">
                            <i class="fas fa-receipt text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-bold">Payment Receipt</h3>
                    </div>
                    <button onclick="closeModal()" class="text-white hover:text-gray-200 transition-colors">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>
            
            <!-- Content -->
            <div class="p-6">
                <div id="receiptContent">
                    <!-- Receipt content will be loaded here -->
                </div>
            </div>
        </div>
    </div>

    <?php $__env->startPush('scripts'); ?>
    <script>
        // Payment data from server
        const paymentData = <?php echo json_encode($paymentDataToShow['payment_records'] ?? [], 15, 512) ?>;

        function viewReceipt(paymentId) {
            // Find the payment record
            const payment = paymentData.find(p => p.id === paymentId);
            
            if (!payment) {
                alert('Payment record not found');
                return;
            }

            // Format session numbers
            let sessionInfo = '';
            if (payment.session_numbers) {
                const sessionNumbers = payment.session_numbers.split(',');
                sessionInfo = sessionNumbers.length > 1 ? 
                    `Sessions ${sessionNumbers.join(', ')}` : 
                    `Session ${sessionNumbers[0]}`;
            } else if (payment.session_count > 0) {
                sessionInfo = `${payment.session_count} Session${payment.session_count > 1 ? 's' : ''}`;
            } else {
                sessionInfo = 'Registration Fee';
            }

            // Format payment type
            const paymentType = payment.payment_type === 'registration' ? 'Registration Fee' : 'Session Payment';

            // Format date and time
            const paymentDate = new Date(payment.payment_date);
            const formattedDate = paymentDate.toLocaleDateString('en-US', { 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric' 
            });
            const formattedTime = paymentDate.toLocaleTimeString('en-US', { 
                hour: '2-digit', 
                minute: '2-digit',
                hour12: true 
            });

            const content = `
                <div class="space-y-6">
                    <!-- Payment Info Header -->
                    <div class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-xl p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <h4 class="font-semibold text-gray-800">${payment.program_name || 'Program Payment'}</h4>
                                <p class="text-sm text-gray-600">${paymentType}</p>
                            </div>
                            <div class="text-right">
                                <div class="text-2xl font-bold text-blue-600">₱${parseFloat(payment.amount).toLocaleString('en-US', {minimumFractionDigits: 2})}</div>
                                <p class="text-sm text-gray-500">${sessionInfo}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Details -->
                    <div class="space-y-4">
                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                            <span class="text-gray-600 font-medium">Payment Date:</span>
                            <span class="font-semibold">${formattedDate}</span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                            <span class="text-gray-600 font-medium">Payment Time:</span>
                            <span class="font-semibold">${formattedTime}</span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                            <span class="text-gray-600 font-medium">Payment Method:</span>
                            <span class="font-semibold capitalize">${payment.payment_method}</span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                            <span class="text-gray-600 font-medium">Payment Type:</span>
                            <span class="font-semibold">${paymentType}</span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                            <span class="text-gray-600 font-medium">Transaction ID:</span>
                            <span class="font-mono text-sm bg-gray-100 px-2 py-1 rounded">${payment.transaction_id}</span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                            <span class="text-gray-600 font-medium">Status:</span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                <i class="fas fa-check-circle mr-1"></i>
                                ${payment.status.charAt(0).toUpperCase() + payment.status.slice(1)}
                            </span>
                        </div>
                        ${payment.session_numbers ? `
                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                            <span class="text-gray-600 font-medium">Session Numbers:</span>
                            <span class="font-semibold">${payment.session_numbers}</span>
                        </div>
                        ` : ''}
                        ${payment.notes ? `
                        <div class="py-2 border-b border-gray-100">
                            <span class="text-gray-600 font-medium block mb-1">Notes:</span>
                            <span class="text-sm text-gray-700">${payment.notes}</span>
                        </div>
                        ` : ''}
                    </div>

                    <!-- Actions -->
                    <div class="pt-4 mt-6 border-t border-gray-200">
                        <div class="flex space-x-3">
                            <button onclick="printReceipt()" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-4 rounded-xl transition-all duration-200 flex items-center justify-center">
                                <i class="fas fa-print mr-2"></i>
                                Print Receipt
                            </button>
                            <button onclick="downloadReceipt()" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-4 rounded-xl transition-all duration-200 flex items-center justify-center">
                                <i class="fas fa-download mr-2"></i>
                                Download PDF
                            </button>
                        </div>
                    </div>
                </div>
            `;
            
            document.getElementById('receiptContent').innerHTML = content;
            document.getElementById('receiptModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('receiptModal').classList.add('hidden');
        }

        function closeModalOnOutsideClick(event) {
            if (event.target.id === 'receiptModal') {
                closeModal();
            }
        }

        function printReceipt() {
            // Create a new window for printing
            const printWindow = window.open('', '_blank');
            const receiptContent = document.getElementById('receiptContent').innerHTML;
            
            printWindow.document.write(`
                <!DOCTYPE html>
                <html>
                <head>
                    <title>Payment Receipt</title>
                    <style>
                        body { font-family: Arial, sans-serif; margin: 20px; }
                        .header { text-align: center; margin-bottom: 30px; }
                        .receipt-info { margin-bottom: 20px; }
                        .receipt-info div { margin-bottom: 10px; }
                        .amount { font-size: 24px; font-weight: bold; color: #059669; }
                        .footer { margin-top: 30px; text-align: center; font-size: 12px; color: #666; }
                        @media print { body { margin: 0; } }
                    </style>
                </head>
                <body>
                    <div class="header">
                        <h1>Payment Receipt</h1>
                        <p>Generated on ${new Date().toLocaleString()}</p>
                    </div>
                    <div class="receipt-info">
                        ${receiptContent.replace(/<[^>]*>/g, '').replace(/\s+/g, ' ').trim()}
                    </div>
                    <div class="footer">
                        <p>This is a computer-generated receipt.</p>
                    </div>
                </body>
                </html>
            `);
            
            printWindow.document.close();
            printWindow.print();
        }

        function downloadReceipt() {
            // For now, we'll trigger the print dialog which can be saved as PDF
            // In a real implementation, you might want to generate a PDF on the server
            alert('PDF download feature will be implemented. For now, you can use the print function and save as PDF.');
        }
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
        document.getElementById('logoutButton').addEventListener('click', function(e) {
            e.preventDefault();
            document.getElementById('logout-form').submit();
        });

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
<?php /**PATH C:\Users\gucor\OneDrive\Documents\Herd\Modular_System\resources\views/Student/payment.blade.php ENDPATH**/ ?>