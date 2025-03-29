<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In - Research Paper Management System</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <nav>
        <a href="#"><img src="logo.jpg" alt="logo"></a>
    </nav>
    <div class="form-wrapper">
        <div class="form-container">
            <h2>Sign In</h2>

            <!-- Error Message Display -->
            <?php if (isset($_SESSION['error'])): ?>
                <div class="error">
                    <?php 
                        echo $_SESSION['error']; 
                        unset($_SESSION['error']); // Clear error after displaying
                    ?>
                </div>
            <?php endif; ?>

            <form id="loginForm" action="login.php" method="POST">
                <div class="form-control">
                    <input type="text" name="email" required>
                    <label>Email</label>
                </div>
                <div class="form-control">
                    <input type="password" name="password" required>
                    <label>Password</label>
                </div>
                <button type="submit">Sign In</button>
            </form>            
            <p>New here? <a href="signup_form.php">Sign up now</a></p>
        </div>
    </div>
</body>
</html>
