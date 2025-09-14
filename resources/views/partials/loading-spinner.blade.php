<!-- Loading Overlay Component -->
<div id="loading-overlay" class="loading-overlay hidden">
    <div class="loading-content">
        <!-- Rotating Logo -->
        <div class="loading-logo">
            <img src="{{ asset('pictures/loading.png') }}" alt="BNSC Loading" class="logo-spinner">
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

<!-- Loading overlay functionality is now handled by loading-utils.js -->
