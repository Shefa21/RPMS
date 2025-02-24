document.getElementById('showSignup').addEventListener('click', () => {
    document.getElementById('loginFormWrapper').classList.add('hidden');
    document.getElementById('signupFormWrapper').classList.remove('hidden');
});

document.getElementById('showLogin').addEventListener('click', () => {
    document.getElementById('signupFormWrapper').classList.add('hidden');
    document.getElementById('loginFormWrapper').classList.remove('hidden');
});

// Form validation
function validatePassword(password) {
    return password.length >= 8 &&
           /[A-Z]/.test(password) &&
           /[a-z]/.test(password) &&
           /[0-9]/.test(password) &&
           /[^A-Za-z0-9]/.test(password);
}

document.getElementById('signupForm').addEventListener('submit', (e) => {
    e.preventDefault();
    
    const password = document.getElementById('signupPassword').value;
    const confirmPassword = document.getElementById('confirmPassword').value;
    
    if (!validatePassword(password)) {
        alert("Password must be at least 8 characters, include uppercase, lowercase, a number, and a special character.");
        return;
    }
    
    if (password !== confirmPassword) {
        alert("Passwords do not match!");
        return;
    }

    console.log("Sign-up successful!");
});

document.getElementById('loginForm').addEventListener('submit', (e) => {
    e.preventDefault();
    
    console.log("Login successful!");
});
