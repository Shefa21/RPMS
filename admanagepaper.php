<?php
session_start();
require_once 'db.php';

// Check if the user is logged in and has the 'admin' role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login_form.php');
    exit();
}

// Fetch all papers
$query = "SELECT id, title, authors, category, created_at FROM papers ORDER BY created_at DESC";
$stmt = $conn->prepare($query);

if ($stmt === false) {
    die("Error preparing the query: " . $conn->error); // Improved error message
}

$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Papers</title>
</head>
<body>
    <h1>Manage Papers</h1>
    
    <?php
    if ($result->num_rows > 0) {
        echo "<table>";
        echo "<thead><tr><th>Title</th><th>Authors</th><th>Category</th><th>Submitted On</th><th>Actions</th></tr></thead>";
        echo "<tbody>";

        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['title']) . "</td>";
            echo "<td>" . htmlspecialchars($row['authors']) . "</td>";
            echo "<td>" . htmlspecialchars($row['category']) . "</td>";
            echo "<td>" . htmlspecialchars($row['created_at']) . "</td>";
            echo "<td>
                    <form action='addeletepaper.php' method='POST'>
                        <input type='hidden' name='id' value='" . $row['id'] . "'>
                        <button type='submit' name='action' value='delete'>Delete</button>
                    </form>
                  </td>";
            echo "</tr>";
        }

        echo "</tbody>";
        echo "</table>";
    } else {
        echo "<p>No papers available in the database.</p>";
    }
    ?>

</body>
</html>
