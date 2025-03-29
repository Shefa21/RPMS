<?php
session_start();
require_once 'db.php';

// Check if the user is logged in and has the 'admin' role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login_form.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    $paper_id = $_POST['id'];

    // Prepare and execute the delete query
    $query = "DELETE FROM papers WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $paper_id);

    if ($stmt->execute()) {
        // Redirect back to the manage papers page after successful deletion
        header('Location: admanagepaper.php');
        exit();
    } else {
        echo "<p>Error deleting the paper. Please try again.</p>";
    }
}
?>
