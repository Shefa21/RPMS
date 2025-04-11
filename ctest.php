<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require 'citationGen.php';
include 'db.php'; // Ensure database connection is included

$paper_id = "";
$doi = "";
$error_message = "";
$success_message = "";
$citation = "";
$papers = [];
$search_done = false; // Track if a search was performed

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
    } else {
        $search_done = true; // Set to true when search is successful
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
            $stmt = $conn->prepare("INSERT INTO citations (paper_id, doi) VALUES (?, ?)");
            $stmt->bind_param("is", $paper_id, $doi);
            $stmt->execute();
            $success_message = "✅ Citation saved successfully!";
        } else {
            $success_message = "⚠️ Citation already exists.";
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
            font-family: 'Arial', sans-serif;
            background-color:white;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-image: url('citpic.webp');
            background-size: cover; /* Ensures the image covers the entire background */
    background-position: center; /* Centers the image */
    background-repeat: no-repeat; /* Prevents the image from repeating */
             background-attachment: fixed; 
            
        }
        .container {
            background: rgb(140, 190, 185,.85);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 600px;
            text-align: center;
            opacity:  opacity: 0.5;
        }
        h1 {
            color: #333;
        }
        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
        }
        input, select, button {
            width: 100%;
            padding: 10px;
            margin-top: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }
        button {
            background-color: rgb(203, 109, 173);
            color: white;
            font-weight: bold;
            border: none;
            cursor: pointer;
            width: 180px;
        }
        button:hover {
            background-color: #0056b3;
        }
        .message {
            padding: 15px;
            border-radius: 5px;
            margin-top: 15px;
            font-size: 16px;
            font-weight: bold;
        }
        .success-message {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .error-message {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .citation-container {
            background: #eef6ff;
            padding: 20px;
            border-radius: 8px;
            margin-top: 20px;
            border-left: 5px solid #007bff;
            text-align: left;
        }
        pre {
            background: #e6e6e6;
            padding: 10px;
            border-radius: 5px;
            overflow-x: auto;
            font-size: 14px;
        }
        .back-btn {
    position: fixed;
    bottom: 20px;
    right: 20px;
    font-size: 18px;
    padding: 8px 12px;
    background-color: #2a4e6c;
    color: white;
    text-decoration: none;
    border-radius: 5px;
    transition: 0.3s;
}

    </style>
</head>
<body>
    <div class="container">
        <h1>Research Paper Citation</h1>

        <!-- Search Paper Form (Only show if no search has been done) -->
        <?php if (!$search_done): ?>
            <form method="POST">
                <label for="title">Enter Paper Title:</label>
                <input type="text" name="title" id="title" required>

                <label for="author">Enter Author Name:</label>
                <input type="text" name="author" id="author" required>

                <button type="submit" name="search_paper">Search Paper</button>
            </form>
        <?php endif; ?>

        <!-- Display Search Results -->
        <?php if (!empty($papers)): ?>
            <h3>Search Results:</h3>
            <form method="POST">
                <select name="paper_id" required onchange="fetchDOI(this.value)">
                    <option value="">-- Select Paper --</option>
                    <?php foreach ($papers as $paper): ?>
                        <option value="<?= $paper['id']; ?>" data-doi="<?= $paper['doi'] ?? ''; ?>">
                            <?= htmlspecialchars($paper['title']); ?> (<?= htmlspecialchars($paper['authors']); ?>)
                        </option>
                    <?php endforeach; ?>
                </select>

                <label for="doi">Enter DOI:</label>
                <input type="text" name="doi" id="doi" required>

                <button type="submit" name="generate_citation">Generate Citation</button>
            </form>
        <?php endif; ?>

        <!-- Show error messages -->
        <?php if (!empty($error_message)): ?>
            <div class="message error-message"> <?= $error_message; ?> </div>
        <?php endif; ?>

        <!-- Show success messages -->
        <?php if (!empty($success_message)): ?>
            <div class="message success-message"> <?= $success_message; ?> </div>
        <?php endif; ?>

        <!-- Display Citation -->
        <?php if (!empty($citation)): ?>
            <div class="citation-container">
                <h2>Citation (BibTeX Format):</h2>
                <pre><?= htmlspecialchars($citation); ?></pre>
            </div>
        <?php endif; ?>
    </div>
    <div class="back-button-container">
                    <a href="rdash.html" class="back-btn">Back</a>
                </div>
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
