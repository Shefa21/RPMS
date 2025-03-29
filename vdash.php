<?php
// Include the database connection file
include 'db.php';

// Fetch top researchers from the database
$query_top_researchers = "SELECT full_name, researcher_score FROM users WHERE role = 'researcher' ORDER BY researcher_score DESC LIMIT 3";
$result_top_researchers = mysqli_query($conn, $query_top_researchers);

// Fetch upcoming conferences from the database
$query_upcoming_conferences = "SELECT title, date FROM conferences ORDER BY date ASC LIMIT 3";
$result_upcoming_conferences = mysqli_query($conn, $query_upcoming_conferences);

// Fetch featured papers from the database
$query_featured_papers = "SELECT title, authors, publication_date, id, file_path FROM papers WHERE featured = 1 LIMIT 3";
$result_featured_papers = mysqli_query($conn, $query_featured_papers);

// Fetch discussion forums from the database
$query_forums = "SELECT title FROM forums LIMIT 3";
$result_forums = mysqli_query($conn, $query_forums);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Research Paper Management</title>
    <link rel="stylesheet" href="vdash.css">
    <script defer src="vdashscript.js"></script>
</head>
<body>
    <header>
        <div class="logo"><img src="logo.jpg" alt="Logo"></div>
        <div class="Profile" id="profile"><a href="profile.php" class="profile-btn">Profile</a></div>
    </header>

    <div class="hero-section">
        <h1>Explore the world of research, connect with experts, and discover groundbreaking ideas</h1>
        <div class="search-bar">
            <input type="text" placeholder="Search research papers, authors...">
            <button>Search</button>
        </div>
        <div class="advanced-search">
            <a href="SearchFilter.html">Advanced Search</a>
        </div>
    </div>

    <main>
        <section class="sidebar">
            <div class="card">
                <h2>🔥 Trending Topics</h2>
                <ul>
                    <?php
                    // Fetching distinct research topics (categories) from the papers table
                    $query = "SELECT DISTINCT category FROM papers WHERE category IS NOT NULL";
                    $result = mysqli_query($conn, $query);

                    // Check if the query was successful
                    if (!$result) {
                        die("Query failed: " . mysqli_error($conn));
                    }

                    // Display topics as links
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<li><a href='search_results.php?category=" . urlencode($row['category']) . "'>" . $row['category'] . "</a></li>";
                    }
                    ?>
                </ul>
            </div>

            <div class="card">
                <h2>🏆 Top Researchers</h2>
                <ul>
                    <?php while ($row = mysqli_fetch_assoc($result_top_researchers)): ?>
                        <li><a href="#"><?= htmlspecialchars($row['full_name']) ?> - <?= htmlspecialchars($row['researcher_score']) ?> points</a></li>
                    <?php endwhile; ?>
                </ul>
            </div>

            <div class="card">
                <h2>📅 Upcoming Conferences</h2>
                <ul>
                    <?php while ($row = mysqli_fetch_assoc($result_upcoming_conferences)): ?>
                        <li><a href="#"><?= htmlspecialchars($row['title']) ?> - <?= htmlspecialchars($row['date']) ?></a></li>
                    <?php endwhile; ?>
                </ul>
            </div>
        </section>

        <section class="main-content">
    <div class="card featured">
        <h2>⭐ Featured Research Papers</h2>
        <?php while ($row = mysqli_fetch_assoc($result_featured_papers)): ?>
            <div class="paper" data-id="<?= $row['id'] ?>"> <!-- Added data attributes -->
                <h3><a href="paper.php?id=<?= $row['id'] ?>"><?= htmlspecialchars($row['title']) ?></a></h3>
                <p><strong>Authors:</strong> <?= htmlspecialchars($row['authors']) ?></p>
                <p><strong>Published:</strong> <?= htmlspecialchars($row['publication_date']) ?></p>

                <!-- Check if the file path is not empty and show the download link -->
                <?php if (!empty($row['file_path'])): ?>
                    <a href="<?= $row['file_path'] ?>" target="_blank" class="download-btn">Download PDF</a>
                <?php endif; ?>

                <button class="save-btn">❤️ Save</button>
                <button class="follow-btn">Follow</button>
                <button class="recommend-btn">Recommend</button>
                <button class="feedback-btn">Feedback</button>
                <button class="share-btn">Share</button>
            </div>
        <?php endwhile; ?>
    </div>
</section>


        <section class="sidebar-right">
            <div class="card">
                <h2>💬 Discussion Forums</h2>
                <ul>
                    <?php while ($row = mysqli_fetch_assoc($result_forums)): ?>
                        <li><a href="forum.php?id=<?= $row['id'] ?>"><?= htmlspecialchars($row['title']) ?></a></li>
                    <?php endwhile; ?>
                </ul>
            </div>

            <!-- Request Research form -->
            <div class="card">
                <h2>📑 Request Research</h2>
                <form action="submit_request.php" method="POST">
                    <input type="text" name="topic" placeholder="Request a research topic..." required>
                    <button type="submit">Submit Request</button>
                </form>
            </div>
        </section>
    </main>
</body>
</html>
