<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require 'citationGen.php';
include 'db.php'; // Ensure database connection is included

$paper_id = "";
$doi = "";
$error_message = "";
$citation = "";
$papers = [];
$success_message = "";

// Handle search request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["search_paper"])) {
    $title = trim($_POST["title"]);
    $author = trim($_POST["author"]);

    // Search for paper by title and author in the papers table
    $stmt = $conn->prepare("SELECT id, title, authors FROM papers WHERE title LIKE ? AND authors LIKE ?");
    $searchTitle = "%" . $title . "%";
    $searchAuthor = "%" . $author . "%";
    $stmt->bind_param("ss", $searchTitle, $searchAuthor);
    $stmt->execute();
    
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $papers[] = $row;
    }

    if (empty($papers)) {
        $error_message = "❌ No matching paper found. Please check the title and author.";
    }
}

// Handle DOI and citation generation
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["generate_citation"])) {
    if (!empty($_POST["paper_id"]) && !empty($_POST["doi"])) {
        $paper_id = intval($_POST["paper_id"]);
        $doi = trim($_POST["doi"]);

        // Check if DOI already exists before inserting
        $stmt = $conn->prepare("SELECT doi FROM citations WHERE doi = ?");
        $stmt->bind_param("s", $doi);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows == 0) {
            // Insert DOI into citations table
            $stmt = $conn->prepare("INSERT INTO citations (paper_id, doi) VALUES (?, ?)");
            $stmt->bind_param("is", $paper_id, $doi);
            $stmt->execute();

            // Set success message
            $success_message = "✔️ Citation successfully saved!";
        }

        // Generate Citation
        $citation = getCitationFromDOI($doi);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Research Paper Citation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
        }
        .citation-container, .error-message, .success-message {
            border-radius: 8px;
            padding: 20px;
            margin-top: 20px;
        }
        .citation-container {
            border: 1px solid #ddd;
            background-color: #f9f9f9;
        }
        .error-message {
            border: 1px solid #ff4d4d;
            background-color: #ffcccc;
            color: #cc0000;
            font-weight: bold;
        }
        .success-message {
            border: 1px solid #4CAF50;
            background-color: #d4edda;
            color: #155724;
            font-weight: bold;
        }
        pre {
            background-color: #e6e6e6;
            padding: 15px;
            border-radius: 5px;
            font-size: 1.1em;
            white-space: pre-wrap;
            word-wrap: break-word;
        }
        select, input, button {
            padding: 10px;
            font-size: 16px;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <h1>Research Paper Citation</h1>

    <!-- Search Paper Form -->
    <form method="POST">
        <label for="title">Enter Paper Title:</label>
        <input type="text" name="title" id="title" required>

        <label for="author">Enter Author Name:</label>
        <input type="text" name="author" id="author" required>

        <button type="submit" name="search_paper">Search Paper</button>
    </form>

    <!-- Display Search Results -->
    <?php if (!empty($papers)): ?>
        <h3>Search Results:</h3>
        <form method="POST">
            <select name="paper_id" required onchange="fetchDOI(this.value)">
                <option value="">-- Select Paper --</option>
                <?php foreach ($papers as $paper): ?>
                    <option value="<?= $paper['id']; ?>" data-doi="<?= $paper['doi'] ?? ''; ?>">
                        <?= htmlspecialchars($paper['title']); ?> (Author: <?= htmlspecialchars($paper['authors']); ?>)
                    </option>
                <?php endforeach; ?>
            </select>

            <br><br>
            <label for="doi">Enter DOI:</label>
            <input type="text" name="doi" id="doi" required>

            <button type="submit" name="generate_citation">Generate Citation</button>
        </form>
    <?php elseif (!empty($error_message)): ?>
        <div class="error-message"><?= $error_message; ?></div>
    <?php endif; ?>

    <!-- Display Success Message -->
    <?php if (!empty($success_message)): ?>
        <div class="success-message"><?= $success_message; ?></div>
    <?php endif; ?>

    <!-- Display Citation -->
    <?php if (!empty($citation)): ?>
    <div class="citation-container">
        <h2>Citation (BibTeX Format):</h2>
        <pre><?= htmlspecialchars($citation); ?></pre>
    </div>
    <?php endif; ?>

    <script>
        function fetchDOI(paperId) {
            var selectedOption = document.querySelector(`option[value="${paperId}"]`);
            var doiField = document.getElementById('doi');

            if (selectedOption && selectedOption.getAttribute('data-doi')) {
                doiField.value = selectedOption.getAttribute('data-doi');
            } else {
                doiField.value = "";
            }
        }
    </script>

</body>
</html>
