
<?php
session_start();
header('Content-Type: application/json');

// Check if user is logged in by verifying the session
if (isset($_SESSION['user_id'])) {
    echo json_encode(["loggedIn" => true]);  // Return true if logged in
} else {
    echo json_encode(["loggedIn" => false]); // Return false if not logged in
}
?>
