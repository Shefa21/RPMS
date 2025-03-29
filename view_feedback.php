<?php
// Include the database connection
include('db.php');

// Check if paper ID is passed via GET
if (!isset($_GET['paper_id']) || !is_numeric($_GET['paper_id'])) {
    echo "Invalid paper ID.";
    exit;
}

$paper_id = $_GET['paper_id'];

// Fetch paper details
$query = "SELECT * FROM papers WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $paper_id);
$stmt->execute();
$paper_result = $stmt->get_result();
$paper = $paper_result->fetch_assoc();

// Fetch feedback for the given paper
$query_feedback = "SELECT f.feedback, u.full_name FROM feedback f
                   JOIN users u ON f.user_id = u.id
                   WHERE f.paper_id = ?";
$stmt_feedback = $conn->prepare($query_feedback);
$stmt_feedback->bind_param("i", $paper_id);
$stmt_feedback->execute();
$feedback_result = $stmt_feedback->get_result();

$feedbacks = [];
while ($row = $feedback_result->fetch_assoc()) {
    $feedbacks[] = $row;
}

$stmt->close();
$stmt_feedback->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback for Paper: <?= htmlspecialchars($paper['title']) ?></title>
    <link rel="stylesheet" href="rdashstyles.css"> <!-- Add your styles -->
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="vdash.php">Home</a></li>
                <li><a href="profile.php">Profile</a></li>
                <li><a href="view_paper.php">Your Papers</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section class="paper-details">
            <h2><?= htmlspecialchars($paper['title']) ?></h2>
            <p><strong>Authors:</strong> <?= htmlspecialchars($paper['authors']) ?></p>
            <p><strong>Published:</strong> <?= htmlspecialchars($paper['publication_date']) ?></p>
            <p><strong>Abstract:</strong> <?= htmlspecialchars($paper['abstract']) ?></p>

            <h3>Feedback for this Paper:</h3>
            <ul>
                <?php if (count($feedbacks) === 0): ?>
                    <li>No feedback available.</li>
                <?php else: ?>
                    <?php foreach ($feedbacks as $feedback): ?>
                        <li><strong><?= htmlspecialchars($feedback['full_name']) ?>:</strong> <?= htmlspecialchars($feedback['feedback']) ?></li>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>
        </section>
    </main>

    <footer>
        <p>Research Paper Management System</p>
    </footer>
</body>
</html>
