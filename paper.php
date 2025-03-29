<?php
// Include the database connection
include('db.php');

// Check if paper ID is passed via GET
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "Invalid paper ID.";
    exit;
}

$paper_id = $_GET['id'];

// Fetch paper details
$query = "SELECT * FROM papers WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $paper_id);
$stmt->execute();
$paper_result = $stmt->get_result();
$paper = $paper_result->fetch_assoc();

$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($paper['title']) ?></title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="rdash.php">Dashboard</a></li>
                <li><a href="profile.php">Profile</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section class="paper-details">
            <h1><?= htmlspecialchars($paper['title']) ?></h1>
            <p><strong>Authors:</strong> <?= htmlspecialchars($paper['authors']) ?></p>
            <p><strong>Published:</strong> <?= htmlspecialchars($paper['publication_date']) ?></p>
            <p><strong>Abstract:</strong> <?= nl2br(htmlspecialchars($paper['abstract'])) ?></p>

            <h3>Feedback for this Paper:</h3>
            <!-- Feedback display will go here -->
            <ul>
                <!-- Feedback list will be populated dynamically -->
            </ul>
        </section>
    </main>

    <footer>
        <p>Research Paper Management System</p>
    </footer>
</body>
</html>
