<?php
include 'db.php';

$topic_id = $_GET['topic_id']; // The topic ID from the URL or a dynamic source
$result = mysqli_query($conn, "SELECT * FROM discussion_forums WHERE topic_id = $topic_id ORDER BY created_at DESC");

while ($row = mysqli_fetch_assoc($result)) {
    $user_result = mysqli_query($conn, "SELECT full_name FROM users WHERE id = " . $row['user_id']);
    $user = mysqli_fetch_assoc($user_result);

    echo "<strong>" . $user['full_name'] . ":</strong> " . $row['comment'] . "<br>";
}
?>
