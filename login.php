<?php
session_start();
$conn = new mysqli("localhost", "root", "", "rpms");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Check if user exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];

            // Redirect based on role
            if ($user['role'] === 'admin') {
                header("Location: admindash.php");
            } elseif ($user['role'] === 'researcher') {
                header("Location: rdash.php");
            } else {
                header("Location: vdash.php");
            }
            exit();
        } else {
            $_SESSION['error'] = "Invalid email or password!";
        }
    } else {
        $_SESSION['error'] = "User not found! Please sign up.";
    }

    $stmt->close();
    $conn->close();
}

// Redirect back to login page if error exists
header("Location: login_form.php");
exit();
?>
