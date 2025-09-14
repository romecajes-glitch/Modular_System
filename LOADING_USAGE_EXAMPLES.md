# Loading Overlay Usage Examples

This document shows how to use the loading overlay components in your BNSC Modular System.

## Files Created

1. **`resources/views/components/loading-overlay.blade.php`** - Full page loading screen with logo spinner
2. **`resources/views/partials/loading-spinner.blade.php`** - Overlay component for existing pages
3. **`public/js/loading-utils.js`** - JavaScript utility for easy loading management
4. **`public/js/page-navigation-loader.js`** - Automatic loading for page navigation
5. **`resources/views/partials/loading-integration.blade.php`** - Easy integration file

## Usage Methods

### Method 1: Full Page Loading Screen

Use this for complete page redirects or when you need a full loading page.

```php
// In your controller
return view('components.loading-overlay', [
    'title' => 'Processing Enrollment',
    'message' => 'Please wait while we process your enrollment application',
    'redirect_url' => '/dashboard', // Optional: auto-redirect after delay
    'redirect_delay' => 3, // Optional: delay in seconds
    'allow_skip' => true // Optional: allow clicking to skip
]);
```

### Method 2: Automatic Page Navigation Loading (Recommended)

This automatically shows loading overlay when users click links that navigate to other pages.

#### Step 1: Include the integration file in your main layout

```php
<!-- In your main layout file (e.g., resources/views/layouts/app.blade.php) -->
@include('partials.loading-integration')
```

That's it! The loading overlay will now automatically appear when users click navigation links.

### Method 3: Manual Overlay Control

For manual control over when to show/hide the loading overlay.

#### Step 1: Include the component in your Blade template

```php
<!-- In your Blade template (e.g., layout.blade.php) -->
@include('partials.loading-spinner')
```

#### Step 2: Include the JavaScript utility

```html
<!-- In your Blade template -->
<script src="{{ asset('js/loading-utils.js') }}"></script>
```

#### Step 3: Use in your JavaScript

```javascript
// Show loading overlay
showLoading('Processing', 'Please wait...');

// Or use the manager directly
loadingManager.show('Processing', 'Please wait...');

// Update message while loading
updateLoadingMessage('Almost done...');

// Hide loading overlay
hideLoading();

// Or use the manager directly
loadingManager.hide();
```

### Method 4: Predefined Loading Scenarios

The utility includes predefined methods for common scenarios:

```javascript
// Form submission
loadingManager.showFormSubmission();

// Page loading
loadingManager.showPageLoad();

// Data processing
loadingManager.showDataProcessing();

// File upload
loadingManager.showFileUpload();

// Login process
loadingManager.showLogin();

// Enrollment process
loadingManager.showEnrollment();
```

## Automatic Navigation Loading

The system automatically detects and shows loading overlay for:

- **Internal links** (`/dashboard`, `/profile`, etc.)
- **Navigation links** (`.nav-link`, `.menu-item`, etc.)
- **Form submissions** (POST, PUT, DELETE methods)
- **Logout links** (special handling)
- **Browser back/forward buttons**

### Excluded from Loading:
- Anchor links (`#section`)
- External links (`target="_blank"`)
- Email/phone links (`mailto:`, `tel:`)
- JavaScript links (`javascript:`)
- Links with `data-no-loading="true"` attribute
- Links with `.no-loading` class

### Customizing Automatic Loading:

```html
<!-- Exclude specific links from loading -->
<a href="/some-page" data-no-loading="true">Skip Loading</a>
<a href="/other-page" class="no-loading">Skip Loading</a>

<!-- Exclude forms from loading -->
<form data-no-loading="true">
    <!-- form content -->
</form>
```

## Real-World Examples

### Example 1: Form Submission with Loading

```javascript
document.getElementById('enrollment-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Show loading
    loadingManager.showEnrollment();
    
    // Submit form
    fetch('/enroll', {
        method: 'POST',
        body: new FormData(this)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            loadingManager.updateMessage('Enrollment successful! Redirecting...');
            setTimeout(() => {
                window.location.href = '/dashboard';
            }, 2000);
        } else {
            loadingManager.hide();
            // Show error message
        }
    })
    .catch(error => {
        loadingManager.hide();
        // Show error message
    });
});
```

### Example 2: Page Navigation with Loading

```javascript
// Show loading when navigating to a new page
function navigateToPage(url) {
    loadingManager.showPageLoad();
    window.location.href = url;
}

// Usage
document.getElementById('dashboard-link').addEventListener('click', function(e) {
    e.preventDefault();
    navigateToPage('/dashboard');
});
```

### Example 3: File Upload with Progress

```javascript
document.getElementById('file-upload').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        loadingManager.showFileUpload();
        
        const formData = new FormData();
        formData.append('file', file);
        
        fetch('/upload', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            loadingManager.updateMessage('File uploaded successfully!');
            setTimeout(() => {
                loadingManager.hide();
            }, 1500);
        })
        .catch(error => {
            loadingManager.hide();
            alert('Upload failed');
        });
    }
});
```

### Example 4: AJAX Requests with Loading

```javascript
function makeAjaxRequest(url, data) {
    loadingManager.showDataProcessing();
    
    return fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify(data)
    })
    .then(response => {
        loadingManager.hide();
        return response.json();
    })
    .catch(error => {
        loadingManager.hide();
        throw error;
    });
}

// Usage
makeAjaxRequest('/api/update-profile', { name: 'John Doe' })
    .then(data => {
        console.log('Success:', data);
    })
    .catch(error => {
        console.error('Error:', error);
    });
```

## Customization

### Custom Messages

```javascript
// Custom title and message
loadingManager.show('Custom Title', 'Custom message here');

// Update messages dynamically
loadingManager.updateTitle('New Title');
loadingManager.updateMessage('New message');
```

### Custom Styling

You can customize the appearance by modifying the CSS in the component files or adding your own styles:

```css
/* Custom loading overlay styles */
.loading-overlay {
    background: rgba(0, 0, 0, 0.9); /* Darker background */
}

.loading-content {
    background: rgba(255, 255, 255, 0.2); /* More transparent */
    border-radius: 25px; /* More rounded */
}

.logo-spinner {
    width: 100px; /* Larger logo */
    height: 100px;
}
```

## Best Practices

1. **Always hide loading overlay** - Make sure to call `hideLoading()` or `loadingManager.hide()` when the operation completes
2. **Use appropriate messages** - Provide clear, user-friendly messages
3. **Handle errors** - Always hide loading overlay in error scenarios
4. **Don't overuse** - Only show loading for operations that take more than 500ms
5. **Update progress** - Use `updateLoadingMessage()` for long-running operations

## Browser Support

- Modern browsers (Chrome, Firefox, Safari, Edge)
- Mobile browsers
- IE11+ (with some limitations on backdrop-filter)

## Performance Notes

- The loading overlay is lightweight and doesn't impact page performance
- CSS animations are hardware-accelerated
- JavaScript utility is minimal and fast
- No external dependencies required
