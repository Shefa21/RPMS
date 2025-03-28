<?php
include 'db.php';

// Function to sanitize input data
function sanitize_input($data) {
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}

// If the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $paper_id = $_POST['paper_id']; // Hidden field in form
    $title = sanitize_input($_POST['title']);
    $authors = $_POST['authors']; // Array of authors
    $category = sanitize_input($_POST['category']);
    $abstract = sanitize_input($_POST['abstract']);

    // Convert authors array to a comma-separated string
    $authors_str = implode(", ", $authors);

    // Handle date fields
    $day = $_POST['day'];
    $month = $_POST['month'];
    $year = $_POST['year'];
    $publication_date = "$year-$month-$day"; // Convert to YYYY-MM-DD format

    // Handle file upload (if a new file is uploaded)
    if (!empty($_FILES['file']['name'])) {
        $uploadDir = "uploads/"; // Ensure this directory exists and is writable
        $fileName = uniqid() . "_" . basename($_FILES["file"]["name"]); // Unique file name to prevent conflicts
        $targetFilePath = $uploadDir . $fileName;
        $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

        // Validate file type (PDF only)
        if ($fileType != "pdf") {
            echo "<script>alert('Only PDF files are allowed!'); window.history.back();</script>";
            exit;
        }

        // Validate file size (max 10MB)
        if ($_FILES['file']['size'] > 30000000) { // 30MB
            echo "<script>alert('File size exceeds the 30MB limit!'); window.history.back();</script>";
            exit;
        }

        // Move the uploaded file
        if (move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath)) {
            // Update database with new file path
            $sql = "UPDATE papers SET title=?, authors=?, category=?, abstract=?, publication_date=?, file_path=? WHERE id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssssi", $title, $authors_str, $category, $abstract, $publication_date, $targetFilePath, $paper_id);
        } else {
            echo "<script>alert('File upload failed!'); window.history.back();</script>";
            exit;
        }
    } else {
        // If no new file is uploaded, update other fields only
        $sql = "UPDATE papers SET title=?, authors=?, category=?, abstract=?, publication_date=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssi", $title, $authors_str, $category, $abstract, $publication_date, $paper_id);
    }

    // Execute the query
    if ($stmt->execute()) {
        echo "<script>alert('Paper updated successfully!'); window.location.href='management.php';</script>";
    } else {
        echo "<script>alert('Error updating paper.'); window.history.back();</script>";
    }

    $stmt->close();
    $conn->close();
}

// Check if paper_id is passed in the URL
if (isset($_GET['paper_id'])) {
    $paper_id = $_GET['paper_id'];

    // Query to fetch the paper data from the database
    $sql = "SELECT * FROM papers WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $paper_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if paper exists
    if ($result->num_rows > 0) {
        $paper = $result->fetch_assoc();
    } else {
        // If paper doesn't exist, redirect to management page with an error message
        echo "<script>alert('Paper not found!'); window.location.href='management.php';</script>";
        exit;
    }
} else {
    // Redirect if no paper_id is passed
    echo "<script>alert('No paper ID provided!'); window.location.href='management.php';</script>";
    exit;
}
?>
<ul>
    <!-- Loop through papers and display links to edit them -->
    <?php if (!empty($papers)): ?>
        <?php foreach ($papers as $paper): ?>
            <li>
                <a href="edit.php?paper_id=<?php echo $paper['id']; ?>">Edit: <?php echo htmlspecialchars($paper['title']); ?></a>
            </li>
        <?php endforeach; ?>
    <?php else: ?>
        <li>No papers available.</li>
    <?php endif; ?>
</ul>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Research Paper</title>
    <link rel="stylesheet" href="edit.css">
</head>
<body>
    <div class="container">
        <h2>Edit Research Paper</h2>
        <form id="editPaperForm" action="edit.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="paper_id" value="<?php echo $paper['id']; ?>">

            <label for="title">Paper Title:</label>
            <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($paper['title']); ?>" required>

            <label for="authors">Author(s) Name:</label>
            <div id="authorFields">
                <?php
                // Convert authors string back to an array
                $authors = explode(", ", $paper['authors']);
                foreach ($authors as $author) {
                    echo '<input type="text" name="authors[]" value="' . htmlspecialchars($author) . '" required>';
                }
                ?>
            </div>
            <button type="button" id="addAuthor">Add Other Author</button>

            <label for="category">Research Field:</label>
            <select id="category" name="category" required>
                <option value="">Select Category</option>
                <option value="Computer Science" <?php if ($paper['category'] == 'Computer Science') echo 'selected'; ?>>Computer Science</option>
                <option value="Engineering" <?php if ($paper['category'] == 'Engineering') echo 'selected'; ?>>Engineering</option>
                <option value="Mathematics" <?php if ($paper['category'] == 'Mathematics') echo 'selected'; ?>>Mathematics</option>
                <option value="Biology" <?php if ($paper['category'] == 'Biology') echo 'selected'; ?>>Biology</option>
                <option value="Quantum Physics" <?php if ($paper['category'] == 'Quantum Physics') echo 'selected'; ?>>Quantum Physics</option>
                <option value="Data Science" <?php if ($paper['category'] == 'Data Science') echo 'selected'; ?>>Data Science</option>
                <option value="Machine Learning" <?php if ($paper['category'] == 'Machine Learning') echo 'selected'; ?>>Machine Learning</option>
                <option value="Physics" <?php if ($paper['category'] == 'Physics') echo 'selected'; ?>>Physics</option>
            </select>

            <label for="file">Upload New Paper (PDF only):</label>
            <input type="file" id="file" name="file" accept=".pdf">

            <label for="date">Publication Date:</label>
            <div id="dateFields">
                <select name="day" id="day" required>
                    <option value="">Day</option>
                    <?php
                    for ($i = 1; $i <= 31; $i++) {
                        $selected = ($i == date('d', strtotime($paper['publication_date']))) ? 'selected' : '';
                        echo "<option value='$i' $selected>$i</option>";
                    }
                    ?>
                </select>
                <select name="month" id="month" required>
                    <option value="">Month</option>
                    <?php
                    $months = ['01' => 'January', '02' => 'February', '03' => 'March', '04' => 'April', '05' => 'May', '06' => 'June', '07' => 'July', '08' => 'August', '09' => 'September', '10' => 'October', '11' => 'November', '12' => 'December'];
                    foreach ($months as $num => $name) {
                        $selected = ($num == date('m', strtotime($paper['publication_date']))) ? 'selected' : '';
                        echo "<option value='$num' $selected>$name</option>";
                    }
                    ?>
                </select>
                <select name="year" id="year" required>
                    <option value="">Year</option>
                    <?php
                    $currentYear = date('Y');
                    for ($i = $currentYear; $i >= 1900; $i--) {
                        $selected = ($i == date('Y', strtotime($paper['publication_date']))) ? 'selected' : '';
                        echo "<option value='$i' $selected>$i</option>";
                    }
                    ?>
                </select>
            </div>

            <label for="abstract">Abstract:</label>
            <textarea id="abstract" name="abstract" rows="4" required><?php echo htmlspecialchars($paper['abstract']); ?></textarea>

            <button type="submit">Save Changes</button>
        </form>
        <br>
        <a id="cancel" href="management.php"><b>Cancel</b></a>
    </div>

    <script src="edit.js"></script>
</body>
</html>
