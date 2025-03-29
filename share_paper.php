<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    echo "You must be logged in to share papers.";
    exit;
}

$paper_id = $_POST['paper_id'];  // The paper ID passed via POST

// Fetch paper details to generate the share link
$query = "SELECT title FROM papers WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $paper_id);
$stmt->execute();
$result = $stmt->get_result();
$paper = $result->fetch_assoc();

if ($paper) {
    // Generate the shareable link with the full URL
    $share_link = "http://yourwebsite.com/paper.php?id=" . $paper_id;
    $subject = urlencode("Check out this paper: " . $paper['title']);
    $body = urlencode("I found this paper interesting: " . $share_link);

    // Return a mailto link for sharing the paper via email
    echo "Share this paper: <a href='mailto:?subject=$subject&body=$body'>Email this paper</a>";
} else {
    echo "Paper not found.";
}

$stmt->close();
$conn->close();
?>
