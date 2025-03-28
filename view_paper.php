<?php
session_start();  // Start the session to access user_id
include 'db.php';  // Database connection

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('You must be logged in to view papers.'); window.location.href='login.php';</script>";
    exit;
}

$user_id = $_SESSION['user_id'];  // Get the user ID from the session

// If a specific paper is requested, fetch its details
if (isset($_GET['paper_id'])) {
    $paper_id = $_GET['paper_id'];

    // Query to get paper details from the database
    $sql = "SELECT * FROM papers WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $paper_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $paper = $result->fetch_assoc();
    } else {
        echo "<script>alert('Paper not found or not accessible!'); window.location.href='view_paper.php';</script>";
        exit;
    }

    $stmt->close();
} else {
    // If no paper_id is set, display all papers by the user
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
    <title>View Research Paper</title>
    <link rel="stylesheet" href="view_paper.css">
</head>
<body>
    <div class="container">
        <h2>Research Papers</h2>
        
        <?php if (isset($papers_result)): ?>
            <!-- Display all papers by the user -->
            <div class="papers-list">
                <?php while ($paper = $papers_result->fetch_assoc()): ?>
                    <div class="paper-item">
                        <h3><a href="?paper_id=<?php echo $paper['id']; ?>"><?php echo htmlspecialchars($paper['title']); ?></a></h3>
                        <p><strong>Authors:</strong> <?php echo htmlspecialchars($paper['authors']); ?></p>
                        <p><strong>Category:</strong> <?php echo htmlspecialchars($paper['category']); ?></p>
                        <p><strong>Publication Date:</strong> <?php echo date("F j, Y", strtotime($paper['publication_date'])); ?></p>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php elseif (isset($paper)): ?>
            <!-- Display specific paper details -->
            <div class="paper-details">
                <h3>Title: <?php echo htmlspecialchars($paper['title']); ?></h3>
                <p><strong>Authors:</strong> <?php echo htmlspecialchars($paper['authors']); ?></p>
                <p><strong>Category:</strong> <?php echo htmlspecialchars($paper['category']); ?></p>
                <p><strong>Publication Date:</strong> <?php echo date("F j, Y", strtotime($paper['publication_date'])); ?></p>
                <p><strong>Abstract:</strong> <?php echo nl2br(htmlspecialchars($paper['abstract'])); ?></p>

                <?php if (!empty($paper['file_path'])): ?>
                    <p><strong>Download Paper:</strong> <a href="<?php echo $paper['file_path']; ?>" target="_blank">Download PDF</a></p>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <br>
        <a href="management.php" class="back-link">Back to Management</a>
    </div>
</body>
</html>
