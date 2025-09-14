<div class="flex-1 overflow-y-auto">
    <ul class="py-2 space-y-1">
        <!-- Dashboard -->
        <li style="cursor: pointer;" id="dashboardNav" data-url="<?php echo e(route('student.dashboard')); ?>" class="nav-item group px-4 py-3 flex items-center transition-all duration-300 rounded-lg mx-2 <?php echo e(request()->is('student/dashboard') ? 'active-nav' : ''); ?>">
            <div class="nav-icon-container w-8 h-8 flex items-center justify-center bg-blue-700/30 group-hover:bg-blue-600/50 rounded-full">
                <i class="fas fa-tachometer-alt text-blue-200"></i>
            </div>
            <a class="nav-text ml-3 block w-full text-blue-100 group-hover:text-white font-medium">Dashboard</a>
            <div class="ml-auto opacity-0 group-hover:opacity-100 transition-opacity">
                <i class="fas fa-chevron-right text-xs text-blue-300"></i>
            </div>
        </li>

        <!-- Session Attendance -->
        <li style="cursor: pointer;" id="attendancetNav" data-url="<?php echo e(route('student.attendance')); ?>" class="nav-item group px-4 py-3 flex items-center transition-all duration-300 rounded-lg mx-2 <?php echo e(request()->is('student/attendance') ? 'active-nav' : ''); ?>">
            <div class="nav-icon-container w-8 h-8 flex items-center justify-center bg-blue-700/30 group-hover:bg-blue-600/50 rounded-full">
                <i class="fas fa-calendar-check text-blue-200"></i>
            </div>
            <a class="nav-text ml-3 block w-full text-blue-100 group-hover:text-white font-medium">Session Attendance</a>
            <div class="ml-auto opacity-0 group-hover:opacity-100 transition-opacity">
                <i class="fas fa-chevron-right text-xs text-blue-300"></i>
            </div>
        </li>

        <!-- Payment Records -->
        <li style="cursor: pointer;" id="paymentNav" data-url="<?php echo e(route('student.payment')); ?>" class="nav-item group px-4 py-3 flex items-center transition-all duration-300 rounded-lg mx-2 <?php echo e(request()->is('student/payment') ? 'active-nav' : ''); ?>">
            <div class="nav-icon-container w-8 h-8 flex items-center justify-center bg-blue-700/30 group-hover:bg-blue-600/50 rounded-full">
                <i class="fas fa-credit-card text-blue-200"></i>
            </div>
            <a class="nav-text ml-3 block w-full text-blue-100 group-hover:text-white font-medium">Payment Records</a>
            <div class="ml-auto opacity-0 group-hover:opacity-100 transition-opacity">
                <i class="fas fa-chevron-right text-xs text-blue-300"></i>
            </div>
        </li>

        <!-- Certificate -->
        <li style="cursor: pointer;" id="certificateNav" data-url="<?php echo e(route('student.certificate')); ?>" class="nav-item group px-4 py-3 flex items-center transition-all duration-300 rounded-lg mx-2 <?php echo e(request()->is('student/certificate') ? 'active-nav' : ''); ?>">
            <div class="nav-icon-container w-8 h-8 flex items-center justify-center bg-blue-700/30 group-hover:bg-blue-600/50 rounded-full">
                <i class="fas fa-award text-blue-200"></i>
            </div>
            <a class="nav-text ml-3 block w-full text-blue-100 group-hover:text-white font-medium">Certificate</a>
            <div class="ml-auto opacity-0 group-hover:opacity-100 transition-opacity">
                <i class="fas fa-chevron-right text-xs text-blue-300"></i>
            </div>
        </li>
    </ul>
</div>
<?php /**PATH C:\Users\gucor\OneDrive\Documents\Herd\Modular_System\resources\views/Student/partials/navigation.blade.php ENDPATH**/ ?>