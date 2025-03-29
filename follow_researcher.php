<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    echo "You must be logged in to follow researchers.";
    exit;
}

$researcher_id = $_POST['researcher_id'];  // Get the researcher ID from the POST request

// Check if the researcher exists in the users table
$query = "SELECT id FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $researcher_id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows == 0) {
    echo "The researcher you're trying to follow doesn't exist.";
    exit;
}

// Check if the user is already following the researcher
$query = "SELECT id FROM follows WHERE user_id = ? AND researcher_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $_SESSION['user_id'], $researcher_id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo "You are already following this researcher.";
    exit;
}

// Insert into follows table
$query = "INSERT INTO follows (user_id, researcher_id) VALUES (?, ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $_SESSION['user_id'], $researcher_id);
if ($stmt->execute()) {
    echo "Successfully followed the researcher!";
} else {
    echo "Error following researcher.";
}

$stmt->close();
$conn->close();
?>
