// Modal functionality
function openModal(modalId) {
    document.getElementById(modalId).style.display = 'block';
}

function closeModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
}

// Close modal when clicking outside of it
window.onclick = function(event) {
    const modals = document.getElementsByClassName('modal');
    for (let i = 0; i < modals.length; i++) {
        if (event.target == modals[i]) {
            modals[i].style.display = 'none';
        }
    }
}


// Notification system
function showNotification(message, type = 'info', duration = 3000) {
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.classList.add('fade-out');
        setTimeout(() => {
            notification.remove();
        }, 500);
    }, duration);
}

// Mobile menu toggle
function toggleMobileMenu() {
    const navLinks = document.querySelector('.nav-links');
    navLinks.classList.toggle('active');
}



// Form validation helper
function validateForm(formId, rules) {
    const form = document.getElementById(formId);
    if (!form) return true;
    
    let isValid = true;
    
    for (const field in rules) {
        const element = form.querySelector(`[name="${field}"]`);
        if (!element) continue;
        
        const value = element.value.trim();
        const fieldRules = rules[field];
        
        if (fieldRules.required && !value) {
            isValid = false;
            showError(element, 'Este campo es requerido');
        } else if (fieldRules.email && !validateEmail(value)) {
            isValid = false;
            showError(element, 'Por favor ingrese un email válido');
        } else if (fieldRules.minLength && value.length < fieldRules.minLength) {
            isValid = false;
            showError(element, `Este campo debe tener al menos ${fieldRules.minLength} caracteres`);
        } else if (fieldRules.matchWith) {
            const matchElement = form.querySelector(`[name="${fieldRules.matchWith}"]`);
            if (matchElement && value !== matchElement.value.trim()) {
                isValid = false;
                showError(element, 'Los valores no coinciden');
            }
        }
    }
    
    return isValid;
}

function showError(element, message) {
    const errorElement = document.createElement('div');
    errorElement.className = 'error-message';
    errorElement.textContent = message;
    errorElement.style.color = '#e74c3c';
    errorElement.style.fontSize = '14px';
    errorElement.style.marginTop = '5px';
    
    const parent = element.parentElement;
    const existingError = parent.querySelector('.error-message');
    
    if (existingError) {
        existingError.textContent = message;
    } else {
        parent.appendChild(errorElement);
    }
}

function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}

// Initialize all forms with data-validation attribute
document.querySelectorAll('form[data-validation]').forEach(form => {
    form.addEventListener('submit', function(e) {
        if (!validateForm(this.id, window[this.dataset.validation])) {
            e.preventDefault();
        }
    });
});