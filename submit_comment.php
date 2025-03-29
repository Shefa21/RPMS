<?php
include 'db.php';

session_start();
if (!isset($_SESSION['user_id'])) {
    die("You must be logged in to comment.");
}

$user_id = $_SESSION['user_id'];
$comment = mysqli_real_escape_string($conn, $_POST['comment']);
$topic_id = $_POST['topic_id'];

$sql = "INSERT INTO discussion_forums (topic_id, user_id, comment) 
        VALUES ('$topic_id', '$user_id', '$comment')";

if (mysqli_query($conn, $sql)) {
    echo "Your comment has been submitted.";
} else {
    echo "Error: " . mysqli_error($conn);
}
?>
