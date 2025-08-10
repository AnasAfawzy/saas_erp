// App-wide JavaScript utilities and configurations
// This file should be included in app.php or main layout

window.App = window.App || {};

// CSRF Token Management
App.csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

// HTTP Request Helper
App.request = async function(url, options = {}) {
    const defaultOptions = {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': App.csrfToken,
            'Accept': 'application/json'
        }
    };

    const config = { ...defaultOptions, ...options };
    
    if (config.headers['Content-Type'] === 'application/json' && config.body && typeof config.body === 'object') {
        config.body = JSON.stringify(config.body);
    }

    try {
        const response = await fetch(url, config);
        return await response.json();
    } catch (error) {
        console.error('Request failed:', error);
        throw error;
    }
};

// Sweet Alert Helper
App.alert = {
    success: function(message, title = 'Success') {
        return Swal.fire({
            icon: 'success',
            title: title,
            text: message,
            showConfirmButton: false,
            timer: 2000,
            timerProgressBar: true,
            toast: true,
            position: 'top-end'
        });
    },
    
    error: function(message, title = 'Error') {
        return Swal.fire({
            icon: 'error',
            title: title,
            text: message,
            confirmButtonText: 'OK',
            confirmButtonColor: '#dc3545'
        });
    },
    
    confirm: function(message, title = 'Are you sure?') {
        return Swal.fire({
            title: title,
            text: message,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes',
            cancelButtonText: 'Cancel'
        });
    },
    
    loading: function(message = 'Please wait...', title = 'Loading') {
        return Swal.fire({
            title: title,
            text: message,
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
    }
};

// Form Validation Helper
App.validation = {
    clearErrors: function(form) {
        if (typeof form === 'string') {
            form = document.getElementById(form);
        }
        form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        form.querySelectorAll('.invalid-feedback').forEach(el => el.remove());
    },
    
    showErrors: function(errors, form) {
        if (typeof form === 'string') {
            form = document.getElementById(form);
        }
        
        Object.keys(errors).forEach(field => {
            let input = form.querySelector(`[name="${field}"]`) || 
                       document.getElementById(field) ||
                       document.getElementById(field.replace('_', ''));
            
            if (input) {
                input.classList.add('is-invalid');
                const feedback = document.createElement('div');
                feedback.className = 'invalid-feedback';
                feedback.textContent = errors[field][0];
                input.parentNode.appendChild(feedback);
            }
        });
    }
};

// Initialize App
document.addEventListener('DOMContentLoaded', function() {
    // Update CSRF token if it changes
    const tokenMeta = document.querySelector('meta[name="csrf-token"]');
    if (tokenMeta) {
        App.csrfToken = tokenMeta.getAttribute('content');
    }
    
    console.log('App initialized successfully');
});
