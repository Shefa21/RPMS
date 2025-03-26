<?php
session_start();
include 'db.php'; // Database connection

// Handle delete request (AJAX)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'])) {
    $user_id = intval($_POST['user_id']);

    // Prevent admin from deleting themselves
    if ($user_id == $_SESSION['user_id']) {
        echo "You cannot delete yourself!";
        exit;
    }

    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);

    if ($stmt->execute()) {
        echo "User deleted successfully.";
    } else {
        echo "Error deleting user.";
    }

    $stmt->close();
    $conn->close();
    exit; // Stop execution to prevent the page from reloading
}

// Fetch users (except admins)
$result = $conn->query("SELECT id, full_name, email, role FROM users WHERE role != 'admin'");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Researchers</title>
    <link rel="stylesheet" href="delete_user.css"> <!-- Add a CSS file if needed -->
</head>
<body>
    <h2>Manage Researchers</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Full Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Action</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr id="user-<?php echo $row['id']; ?>">
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['full_name']; ?></td>
                <td><?php echo $row['email']; ?></td>
                <td><?php echo ucfirst($row['role']); ?></td>
                <td>
                    <button onclick="deleteUser(<?php echo $row['id']; ?>)">Delete</button>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>

    <script>
        function deleteUser(userId) {
            if (confirm("Are you sure you want to delete this user?")) {
                fetch('delete_user.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: 'user_id=' + userId
                })
                .then(response => response.text())
                .then(data => {
                    alert(data);
                    if (data.includes("successfully")) {
                        document.getElementById('user-' + userId).remove();
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        }
    </script>
</body>
</html>

<?php
$conn->close();
?>
