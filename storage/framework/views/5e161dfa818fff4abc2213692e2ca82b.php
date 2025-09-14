<!-- Loading Overlay Component -->
<div id="loading-overlay" class="loading-overlay hidden">
    <div class="loading-content">
        <!-- Rotating Logo -->
        <div class="loading-logo">
            <img src="<?php echo e(asset('pictures/loading.png')); ?>" alt="BNSC Loading" class="logo-spinner">
        </div>
    </div>
</div>

<style>
    .loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.3);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        transition: opacity 0.3s ease;
    }

    .loading-overlay.hidden {
        display: none;
    }

    .loading-content {
        text-align: center;
        color: white;
        padding: 2rem;
        background: transparent;
        border-radius: 20px;
        max-width: 400px;
        width: 90%;
    }

    .loading-logo {
        position: relative;
        display: inline-block;
    }

    .logo-spinner {
        width: 150px;
        height: 150px;
        border-radius: 15px;
        animation: logoSpinReverse 2s linear infinite;
    }

    /* Animations */
    @keyframes logoSpinReverse {
        0% { transform: rotate(360deg); }
        100% { transform: rotate(0deg); }
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .loading-content {
            padding: 1.5rem;
            max-width: 300px;
        }
        
        .logo-spinner {
            width: 120px;
            height: 120px;
        }
    }

    @media (max-width: 480px) {
        .loading-content {
            padding: 1rem;
            max-width: 250px;
        }
        
        .logo-spinner {
            width: 100px;
            height: 100px;
        }
    }
</style>

<script>
    // Loading overlay utility functions
    window.LoadingOverlay = {
        show: function(title = 'Loading', message = 'Please wait while we process your request') {
            const overlay = document.getElementById('loading-overlay');
            const titleElement = document.getElementById('loading-title');
            const messageElement = document.getElementById('loading-message');
            
            if (titleElement) titleElement.textContent = title;
            if (messageElement) messageElement.textContent = message;
            
            overlay.classList.remove('hidden');
        },
        
        hide: function() {
            const overlay = document.getElementById('loading-overlay');
            overlay.classList.add('hidden');
        },
        
        updateMessage: function(message) {
            const messageElement = document.getElementById('loading-message');
            if (messageElement) messageElement.textContent = message;
        },
        
        updateTitle: function(title) {
            const titleElement = document.getElementById('loading-title');
            if (titleElement) titleElement.textContent = title;
        }
    };

    // Auto-hide loading overlay when page is fully loaded
    document.addEventListener('DOMContentLoaded', function() {
        // Hide loading overlay if it's visible on page load
        setTimeout(function() {
            LoadingOverlay.hide();
        }, 1000);
    });
</script>
<?php /**PATH C:\Users\gucor\OneDrive\Documents\Herd\Modular_System\resources\views/components/loading-overlay.blade.php ENDPATH**/ ?>