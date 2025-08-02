/**
 * Form Validation JavaScript for Rwanda Football Registry System
 * Provides client-side validation for athlete registration form
 * Simple and easy to understand validation functions
 */

// Wait for DOM to be fully loaded
document.addEventListener('DOMContentLoaded', function() {
    
    // Get the form element
    const form = document.getElementById('athleteForm');
    
    if (form) {
        // Add event listener for form submission
        form.addEventListener('submit', function(e) {
            if (!validateForm()) {
                e.preventDefault(); // Stop form submission if validation fails
            }
        });

        // Add real-time validation for specific fields
        setupRealTimeValidation();
    }
});

/**
 * Main form validation function
 * Returns true if all validations pass, false otherwise
 */
function validateForm() {
    let isValid = true;
    let errorMessages = [];

    // Clear previous error styles
    clearErrorStyles();

    // Validate Name
    const name = document.getElementById('name').value.trim();
    if (name.length < 2) {
        showFieldError('name', 'Name must be at least 2 characters long');
        errorMessages.push('Invalid name');
        isValid = false;
    }

    // Validate Age
    const age = parseInt(document.getElementById('age').value);
    if (isNaN(age) || age < 15 || age > 40) {
        showFieldError('age', 'Age must be between 15 and 40 years');
        errorMessages.push('Invalid age');
        isValid = false;
    }

    // Validate Team
    const team = document.getElementById('team').value.trim();
    if (team.length < 2) {
        showFieldError('team', 'Team name must be at least 2 characters long');
        errorMessages.push('Invalid team name');
        isValid = false;
    }

    // Validate Position
    const position = document.getElementById('position').value;
    if (position === '') {
        showFieldError('position', 'Please select a position');
        errorMessages.push('Position is required');
        isValid = false;
    }

    // Validate Height
    const height = parseFloat(document.getElementById('height').value);
    if (isNaN(height) || height < 150 || height > 220) {
        showFieldError('height', 'Height must be between 150-220 cm');
        errorMessages.push('Invalid height');
        isValid = false;
    }

    // Validate Rating
    const rating = parseInt(document.getElementById('rating').value);
    if (isNaN(rating) || rating < 1 || rating > 100) {
        showFieldError('rating', 'Rating must be between 1 and 100');
        errorMessages.push('Invalid rating');
        isValid = false;
    }

    // Validate Market Value
    const marketValue = parseFloat(document.getElementById('market_value').value);
    if (isNaN(marketValue) || marketValue < 0) {
        showFieldError('market_value', 'Market value must be a positive number');
        errorMessages.push('Invalid market value');
        isValid = false;
    }

    // Validate Photo (if uploaded)
    const photoFile = document.getElementById('photo').files[0];
    if (photoFile) {
        if (!validatePhotoFile(photoFile)) {
            showFieldError('photo', 'Invalid photo file');
            errorMessages.push('Invalid photo file');
            isValid = false;
        }
    }

    // Show summary of errors if any
    if (!isValid) {
        showValidationSummary(errorMessages);
    } else {
        // Show success message
        showSuccessMessage('Form validation passed! Submitting...');
    }

    return isValid;
}

/**
 * Validate photo file type and size
 */
function validatePhotoFile(file) {
    const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
    const maxSize = 2 * 1024 * 1024; // 2MB in bytes

    // Check file type
    if (!allowedTypes.includes(file.type)) {
        alert('Please upload only JPG, PNG, or GIF images');
        return false;
    }

    // Check file size
    if (file.size > maxSize) {
        alert('Photo size must be less than 2MB');
        return false;
    }

    return true;
}

/**
 * Setup real-time validation for form fields
 */
function setupRealTimeValidation() {
    
    // Age validation
    document.getElementById('age').addEventListener('blur', function() {
        const age = parseInt(this.value);
        if (this.value && (isNaN(age) || age < 15 || age > 40)) {
            showFieldError('age', 'Age must be between 15 and 40');
        } else {
            clearFieldError('age');
        }
    });

    // Height validation
    document.getElementById('height').addEventListener('blur', function() {
        const height = parseFloat(this.value);
        if (this.value && (isNaN(height) || height < 150 || height > 220)) {
            showFieldError('height', 'Height must be between 150-220 cm');
        } else {
            clearFieldError('height');
        }
    });

    // Rating validation
    document.getElementById('rating').addEventListener('blur', function() {
        const rating = parseInt(this.value);
        if (this.value && (isNaN(rating) || rating < 1 || rating > 100)) {
            showFieldError('rating', 'Rating must be between 1 and 100');
        } else {
            clearFieldError('rating');
        }
    });

    // Market value validation
    document.getElementById('market_value').addEventListener('blur', function() {
        const value = parseFloat(this.value);
        if (this.value && (isNaN(value) || value < 0)) {
            showFieldError('market_value', 'Market value must be a positive number');
        } else {
            clearFieldError('market_value');
        }
    });

    // Photo file validation
    document.getElementById('photo').addEventListener('change', function() {
        if (this.files[0]) {
            validatePhotoFile(this.files[0]);
        }
    });

    // Name validation
    document.getElementById('name').addEventListener('blur', function() {
        if (this.value.trim().length < 2) {
            showFieldError('name', 'Name must be at least 2 characters long');
        } else {
            clearFieldError('name');
        }
    });
}

