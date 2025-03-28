<?php
session_start(); // Start the session to access user_id
include 'db.php'; // Database connection

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "You must be logged in to upload papers.";
    exit;
}

$user_id = $_SESSION['user_id'];  // Get the user ID from the session

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if required fields are provided
    if (empty($_POST['title']) || empty($_POST['authors']) || empty($_POST['category']) || empty($_POST['abstract'])) {
        echo "All fields are required!";
        exit;
    }

    // Sanitize inputs
    $title = htmlspecialchars($_POST['title']);
    $authors = htmlspecialchars(implode(", ", $_POST['authors']));  // Convert authors array to string
    $category = htmlspecialchars($_POST['category']);
    $abstract = htmlspecialchars($_POST['abstract']);
    $day = $_POST['day'];
    $month = $_POST['month'];
    $year = $_POST['year'];
    $publication_date = "$year-$month-$day";  // Format date as YYYY-MM-DD

    // Handle file upload
    $file = $_FILES['file'];
    $targetDir = "uploads/";
    $targetFile = $targetDir . basename($file["name"]);
    $uploadOk = 1;
    $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
    $fileName = basename($file["name"]);
    $fileName = preg_replace("/[^a-zA-Z0-9\-_\.]/", "", $fileName);  // Sanitize file name
    $targetFile = $targetDir . $fileName;

    // Limit file size to 10MB
    if ($file["size"] > 20485760) {
        echo "Sorry, your file is too large. Maximum allowed size is 10MB.";
        $uploadOk = 0;
    }

    // Check if file is a PDF
    if ($fileType != "pdf") {
        echo "Sorry, only PDF files are allowed.";
        $uploadOk = 0;
    }

    // If everything is ok, try to upload file
    if ($uploadOk == 1) {
        if (move_uploaded_file($file["tmp_name"], $targetFile)) {
            // Insert data into the database, including user_id
            $stmt = $conn->prepare("INSERT INTO papers (title, authors, category, file_path, publication_date, abstract, user_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssssi", $title, $authors, $category, $targetFile, $publication_date, $abstract, $user_id);

            if ($stmt->execute()) {
                echo "Research paper submitted successfully.";
            } else {
                echo "Error: " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}

$conn->close();
?>
