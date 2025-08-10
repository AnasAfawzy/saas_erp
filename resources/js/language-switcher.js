// Language switcher functionality
document.addEventListener('DOMContentLoaded', function() {
    // Add smooth transition when switching languages
    const languageForms = document.querySelectorAll('form[action*="language.switch"]');

    languageForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            // Add loading state
            const button = form.querySelector('button[type="submit"]');
            const originalText = button.textContent;

            // Show loading indicator
            button.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>' + originalText;
            button.disabled = true;

            // Add fade effect to body
            document.body.style.transition = 'opacity 0.3s ease';
            document.body.style.opacity = '0.8';
        });
    });

    // Handle language detection and UI adjustments
    const currentLang = document.documentElement.lang;

    // Adjust dropdown positions for RTL/LTR
    if (currentLang === 'ar') {
        // RTL specific adjustments
        const dropdowns = document.querySelectorAll('.dropdown-menu-end');
        dropdowns.forEach(dropdown => {
            dropdown.style.right = '0';
            dropdown.style.left = 'auto';
        });
    } else {
        // LTR specific adjustments
        const dropdowns = document.querySelectorAll('.dropdown-menu-end');
        dropdowns.forEach(dropdown => {
            dropdown.style.right = 'auto';
            dropdown.style.left = '0';
        });
    }

    // Add language indicator to page
    const languageIndicator = document.querySelector('#languageDropdown');
    if (languageIndicator) {
        const currentLanguage = currentLang === 'ar' ? 'العربية' : 'English';
        const flag = currentLang === 'ar' ? '🇸🇦' : '🇺🇸';

        // Update language display
        const langText = languageIndicator.querySelector('.d-none.d-lg-inline');
        if (langText) {
            langText.innerHTML = flag + ' ' + currentLanguage;
        }
    }

    // Handle responsive menu for different languages
    const navbarToggler = document.querySelector('.navbar-toggler');
    const navbarCollapse = document.querySelector('.navbar-collapse');

    if (navbarToggler && navbarCollapse) {
        navbarToggler.addEventListener('click', function() {
            // Add language-specific animation classes
            if (currentLang === 'ar') {
                navbarCollapse.classList.add('slide-right');
            } else {
                navbarCollapse.classList.add('slide-left');
            }
        });
    }
});

// Add CSS animations for language switching
const style = document.createElement('style');
style.textContent = `
    .slide-right {
        animation: slideInRight 0.3s ease-in-out;
    }

    .slide-left {
        animation: slideInLeft 0.3s ease-in-out;
    }

    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    @keyframes slideInLeft {
        from {
            transform: translateX(-100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    .language-switching {
        pointer-events: none;
        opacity: 0.7;
        transition: all 0.3s ease;
    }

    /* Language specific hover effects */
    [dir="rtl"] .dropdown-item:hover {
        padding-right: 1.5rem;
        transition: padding-right 0.2s ease;
    }

    [dir="ltr"] .dropdown-item:hover {
        padding-left: 1.5rem;
        transition: padding-left 0.2s ease;
    }
`;
document.head.appendChild(style);
