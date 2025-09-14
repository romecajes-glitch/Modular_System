/**
 * Page Navigation Loader
 * Automatically shows loading overlay when clicking links that navigate to other pages
 */

document.addEventListener('DOMContentLoaded', function() {
    // Wait for loading manager to be available
    const waitForLoadingManager = () => {
        if (typeof window.loadingManager !== 'undefined') {
            initPageNavigationLoader();
        } else {
            setTimeout(waitForLoadingManager, 100);
        }
    };
    
    waitForLoadingManager();
});

function initPageNavigationLoader() {
    // Selectors for links that should show loading
    const navigationSelectors = [
        'a[href^="/"]',           // Internal links
        'a[href^="./"]',          // Relative links
        'a[href^="../"]',         // Parent directory links
        'a[href*="' + window.location.hostname + '"]', // Same domain links
        '.nav-link',              // Navigation links
        '.menu-item',             // Menu items
        '.sidebar-link',          // Sidebar links
        '.dashboard-link',        // Dashboard links
        '.profile-link',          // Profile links
        '.logout-link',           // Logout links
        '.enrollment-link',       // Enrollment links
        '.program-link',          // Program links
        '.admin-link',            // Admin links
        '.student-link',          // Student links
        '.instructor-link'        // Instructor links
    ];

    // Exclude selectors (links that shouldn't show loading)
    const excludeSelectors = [
        'a[href^="#"]',           // Anchor links
        'a[href^="javascript:"]', // JavaScript links
        'a[href^="mailto:"]',     // Email links
        'a[href^="tel:"]',        // Phone links
        'a[target="_blank"]',     // External links
        'a[href*="logout"]',      // Logout links (handle separately)
        'a[href*="login"]',       // Login links
        'a[href*="auth"]',        // Authentication links
        '.no-loading',            // Links with no-loading class
        '.external-link',         // External links
        '.download-link',         // Download links
        '.modal-trigger',         // Modal triggers
        '.dropdown-toggle',       // Dropdown toggles
        '.btn[data-toggle]',      // Bootstrap toggles
        '.accordion-toggle',      // Accordion toggles
        '.login-link',            // Login links
        '.auth-link'              // Authentication links
    ];

    // Get all navigation links
    let navigationLinks = [];
    navigationSelectors.forEach(selector => {
        const links = document.querySelectorAll(selector);
        navigationLinks = navigationLinks.concat(Array.from(links));
    });

    // Remove excluded links
    excludeSelectors.forEach(selector => {
        const excludedLinks = document.querySelectorAll(selector);
        excludedLinks.forEach(link => {
            const index = navigationLinks.indexOf(link);
            if (index > -1) {
                navigationLinks.splice(index, 1);
            }
        });
    });

    // Remove duplicates
    navigationLinks = [...new Set(navigationLinks)];

    // Add click event listeners
    navigationLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            
            // Skip if it's a hash link or external link
            if (!href || href.startsWith('#') || href.startsWith('javascript:') || 
                href.startsWith('mailto:') || href.startsWith('tel:') || 
                this.getAttribute('target') === '_blank') {
                return;
            }

            // Skip if it's the same page
            if (href === window.location.pathname || href === window.location.href) {
                return;
            }

            // Skip if it's a form submission or has special attributes
            if (this.getAttribute('data-no-loading') === 'true' || 
                this.classList.contains('no-loading')) {
                return;
            }

            // Show loading overlay
            showPageNavigationLoading(this);
        });
    });

    // Handle form submissions
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const action = this.getAttribute('action');
            const method = this.getAttribute('method') || 'GET';
            const formId = this.getAttribute('id') || '';
            const formClass = this.className || '';
            
            // Skip if it's a GET form or has no action
            if (method.toLowerCase() === 'get' || !action) {
                return;
            }

            // Skip if it's marked as no-loading
            if (this.getAttribute('data-no-loading') === 'true' || 
                this.classList.contains('no-loading')) {
                return;
            }

            // Skip login forms
            if (action.includes('login') || 
                action.includes('auth') || 
                formId.includes('login') || 
                formClass.includes('login') ||
                this.querySelector('input[name="username"]') ||
                this.querySelector('input[name="email"]') && this.querySelector('input[name="password"]')) {
                return;
            }

            // Show loading overlay
            showFormSubmissionLoading(this);
        });
    });

    // Handle logout links specially (but not login links)
    const logoutLinks = document.querySelectorAll('a[href*="logout"], .logout-link');
    logoutLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            // Skip if it's actually a login link
            if (href && (href.includes('login') || href.includes('auth'))) {
                return;
            }
            showLogoutLoading();
        });
    });

    console.log(`Page Navigation Loader initialized for ${navigationLinks.length} links`);
}

function showPageNavigationLoading(link) {
    // Just show the logo with spinner - no text needed
    window.loadingManager.show();
}

function showFormSubmissionLoading(form) {
    // Just show the logo with spinner - no text needed
    window.loadingManager.show();
}

function showLogoutLoading() {
    // Just show the logo with spinner - no text needed
    window.loadingManager.show();
}

// Handle browser back/forward buttons
window.addEventListener('beforeunload', function() {
    window.loadingManager.show();
});

// Handle page visibility changes
document.addEventListener('visibilitychange', function() {
    if (document.hidden) {
        // Page is being hidden (user navigating away)
        // Don't show loading overlay when switching tabs - this causes the bug
        // window.loadingManager.show();
    } else {
        // Page is becoming visible again (user returned to tab)
        // Hide any loading overlay that might be stuck
        window.loadingManager.hide();
    }
});

// Additional safety: Hide loading overlay when window regains focus
window.addEventListener('focus', function() {
    // Small delay to ensure any pending operations complete
    setTimeout(() => {
        window.loadingManager.hide();
    }, 100);
});

// Export for module systems
if (typeof module !== 'undefined' && module.exports) {
    module.exports = { initPageNavigationLoader, showPageNavigationLoading, showFormSubmissionLoading };
}
