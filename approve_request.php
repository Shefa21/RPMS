<?php
session_start();
require_once 'db.php';

// Check if the user is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login_form.php');
    exit();
}

// Update the status of all pending research requests to 'approved'
$query = "UPDATE research_requests SET status = 'approved' WHERE status = 'pending'";
$stmt = $conn->prepare($query);

if ($stmt === false) {
    die("Error preparing the query: " . $conn->error);  // Debugging line
}

if ($stmt->execute()) {
    // Redirect back to the admin dashboard
    header('Location: admindash.php');
    exit();
} else {
    echo "Error executing query: " . $stmt->error;  // Debugging line
}
?>
