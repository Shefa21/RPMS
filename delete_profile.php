<?php
session_start();
require 'db.php';  // Include database connection

if (!isset($_SESSION['user_id'])) {
    header("Location: login_form.php");  // Redirect if not logged in
    exit();
}

$user_id = $_SESSION['user_id'];

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Prepare and execute the delete query
    $deleteQuery = "DELETE FROM users WHERE id = ?";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->bind_param("i", $user_id);

    if ($stmt->execute()) {
        // Destroy the session and log out the user
        session_destroy();  
        header("Location: login_form.php");  // Redirect to login page
        exit();
    } else {
        echo "Error deleting profile!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Delete Profile</title>
    <link rel="stylesheet" href="profile.css">  <!-- Link to your CSS file -->
</head>
<body>
    <div class="container">
        <h2>Are you sure you want to delete your profile?</h2>
        <form method="POST">
            <button type="submit" style="background-color: red;">Yes, Delete My Profile</button>
            <a href="profile.php" style="margin-left: 10px; color: blue;">Cancel</a>
        </form>
    </div>
</body>
</html>
