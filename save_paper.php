<?php
session_start();
include 'db.php'; // Include your database connection

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "You need to log in to save this paper.";
    exit;
}

$paper_id = $_POST['paper_id'];
$user_id = $_SESSION['user_id'];

// Example query to save paper (you can add your save logic, like updating a "saved_papers" table)
$query = "INSERT INTO saved_papers (paper_id, user_id) VALUES (?, ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $paper_id, $user_id);

if ($stmt->execute()) {
    echo "Paper saved successfully!";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
