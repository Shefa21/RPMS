<?php
session_start();  // Start the session to access user_id
include 'db.php';  // Database connection

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('You must be logged in to delete papers.'); window.location.href='login.php';</script>";
    exit;
}

$user_id = $_SESSION['user_id'];  // Get the user ID from the session

// If a specific paper is requested for deletion
if (isset($_GET['paper_id'])) {
    $paper_id = $_GET['paper_id'];

    // Query to check if the paper belongs to the logged-in user
    $sql = "SELECT * FROM papers WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $paper_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Paper exists and belongs to the user, proceed with deletion
        $paper = $result->fetch_assoc();
        
        // Delete the paper's file from the server
        if (file_exists($paper['file_path'])) {
            unlink($paper['file_path']);
        }

        // Delete the paper from the database
        $delete_sql = "DELETE FROM papers WHERE id = ? AND user_id = ?";
        $delete_stmt = $conn->prepare($delete_sql);
        $delete_stmt->bind_param("ii", $paper_id, $user_id);
        
        if ($delete_stmt->execute()) {
            echo "<script>alert('Paper deleted successfully!'); window.location.href='delete_paper.php';</script>";
        } else {
            echo "<script>alert('Error deleting the paper.'); window.location.href='delete_paper.php';</script>";
        }

        $delete_stmt->close();
    } else {
        echo "<script>alert('Paper not found or not accessible!'); window.location.href='delete_paper.php';</script>";
    }

    $stmt->close();
} else {
    // Display all papers by the user for deletion
    $sql = "SELECT * FROM papers WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $papers_result = $stmt->get_result();

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Research Paper</title>
    <link rel="stylesheet" href="delete_paper.css">
</head>
<body>
    <div class="container">
        <h2>Delete Research Paper</h2>
        
        <?php if (isset($papers_result)): ?>
            <!-- Display all papers by the user with a delete option -->
            <div class="papers-list">
                <?php while ($paper = $papers_result->fetch_assoc()): ?>
                    <div class="paper-item">
                        <h3><?php echo htmlspecialchars($paper['title']); ?></h3>
                        <p><strong>Authors:</strong> <?php echo htmlspecialchars($paper['authors']); ?></p>
                        <p><strong>Category:</strong> <?php echo htmlspecialchars($paper['category']); ?></p>
                        <p><strong>Publication Date:</strong> <?php echo date("F j, Y", strtotime($paper['publication_date'])); ?></p>
                        
                        <!-- Link to delete the paper -->
                        <a href="?paper_id=<?php echo $paper['id']; ?>" class="delete-link" onclick="return confirm('Are you sure you want to delete this paper?');">Delete Paper</a>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php endif; ?>

        <br>
        <a href="management.php" class="back-link">Back to Management</a>
    </div>
</body>
</html>
