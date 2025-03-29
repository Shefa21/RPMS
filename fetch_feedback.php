<?php
// Include the database connection
include('db.php');

// Check if paper ID is passed via GET
if (!isset($_GET['paper_id']) || !is_numeric($_GET['paper_id'])) {
    echo json_encode([]);
    exit;
}

$paper_id = $_GET['paper_id'];

// Fetch feedback for the given paper
$query = "SELECT f.feedback, u.full_name FROM feedback f
          JOIN users u ON f.user_id = u.id
          WHERE f.paper_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $paper_id);
$stmt->execute();
$feedback_result = $stmt->get_result();

$feedbacks = [];
while ($row = $feedback_result->fetch_assoc()) {
    $feedbacks[] = $row;
}

$stmt->close();
$conn->close();

// Return feedback data as JSON
echo json_encode($feedbacks);
?>
