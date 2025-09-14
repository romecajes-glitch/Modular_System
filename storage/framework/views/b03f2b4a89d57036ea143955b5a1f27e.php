<!-- Logo (Collapse Button) -->
<button id="toggleSidebar" class="p-4 flex items-center justify-center border-b border-blue-800/50 w-full transition-all duration-300 hover:bg-blue-600/20 hover:shadow-lg group">
    <div class="bg-gradient-to-r from-blue-600 to-blue-500 p-2 rounded-xl shadow-lg group-hover:shadow-xl group-hover:scale-105 transition-all duration-300">
        <img src="<?php echo e(asset('pictures/logo.png')); ?>" alt="Logo" class="w-8 h-8 object-contain">
    </div>
    <span class="logo-text ml-3 font-bold text-xl text-white group-hover:text-blue-100 transition-colors duration-300">Instructor Portal</span>
    <div class="ml-auto collapse-icon group-hover:scale-110 transition-all duration-300">
        <i class="fas fa-chevron-left text-blue-300 group-hover:text-white transition-all duration-300"></i>
    </div>
</button>

<!-- User Profile -->
<div class="user-profile w-full p-4 flex items-center border-b border-blue-800/50 transition-all duration-300 hover:bg-blue-600/20 hover:shadow-md group cursor-pointer">
    <div class="h-12 w-12 rounded-full bg-gradient-to-r from-blue-600 to-blue-400 flex items-center justify-center shadow-lg group-hover:shadow-xl group-hover:scale-105 transition-all duration-300">
        <?php if(auth()->user()->photo): ?>
            <img src="<?php echo e(asset('storage/' . auth()->user()->photo)); ?>" alt="Profile Photo" class="w-12 h-12 rounded-full object-cover">
        <?php else: ?>
            <i class="fas fa-user text-white text-lg group-hover:scale-110 transition-transform duration-300"></i>
        <?php endif; ?>
    </div>
    <div class="ml-3 user-details text-left">
        <div class="font-semibold text-white group-hover:text-blue-100 transition-colors duration-300"><?php echo e(auth()->user()->name); ?></div>
        <div class="text-xs text-blue-200 group-hover:text-blue-300 transition-colors duration-300">Instructor</div>
    </div>
</div>
<div class="flex-1 overflow-y-auto">
    <ul class="py-2 space-y-1">
        <!-- Dashboard -->
        <li style="cursor: pointer;" id="dashboardNav" data-url="<?php echo e(route('instructor.dashboard')); ?>" class="nav-item group px-4 py-3 flex items-center transition-all duration-300 rounded-lg mx-2 hover:bg-blue-600/20 hover:shadow-md hover:scale-[1.02] <?php echo e(request()->is('instructor/dashboard') ? 'active-nav' : ''); ?>">
            <div class="nav-icon-container w-8 h-8 flex items-center justify-center bg-blue-700/30 group-hover:bg-blue-500 group-hover:shadow-lg rounded-full transition-all duration-300">
                <i class="fas fa-tachometer-alt text-blue-200 group-hover:text-white transition-colors duration-300"></i>
            </div>
            <a class="nav-text ml-3 block w-full text-blue-100 group-hover:text-white font-medium transition-colors duration-300">Dashboard</a>
            <div class="ml-auto opacity-0 group-hover:opacity-100 transition-all duration-300 transform group-hover:translate-x-1">
                <i class="fas fa-chevron-right text-xs text-blue-300"></i>
            </div>
        </li>

        <!-- Students -->
        <li style="cursor: pointer;" id="studentsNav" data-url="<?php echo e(route('instructor.students')); ?>" class="nav-item group px-4 py-3 flex items-center transition-all duration-300 rounded-lg mx-2 hover:bg-blue-600/20 hover:shadow-md hover:scale-[1.02] <?php echo e(request()->is('instructor/students*') ? 'active-nav' : ''); ?>">
            <div class="nav-icon-container w-8 h-8 flex items-center justify-center bg-blue-700/30 group-hover:bg-blue-500 group-hover:shadow-lg rounded-full transition-all duration-300">
                <i class="fas fa-users text-blue-200 group-hover:text-white transition-colors duration-300"></i>
            </div>
            <a class="nav-text ml-3 block w-full text-blue-100 group-hover:text-white font-medium transition-colors duration-300">Students</a>
            <div class="ml-auto opacity-0 group-hover:opacity-100 transition-all duration-300 transform group-hover:translate-x-1">
                <i class="fas fa-chevron-right text-xs text-blue-300"></i>
            </div>
        </li>

        <!-- Attendance -->
        <li style="cursor: pointer;" id="attendanceNav" data-url="<?php echo e(route('instructor.attendance')); ?>" class="nav-item group px-4 py-3 flex items-center transition-all duration-300 rounded-lg mx-2 hover:bg-blue-600/20 hover:shadow-md hover:scale-[1.02] <?php echo e(request()->is('instructor/attendance*') ? 'active-nav' : ''); ?>">
            <div class="nav-icon-container w-8 h-8 flex items-center justify-center bg-blue-700/30 group-hover:bg-blue-500 group-hover:shadow-lg rounded-full transition-all duration-300">
                <i class="fas fa-clipboard-check text-blue-200 group-hover:text-white transition-colors duration-300"></i>
            </div>
            <a class="nav-text ml-3 block w-full text-blue-100 group-hover:text-white font-medium transition-colors duration-300">Attendance</a>
            <div class="ml-auto opacity-0 group-hover:opacity-100 transition-all duration-300 transform group-hover:translate-x-1">
                <i class="fas fa-chevron-right text-xs text-blue-300"></i>
            </div>
        </li>

        <!-- Certificate -->
        <li style="cursor: pointer;" id="certificateNav" data-url="<?php echo e(route('instructor.certificates')); ?>" class="nav-item group px-4 py-3 flex items-center transition-all duration-300 rounded-lg mx-2 hover:bg-blue-600/20 hover:shadow-md hover:scale-[1.02] <?php echo e(request()->is('instructor/certificates*') ? 'active-nav' : ''); ?>">
            <div class="nav-icon-container w-8 h-8 flex items-center justify-center bg-blue-700/30 group-hover:bg-blue-500 group-hover:shadow-lg rounded-full transition-all duration-300">
                <i class="fas fa-certificate text-blue-200 group-hover:text-white transition-colors duration-300"></i>
            </div>
            <a class="nav-text ml-3 block w-full text-blue-100 group-hover:text-white font-medium transition-colors duration-300">Certificates</a>
            <div class="ml-auto opacity-0 group-hover:opacity-100 transition-all duration-300 transform group-hover:translate-x-1">
                <i class="fas fa-chevron-right text-xs text-blue-300"></i>
            </div>
        </li>
    </ul>
</div>
<?php /**PATH C:\Users\gucor\OneDrive\Documents\Herd\Modular_System\resources\views/Instructor/partials/navigation.blade.php ENDPATH**/ ?>