<?php
// Include the database connection
include('db.php');

// Fetch papers from the database
$sql = "SELECT * FROM papers"; // Assuming you have a 'papers' table
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Store all papers in an array
    $papers = [];
    while ($row = $result->fetch_assoc()) {
        $papers[] = $row;
    }
} else {
    $papers = [];
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Researcher Dashboard</title>
    <link rel="stylesheet" href="rdashstyles.css"> <!-- Link to your styles -->
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="vdash.php">Home</a></li>
                <li><a href="upload.html">Upload Paper</a></li>
                <li><a href="profile.php">Profile</a></li>
                <li><a href="management.php">Manage Papers</a></li>
                <li id="authLink"><a href="login_form.php" class="login-btn">Login</a></li>
                <li id="authLink">
                    <!-- Login/Logout link will be dynamically changed using JS -->
                </li>
            </ul>
        </nav>
    </header>

    <main>
        <section class="dashboard-content">
            <h1>Welcome, Researcher</h1>

            <!-- Notifications Section -->
            <div id="notifications">
                <h2>Notifications</h2>
                <ul id="notificationList">
                    <li>New feedback received for your paper "XYZ".</li>
                    <li>Upcoming conference submission deadline: March 5th.</li>
                </ul>
            </div>

            <!-- Dashboard Content for Papers -->
            <div class="dashboard-options">
                <h2>Your Papers</h2>
                <?php if (count($papers) > 0): ?>
                    <?php foreach ($papers as $paper): ?>
                        <div class="paper" data-id="<?= $paper['id'] ?>"> 
                            <h3><a href="paper.php?id=<?= $paper['id'] ?>"><?= htmlspecialchars($paper['title']) ?></a></h3>
                            <p><strong>Authors:</strong> <?= htmlspecialchars($paper['authors']) ?></p>
                            <p><strong>Published:</strong> <?= htmlspecialchars($paper['publication_date']) ?></p>

                            <!-- View Feedback Button -->
                            <a href="view_feedback.php?paper_id=<?= $paper['id'] ?>" class="view-feedback-btn">View Feedback</a>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No papers available.</p>
                <?php endif; ?>
            </div>

            <!-- Section for additional actions -->
            <div class="dashboard-actions">
                <button id="collaborateBtn">Collaborate with Reviewers</button>
                <button id="exploreOpportunitiesBtn">Explore Journals</button>
            </div>
        </section>
    </main>

    <footer>
        <p>Research Paper Management System</p>
    </footer>

    <script src="rdashscript.js"></script> <!-- Your JS file -->
</body>
</html>
