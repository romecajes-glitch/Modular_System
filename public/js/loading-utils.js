/**
 * Loading Overlay Utility
 * Easy-to-use loading overlay management for BNSC Modular System
 */

class LoadingManager {
    constructor() {
        this.overlay = null;
        this.isVisible = false;
        this.init();
    }

    init() {
        // Create loading overlay if it doesn't exist
        if (!document.getElementById('loading-overlay')) {
            this.createOverlay();
        }
        this.overlay = document.getElementById('loading-overlay');
    }

    createOverlay() {
        const overlayHTML = `
            <div id="loading-overlay" class="loading-overlay hidden">
                <div class="loading-content">
                    <div class="loading-logo">
                        <img src="/pictures/loading.png" alt="BNSC Loading" class="logo-spinner">
                    </div>
                </div>
            </div>
        `;

        // Add styles if not already present
        if (!document.getElementById('loading-styles')) {
            const styles = document.createElement('style');
            styles.id = 'loading-styles';
            styles.textContent = this.getStyles();
            document.head.appendChild(styles);
        }

        // Add overlay to body
        document.body.insertAdjacentHTML('beforeend', overlayHTML);
    }

    getStyles() {
        return `
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

            @keyframes logoSpinReverse {
                0% { transform: rotate(360deg); }
                100% { transform: rotate(0deg); }
            }

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
        `;
    }

    show(title = 'Loading', message = 'Please wait while we process your request') {
        if (!this.overlay) this.init();
        
        this.overlay.classList.remove('hidden');
        this.isVisible = true;
        
        // Prevent body scroll
        document.body.style.overflow = 'hidden';
    }

    hide() {
        if (!this.overlay) return;
        
        this.overlay.classList.add('hidden');
        this.isVisible = false;
        
        // Restore body scroll
        document.body.style.overflow = '';
    }

    updateMessage(message) {
        // Text elements removed - logo only display
    }

    updateTitle(title) {
        // Text elements removed - logo only display
    }

    // Convenience methods for common scenarios
    showFormSubmission() {
        this.show();
    }

    showPageLoad() {
        this.show();
    }

    showDataProcessing() {
        this.show();
    }

    showFileUpload() {
        this.show();
    }

    showLogin() {
        this.show();
    }

    showEnrollment() {
        this.show();
    }
}

// Create global instance
window.loadingManager = new LoadingManager();

// Convenience functions for easy access
window.showLoading = (title, message) => window.loadingManager.show(title, message);
window.hideLoading = () => window.loadingManager.hide();
window.updateLoadingMessage = (message) => window.loadingManager.updateMessage(message);
window.updateLoadingTitle = (title) => window.loadingManager.updateTitle(title);

// Auto-hide loading on page load
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(() => {
        window.loadingManager.hide();
    }, 500);
});

// Export for module systems
if (typeof module !== 'undefined' && module.exports) {
    module.exports = LoadingManager;
}
