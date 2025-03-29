<?php
session_start();
$conn = new mysqli("localhost", "root", "", "rpms");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $role = $_POST['role'];
    $password = $_POST['password'];

    // Check if email already exists
    $check_query = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $check_query->bind_param("s", $email);
    $check_query->execute();
    $result = $check_query->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['error'] = "Email already registered. Please sign in.";
        header("Location: signup_form.php");
        exit();
    }

    // Hash password for security
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert user into database
    $stmt = $conn->prepare("INSERT INTO users (full_name, email, role, password) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $role, $hashed_password);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Sign-up successful! You can now log in.";
        header("Location: login_form.php");
        exit();
    } else {
        $_SESSION['error'] = "Error: " . $stmt->error;
        header("Location: signup_form.php");
        exit();
    }

    $stmt->close();
}

$conn->close();
?>
