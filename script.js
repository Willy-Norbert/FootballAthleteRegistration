document.addEventListener('DOMContentLoaded', function() {
    
    // Form validation for registration
    const registerForm = document.getElementById('registerForm');
    if (registerForm) {
        registerForm.addEventListener('submit', function(e) {
            const name = document.getElementById('name').value.trim();
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value;
            
            if (name.length < 2) {
                alert('Name must be at least 2 characters long');
                e.preventDefault();
                return;
            }
            
            if (!isValidEmail(email)) {
                alert('Please enter a valid email address');
                e.preventDefault();
                return;
            }
            
            if (password.length < 6) {
                alert('Password must be at least 6 characters long');
                e.preventDefault();
                return;
            }
        });
    }
    
    // Form validation for login
    const loginForm = document.getElementById('loginForm');
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value;
            
            if (!isValidEmail(email)) {
                alert('Please enter a valid email address');
                e.preventDefault();
                return;
            }
            
            if (password.length === 0) {
                alert('Please enter your password');
                e.preventDefault();
                return;
            }
        });
    }
    
    // Form validation for add/edit player
    const playerForms = document.querySelectorAll('#addPlayerForm, #editPlayerForm');
    playerForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const name = document.getElementById('name').value.trim();
            const position = document.getElementById('position').value;
            const team = document.getElementById('team').value.trim();
            const photo = document.getElementById('photo').value.trim();
            
            if (name.length < 2) {
                alert('Player name must be at least 2 characters long');
                e.preventDefault();
                return;
            }
            
            if (!position) {
                alert('Please select a position');
                e.preventDefault();
                return;
            }
            
            if (team.length < 2) {
                alert('Team name must be at least 2 characters long');
                e.preventDefault();
                return;
            }
            
            if (photo && !isValidURL(photo)) {
                alert('Please enter a valid photo URL');
                e.preventDefault();
                return;
            }
        });
    });
    
    // Real-time validation feedback
    const emailInputs = document.querySelectorAll('input[type="email"]');
    emailInputs.forEach(input => {
        input.addEventListener('blur', function() {
            const email = this.value.trim();
            if (email && !isValidEmail(email)) {
                this.style.borderColor = '#dc3545';
                showFieldError(this, 'Please enter a valid email address');
            } else {
                this.style.borderColor = '#000';
                hideFieldError(this);
            }
        });
    });
    
    // Password strength indicator
    const passwordInputs = document.querySelectorAll('input[type="password"]');
    passwordInputs.forEach(input => {
        input.addEventListener('input', function() {
            const password = this.value;
            let strength = '';
            let color = '';
            
            if (password.length === 0) {
                strength = '';
            } else if (password.length < 6) {
                strength = 'Too short';
                color = '#dc3545';
            } else if (password.length < 8) {
                strength = 'Weak';
                color = '#ffc107';
            } else {
                strength = 'Good';
                color = '#28a745';
            }
            
            showPasswordStrength(this, strength, color);
        });
    });
    
    // URL validation for photo inputs
    const urlInputs = document.querySelectorAll('input[type="url"]');
    urlInputs.forEach(input => {
        input.addEventListener('blur', function() {
            const url = this.value.trim();
            if (url && !isValidURL(url)) {
                this.style.borderColor = '#dc3545';
                showFieldError(this, 'Please enter a valid URL');
            } else {
                this.style.borderColor = '#000';
                hideFieldError(this);
            }
        });
    });
    
    // Auto-hide alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            setTimeout(() => {
                alert.remove();
            }, 300);
        }, 5000);
    });
});

// Utility functions
function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

function isValidURL(url) {
    try {
        new URL(url);
        return true;
    } catch {
        return false;
    }
}

function showFieldError(input, message) {
    hideFieldError(input); // Remove existing error
    
    const errorDiv = document.createElement('div');
    errorDiv.className = 'field-error';
    errorDiv.style.color = '#dc3545';
    errorDiv.style.fontSize = '12px';
    errorDiv.style.marginTop = '5px';
    errorDiv.textContent = message;
    
    input.parentNode.appendChild(errorDiv);
}

function hideFieldError(input) {
    const existingError = input.parentNode.querySelector('.field-error');
    if (existingError) {
        existingError.remove();
    }
}

function showPasswordStrength(input, strength, color) {
    let strengthDiv = input.parentNode.querySelector('.password-strength');
    
    if (!strengthDiv) {
        strengthDiv = document.createElement('div');
        strengthDiv.className = 'password-strength';
        strengthDiv.style.fontSize = '12px';
        strengthDiv.style.marginTop = '5px';
        input.parentNode.appendChild(strengthDiv);
    }
    
    strengthDiv.textContent = strength ? `Password strength: ${strength}` : '';
    strengthDiv.style.color = color;
}

// Confirmation dialogs for delete actions
function confirmDelete(playerName) {
    return confirm(`Are you sure you want to delete ${playerName}? This action cannot be undone.`);
}

// Image loading error handling
document.addEventListener('DOMContentLoaded', function() {
    const images = document.querySelectorAll('.player-photo');
    images.forEach(img => {
        img.addEventListener('error', function() {
            this.src = 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=150&h=150&fit=crop&crop=face';
        });
    });
});

// Form auto-save for better UX (saves to localStorage temporarily)
function autoSaveForm(formId) {
    const form = document.getElementById(formId);
    if (!form) return;
    
    const inputs = form.querySelectorAll('input, select, textarea');
    
    // Load saved data
    inputs.forEach(input => {
        const savedValue = localStorage.getItem(`${formId}_${input.name}`);
        if (savedValue && input.type !== 'password') {
            input.value = savedValue;
        }
    });
    
    // Save data on input
    inputs.forEach(input => {
        input.addEventListener('input', function() {
            if (this.type !== 'password') {
                localStorage.setItem(`${formId}_${this.name}`, this.value);
            }
        });
    });
    
    // Clear saved data on successful submit
    form.addEventListener('submit', function() {
        inputs.forEach(input => {
            localStorage.removeItem(`${formId}_${input.name}`);
        });
    });
}

// Initialize auto-save for forms
document.addEventListener('DOMContentLoaded', function() {
    autoSaveForm('addPlayerForm');
    autoSaveForm('editPlayerForm');
});