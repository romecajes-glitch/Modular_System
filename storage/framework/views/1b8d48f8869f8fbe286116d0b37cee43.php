<!-- Loading System Integration -->
<!-- Include this in your main layout file (e.g., app.blade.php or layout.blade.php) -->

<!-- Loading Overlay Component -->
<?php echo $__env->make('partials.loading-spinner', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<!-- Loading JavaScript Files -->
<script src="<?php echo e(asset('js/loading-utils.js')); ?>"></script>
<script src="<?php echo e(asset('js/page-navigation-loader.js')); ?>"></script>

<!-- Optional: Custom Loading Configuration -->
<script>
    // Customize loading messages for your specific pages
    document.addEventListener('DOMContentLoaded', function() {
        // Override default messages if needed
        if (window.loadingManager) {
            // Add custom loading scenarios
            window.loadingManager.showAttendance = function() {
                this.show('Loading Attendance', 'Please wait while we load attendance records');
            };
            
            window.loadingManager.showSchedule = function() {
                this.show('Loading Schedule', 'Please wait while we load your schedule');
            };
            
            window.loadingManager.showCertificate = function() {
                this.show('Loading Certificate', 'Please wait while we load your certificate');
            };
            
            window.loadingManager.showPayment = function() {
                this.show('Loading Payment', 'Please wait while we load payment information');
            };
        }
    });
</script>

<!-- Optional: Manual Loading Triggers -->
<script>
    // You can manually trigger loading for specific actions
    function showCustomLoading(title, message) {
        if (window.loadingManager) {
            window.loadingManager.show(title, message);
        }
    }
    
    // Example usage:
    // showCustomLoading('Processing Data', 'Please wait...');
</script>
<?php /**PATH C:\Users\gucor\OneDrive\Documents\Herd\Modular_System\resources\views/partials/loading-integration.blade.php ENDPATH**/ ?>