/**
 * Show error for a specific field
 */
function showFieldError(fieldId, message) {
    const field = document.getElementById(fieldId);
    const formGroup = field.closest('.form-group');
    
    // Add error styling
    field.style.borderColor = '#dc3545';
    field.style.boxShadow = '0 0 0 3px rgba(220, 53, 69, 0.1)';
    
    // Remove existing error message
    const existingError = formGroup.querySelector('.error-message');
    if (existingError) {
        existingError.remove();
    }
    
    // Add error message
    const errorDiv = document.createElement('div');
    errorDiv.className = 'error-message';
    errorDiv.style.color = '#dc3545';
    errorDiv.style.fontSize = '0.875rem';
    errorDiv.style.marginTop = '0.25rem';
    errorDiv.textContent = message;
    formGroup.appendChild(errorDiv);
}

/**
 * Clear error for a specific field
 */
function clearFieldError(fieldId) {
    const field = document.getElementById(fieldId);
    const formGroup = field.closest('.form-group');
    
    // Remove error styling
    field.style.borderColor = '#e9ecef';
    field.style.boxShadow = 'none';
    
    // Remove error message
    const errorMessage = formGroup.querySelector('.error-message');
    if (errorMessage) {
        errorMessage.remove();
    }
}

/**
 * Clear all error styles from the form
 */
function clearErrorStyles() {
    const fields = ['name', 'age', 'team', 'position', 'height', 'rating', 'market_value', 'photo'];
    
    fields.forEach(fieldId => {
        clearFieldError(fieldId);
    });
    
    // Remove validation summary if exists
    const existingSummary = document.querySelector('.validation-summary');
    if (existingSummary) {
        existingSummary.remove();
    }
}

/**
 * Show validation error summary
 */
function showValidationSummary(errors) {
    // Remove existing summary
    const existingSummary = document.querySelector('.validation-summary');
    if (existingSummary) {
        existingSummary.remove();
    }
    
    // Create error summary
    const summaryDiv = document.createElement('div');
    summaryDiv.className = 'validation-summary alert alert-error';
    summaryDiv.innerHTML = `
        <strong>Please correct the following errors:</strong>
        <ul style="margin: 0.5rem 0 0 1rem;">
            ${errors.map(error => `<li>${error}</li>`).join('')}
        </ul>
    `;
    
    // Insert at the top of the form
    const form = document.getElementById('athleteForm');
    form.insertBefore(summaryDiv, form.firstChild);
    
    // Scroll to top of form
    summaryDiv.scrollIntoView({ behavior: 'smooth', block: 'start' });
}

/**
 * Show success message
 */
function showSuccessMessage(message) {
    console.log('✅ ' + message);
    
    // You can add visual success feedback here if needed
    const submitButton = document.querySelector('button[type="submit"]');
    const originalText = submitButton.textContent;
    
    submitButton.textContent = '✅ Submitting...';
    submitButton.disabled = true;
    
    // Re-enable after a short delay (will be submitted anyway)
    setTimeout(() => {
        submitButton.textContent = originalText;
        submitButton.disabled = false;
    }, 2000);
}

/**
 * Utility function to format market value with commas
 */
function formatMarketValue() {
    const marketValueField = document.getElementById('market_value');
    
    marketValueField.addEventListener('input', function() {
        // Remove non-numeric characters
        let value = this.value.replace(/[^0-9]/g, '');
        
        // Add commas for thousands
        if (value) {
            value = parseInt(value).toLocaleString();
            this.value = value;
        }
    });
}

// Initialize market value formatting
document.addEventListener('DOMContentLoaded', function() {
    formatMarketValue();
});

/**
 * Show loading state during form submission
 */
function showLoadingState() {
    const submitButton = document.querySelector('button[type="submit"]');
    submitButton.innerHTML = '⏳ Registering Athlete...';
    submitButton.disabled = true;
}

// Console log for debugging
console.log('🏆 Rwanda Football Registry - Form Validation Loaded Successfully!');