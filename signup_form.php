<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Research Paper Management System</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <nav>
        <a href="#"><img src="logo.jpg" alt="logo"></a>
    </nav>
    
    <div class="form-wrapper">
        <div class="form-container">
            <h2>Sign Up</h2>

            <!-- Display Error Message -->
            <?php if (isset($_SESSION['error'])): ?>
                <div class="error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
            <?php endif; ?>

            <form id="signupForm" action="signup.php" method="POST">
                <div class="form-control">
                    <input type="text" name="name" required>
                    <label>Full Name</label>
                </div>
                <div class="form-control">
                    <input type="email" name="email" required>
                    <label>Email</label>
                </div>
                <div class="form-control">
                    <select name="role" required>
                        <option value="" disabled selected>Select Role</option>
                        <option value="admin">Admin</option>
                        <option value="researcher">Researcher</option>
                        <option value="viewer">Viewer</option>
                    </select>
                </div>
                <div class="form-control">
                    <input type="password" name="password" id="password" required>
                    <label>Password</label>
    
                </div>
                <div class="form-control">
                    <input type="password" name="confirm_password" id="confirm_password" required>
                    <label>Confirm Password</label>
                </div>
                <button type="submit">Sign Up</button>
            </form>
            
            <p>Already have an account? <a href="login_form.php">Sign In</a></p>
        </div>
    </div>

    <script src="script.js"></script> <!-- Your external JavaScript file -->
</body>
</html>
