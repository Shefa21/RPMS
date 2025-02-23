// DOM Elements
const inner = document.querySelector('.inner');
const showSignupButton = document.getElementById('showSignup');
const showLoginButton = document.getElementById('showLogin');
const loginForm = document.getElementById('loginForm');
const signupForm = document.getElementById('signupForm');
const googleSignInButton = document.getElementById('googleSignIn');
const googleSignUpButton = document.getElementById('googleSignUp');
const themeToggle = document.getElementById('themeToggle');

// Theme Toggle
function toggleTheme() {
    document.documentElement.classList.toggle('dark');
    localStorage.setItem('theme', document.documentElement.classList.contains('dark') ? 'dark' : 'light');
}

// Set initial theme
if (localStorage.getItem('theme') === 'light') {
    document.documentElement.classList.remove('dark');
}

themeToggle.addEventListener('click', toggleTheme);

// Toggle between login and signup
showSignupButton.addEventListener('click', () => {
    inner.classList.add('flipped');
});

showLoginButton.addEventListener('click', () => {
    inner.classList.remove('flipped');
});

// Form validation
function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}

function validatePassword(password) {
    return password.length >= 6;
}

function validateName(name) {
    return name.trim().length >= 2;
}

function showError(input, message) {
    const inputGroup = input.parentElement;
    const errorMessage = inputGroup.querySelector('.error-message');
    input.classList.add('error');
    errorMessage.textContent = message;
    inputGroup.classList.add('shake');
    setTimeout(() => {
        inputGroup.classList.remove('shake');
    }, 300);
}

function clearError(input) {
    const inputGroup = input.parentElement;
    const errorMessage = inputGroup.querySelector('.error-message');
    input.classList.remove('error');
    errorMessage.textContent = '';
}

// Google Sign In
function handleGoogleSignIn() {
    // Here you would typically integrate with Google OAuth
    console.log('Google Sign In clicked');
}

googleSignInButton.addEventListener('click', handleGoogleSignIn);
googleSignUpButton.addEventListener('click', handleGoogleSignIn);

// Login form submission
loginForm.addEventListener('submit', (e) => {
    e.preventDefault();
    let isValid = true;
    
    const email = document.getElementById('loginEmail');
    const password = document.getElementById('loginPassword');

    clearError(email);
    clearError(password);

    if (!validateEmail(email.value)) {
        showError(email, 'Please enter a valid email');
        isValid = false;
    }

    if (!validatePassword(password.value)) {
        showError(password, 'Password must be at least 6 characters');
        isValid = false;
    }

    if (isValid) {
        // Here you would typically send the data to your server
        console.log('Login form submitted', {
            email: email.value,
            password: password.value
        });
    }
});

// Signup form submission
signupForm.addEventListener('submit', (e) => {
    e.preventDefault();
    let isValid = true;

    const name = document.getElementById('signupName');
    const email = document.getElementById('signupEmail');
    const password = document.getElementById('signupPassword');

    clearError(name);
    clearError(email);
    clearError(password);

    if (!validateName(name.value)) {
        showError(name, 'Please enter your full name');
        isValid = false;
    }

    if (!validateEmail(email.value)) {
        showError(email, 'Please enter a valid email');
        isValid = false;
    }

    if (!validatePassword(password.value)) {
        showError(password, 'Password must be at least 6 characters');
        isValid = false;
    }

    if (isValid) {
        // Here you would typically send the data to your server
        console.log('Signup form submitted', {
            name: name.value,
            email: email.value,
            password: password.value
        });
    }
});

// Add placeholder support for floating labels
document.querySelectorAll('input').forEach(input => {
    input.setAttribute('placeholder', ' ');
});