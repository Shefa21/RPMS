<?php
session_start();
require_once 'db.php';

// Check if the user is logged in and has the 'admin' role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login_form.php');
    exit();
}

if (isset($_POST['add_conference'])) {
    $title = $_POST['title'];
    $date = $_POST['date'];
    $description = $_POST['description'];
    
    $query = "INSERT INTO conferences (title, date, description) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sss", $title, $date, $description);
    $stmt->execute();
    
    header('Location: admindash.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Conference</title>
</head>
<body>
    <h1>Add Upcoming Conference</h1>

    <form action="add_conference.php" method="POST">
        <label for="title">Conference Title:</label>
        <input type="text" name="title" required>
        <label for="date">Conference Date:</label>
        <input type="date" name="date" required>
        <label for="description">Conference Description:</label>
        <textarea name="description" required></textarea>
        <button type="submit" name="add_conference">Add Conference</button>
    </form>
</body>
</html>
