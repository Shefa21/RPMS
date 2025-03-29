<?php
session_start();
require_once 'db.php';  // Include the database connection file

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ensure the user is logged in
    if (!isset($_SESSION['user_id'])) {
        header('Location: login_form.php');
        exit();
    }

    // Get the topic from the form input
    $topic = $_POST['topic'];
    $user_id = $_SESSION['user_id'];  // Get the logged-in user's ID

    // Prepare and execute the database insert query
    $query = "INSERT INTO research_requests (topic, user_id) VALUES (?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('si', $topic, $user_id);

    if ($stmt->execute()) {
        // Redirect to a confirmation page or dashboard after successful request
        header('Location: vdash.html');
        exit();
    } else {
        // Handle database insertion failure
        echo "Error: " . $stmt->error;
    }
}
?>
