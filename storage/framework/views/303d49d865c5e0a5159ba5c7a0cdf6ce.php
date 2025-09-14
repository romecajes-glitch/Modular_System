<!-- Loading System Integration for Instructor -->
<!-- Include this in your instructor layout files -->

<!-- Loading Overlay Component -->
<?php echo $__env->make('components.loading-overlay', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<!-- Loading JavaScript Files -->
<script src="<?php echo e(asset('js/loading-utils.js')); ?>"></script>
<script src="<?php echo e(asset('js/page-navigation-loader.js')); ?>"></script>

<!-- Instructor-specific Loading Configuration -->
<script>
    // Customize loading messages for instructor-specific pages
    document.addEventListener('DOMContentLoaded', function() {
        // Wait for loading manager to be available
        const waitForLoadingManager = () => {
            if (typeof window.loadingManager !== 'undefined') {
                initInstructorLoading();
            } else {
                setTimeout(waitForLoadingManager, 100);
            }
        };
        
        waitForLoadingManager();
    });

    function initInstructorLoading() {
        // Add loading to instructor-specific navigation
        const instructorNavItems = document.querySelectorAll('.nav-item[data-url]');
        instructorNavItems.forEach(item => {
            item.addEventListener('click', function() {
                const navText = this.querySelector('.nav-text').textContent.trim();
                if (navText) {
                    window.loadingManager.show();
                }
            });
        });

        // Add loading to profile modal button
        const profileButton = document.querySelector('a[onclick*="openProfileModal"]');
        if (profileButton) {
            profileButton.addEventListener('click', function(e) {
                window.loadingManager.show();
                // Hide loading after a short delay since it's a modal
                setTimeout(() => {
                    window.loadingManager.hide();
                }, 500);
            });
        }

        // Add loading to logout button
        const logoutButton = document.getElementById('logoutButton');
        if (logoutButton) {
            logoutButton.addEventListener('click', function(e) {
                e.preventDefault();
                window.loadingManager.show();
                
                // Submit the logout form after showing loading
                setTimeout(() => {
                    document.getElementById('logout-form').submit();
                }, 1000);
            });
        }

        // Add loading to attendance-related buttons
        const attendanceButtons = document.querySelectorAll('button[onclick*="attendance"], a[href*="attendance"]');
        attendanceButtons.forEach(button => {
            button.addEventListener('click', function() {
                window.loadingManager.show();
            });
        });

        // Add loading to certificate-related buttons
        const certificateButtons = document.querySelectorAll('button[onclick*="certificate"], a[href*="certificate"]');
        certificateButtons.forEach(button => {
            button.addEventListener('click', function() {
                window.loadingManager.show();
            });
        });

        // Add loading to student-related buttons
        const studentButtons = document.querySelectorAll('button[onclick*="student"], a[href*="student"]');
        studentButtons.forEach(button => {
            button.addEventListener('click', function() {
                window.loadingManager.show();
            });
        });
    }
</script>

<!-- Optional: Manual Loading Triggers -->
<script>
    // You can manually trigger loading for specific instructor actions
    function showInstructorLoading(title, message) {
        if (window.loadingManager) {
            window.loadingManager.show(title, message);
        }
    }
    
    // Instructor-specific loading functions
    function showAttendanceLoading() {
        showInstructorLoading();
    }
    
    function showCertificateLoading() {
        showInstructorLoading();
    }
    
    function showStudentLoading() {
        showInstructorLoading();
    }
    
    function showProfileLoading() {
        showInstructorLoading();
    }
</script>
<?php /**PATH C:\Users\gucor\OneDrive\Documents\Herd\Modular_System\resources\views/Instructor/partials/loading-integration.blade.php ENDPATH**/ ?>