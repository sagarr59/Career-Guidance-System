// Authentication utility functions

// Toggle password visibility
function togglePassword(inputId, iconId) {
    const passwordInput = document.getElementById(inputId);
    const toggleIcon = document.getElementById(iconId);
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleIcon.classList.remove('fa-eye');
        toggleIcon.classList.add('fa-eye-slash');
    } else {
        passwordInput.type = 'password';
        toggleIcon.classList.remove('fa-eye-slash');
        toggleIcon.classList.add('fa-eye');
    }
}

// Real-time password match validation
function initPasswordValidation(passwordId, confirmPasswordId) {
    const password = document.getElementById(passwordId);
    const confirmPassword = document.getElementById(confirmPasswordId);
    
    if (password && confirmPassword) {
        confirmPassword.addEventListener('input', function() {
            if(confirmPassword.value === '') {
                // Empty - default border
                confirmPassword.style.borderColor = '#e8ecef';
                confirmPassword.style.borderWidth = '2px';
            } else if(password.value === confirmPassword.value) {
                // Matching - green border (thick)
                confirmPassword.style.borderColor = '#27ae60';
                confirmPassword.style.borderWidth = '3px';
            } else {
                // Not matching - red border (thick)
                confirmPassword.style.borderColor = '#e74c3c';
                confirmPassword.style.borderWidth = '3px';
            }
        });
    }
}

// Initialize when DOM is loaded
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize password validation for registration form
        initPasswordValidation('password', 'confirm_password');
    });
} else {
    // Initialize password validation for registration form
    initPasswordValidation('password', 'confirm_password');
}