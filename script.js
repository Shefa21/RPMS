// Form validation for sign-up
function validatePassword(password) {
    // Checks if password meets the requirements
    return password.length >= 8 &&
           /[A-Z]/.test(password) &&  // At least one uppercase letter
           /[a-z]/.test(password) &&  // At least one lowercase letter
           /[0-9]/.test(password) &&  // At least one number
           /[^A-Za-z0-9]/.test(password); // At least one special character
}

// Sign-Up Form Handling
const signupForm = document.getElementById('signupForm');
if (signupForm) {
    signupForm.addEventListener('submit', (e) => {
        e.preventDefault();  // Prevent form from submitting immediately

        const password = document.getElementById('password').value;  // Get password value
        const confirmPassword = document.getElementById('confirm_password').value;  // Get confirm password value

        // Check if password meets the requirements
        if (!validatePassword(password)) {
            alert("Password must be at least 8 characters, include uppercase, lowercase, a number, and a special character.");
            return;
        }

        // Check if the passwords match
        if (password !== confirmPassword) {
            alert("Passwords do not match!");
            return;
        }

        // If all checks pass, submit the form
        signupForm.submit();  // Submit the form
    });
}


// Login Form Handling
const loginForm = document.getElementById('loginForm');
if (loginForm) {
    loginForm.addEventListener('submit', (e) => {
        e.preventDefault();
        
        console.log("Login successful!");
        // Redirect user based on role after successful login (placeholder)
        window.location.href = "vdash.html"; // Update this based on the role
    });
}
