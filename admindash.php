<?php
session_start();
require_once 'db.php';

// Check if the user is logged in and has the 'admin' role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login_form.php');
    exit();
}

// Fetch all researchers for selection
$query_all_researchers = "SELECT id, full_name, researcher_score FROM users WHERE role = 'researcher' ORDER BY researcher_score DESC";
$result_all_researchers = $conn->query($query_all_researchers);

// Fetch all papers for selection
$query_all_papers = "SELECT id, title, authors FROM papers";
$result_all_papers = $conn->query($query_all_papers);

// Fetch all conferences for selection
$query_all_conferences = "SELECT id, title, date FROM conferences";
$result_all_conferences = $conn->query($query_all_conferences);

// Fetch all forums
$query_forums = "SELECT id, title FROM forums";
$result_forums = $conn->query($query_forums);

// Handle form submissions for selections
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['top_researchers'])) {
        $selected_researchers = $_POST['top_researchers'];
        $update_query = "UPDATE users SET top_researcher = 0";
        $conn->query($update_query);
        foreach ($selected_researchers as $researcher_id) {
            $conn->query("UPDATE users SET top_researcher = 1 WHERE id = $researcher_id");
        }
    }
    if (isset($_POST['featured_papers'])) {
        $selected_papers = $_POST['featured_papers'];
        $update_query = "UPDATE papers SET featured = 0";
        $conn->query($update_query);
        foreach ($selected_papers as $paper_id) {
            $conn->query("UPDATE papers SET featured = 1 WHERE id = $paper_id");
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="admindash.css">
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="vdash.php">Home</a></li>
                <li><a href="manage_requests.php">Manage Research Topics</a></li>
                <li><a href="admanagepaper.php">Manage Papers</a></li>
                <li><a href="delete_user.php">Manage Researchers</a></li>
                <li><a href="profile.php">Profile</a></li>
                <li id="authLink"><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <h1>Welcome, Admin</h1>

        <!-- Select Top Researchers -->
        <section id="selectTopResearchers">
            <h2>Select Top Researchers</h2>
            <form method="POST">
                <select name="top_researchers[]" multiple>
                    <?php while ($row = $result_all_researchers->fetch_assoc()): ?>
                        <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['full_name']) ?> - <?= htmlspecialchars($row['researcher_score']) ?> points</option>
                    <?php endwhile; ?>
                </select>
                <button type="submit">Update</button>
            </form>
        </section>

        <!-- Select Featured Papers -->
        <section id="selectFeaturedPapers">
            <h2>Select Featured Papers</h2>
            <form method="POST">
                <select name="featured_papers[]" multiple>
                    <?php while ($row = $result_all_papers->fetch_assoc()): ?>
                        <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['title']) ?> by <?= htmlspecialchars($row['authors']) ?></option>
                    <?php endwhile; ?>
                </select>
                <button type="submit">Update</button>
            </form>
        </section>

        <!-- Discussion Forums -->
        <section id="discussionForums">
            <h2>Discussion Forums</h2>
            <ul>
                <?php while ($row = $result_forums->fetch_assoc()): ?>
                    <li><a href="forum.php?id=<?= $row['id'] ?>"><?= htmlspecialchars($row['title']) ?></a></li>
                <?php endwhile; ?>
            </ul>
        </section>

        <!-- Dashboard Options -->
        <div class="dashboard-options">
            <button id="addConferenceBtn"><a href="add_conference.php">Add Conference</a></button>
            <button id="addForumBtn"><a href="add_forum.php">Add forum</a></button>
        </div>
    </main>

    <footer>
        <p>Research Paper Management System</p>
    </footer>

    <script src="admindash.js"></script>
</body>
</html>
