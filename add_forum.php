<?php
session_start();
require_once 'db.php';

// Check if the user is logged in and has the 'admin' role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login_form.php');
    exit();
}

if (isset($_POST['create_forum'])) {
    // Debugging to check if the form is being submitted
    echo "Form submitted with title: " . $_POST['title'];
    // Commenting out the database insert to test form submission
    // $title = $_POST['title'];
    // $query = "INSERT INTO forums (title) VALUES (?)";
    // $stmt = $conn->prepare($query);
    // $stmt->bind_param("s", $title);
    // $stmt->execute();
    
    // Redirecting manually for testing
    // header('Location: admindash.php');
    // exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Forum</title>
</head>
<body>
    <h1>Create New Forum</h1>

    <form action="add_forum.php" method="POST">
        <label for="title">Forum Title:</label>
        <input type="text" name="title" required>
        <button type="submit" name="create_forum">Create Forum</button>
    </form>
</body>
</html>
