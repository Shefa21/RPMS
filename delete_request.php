<?php
session_start();
require_once 'db.php';

// Check if the user is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login_form.php');
    exit();
}

// Get the request ID
if (isset($_GET['id'])) {
    $request_id = $_GET['id'];

    // Delete the request from the database
    $query = "DELETE FROM research_requests WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $request_id);

    if ($stmt->execute()) {
        // Redirect back to the admin dashboard
        header('Location: admindash.php');
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>
