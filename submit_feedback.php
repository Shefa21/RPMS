<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    echo "You must be logged in to submit feedback.";
    exit;
}

$paper_id = $_POST['paper_id'];  // The paper ID passed via POST
$feedback = $_POST['feedback'];  // The feedback message

$user_id = $_SESSION['user_id']; // Assuming the logged-in user's ID is stored in session

// Insert the feedback into the database
$query = "INSERT INTO feedback (paper_id, user_id, feedback, created_at) 
          VALUES (?, ?, ?, NOW())";
$stmt = $conn->prepare($query);
$stmt->bind_param("iis", $paper_id, $user_id, $feedback);

if ($stmt->execute()) {
    echo "Thank you for your feedback!";
} else {
    echo "Error submitting feedback. Please try again later.";
}

$stmt->close();
$conn->close();
?>
