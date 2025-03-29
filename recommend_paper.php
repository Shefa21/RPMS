<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    echo "You must be logged in to recommend papers.";
    exit;
}

$user_id = $_SESSION['user_id'];
$paper_id = $_POST['paper_id'];  // The paper ID passed via POST

// Check if the paper is already recommended
$query = "SELECT * FROM recommended_papers WHERE user_id = ? AND paper_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $user_id, $paper_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "You have already recommended this paper.";
} else {
    // Recommend the paper
    $query = "INSERT INTO recommended_papers (user_id, paper_id) VALUES (?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $user_id, $paper_id);
    if ($stmt->execute()) {
        echo "Paper recommended successfully!";
    } else {
        echo "Error recommending paper: " . $stmt->error;
    }
}
$stmt->close();
$conn->close();
?>
