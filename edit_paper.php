<?php
// Include your database connection file
include 'db.php';

// Fetch all papers from the database
$sql = "SELECT id, title FROM papers";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Create an array to store the papers
    $papers = [];
    while($row = $result->fetch_assoc()) {
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
    <title>Manage Papers</title>
    <link rel="stylesheet" href="management.css">
</head>
<body>

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
        <li><a href="edit.php">Edit Paper</a></li>
        
    </ul>

    <button id="back"><a href="rdash.html">Back To Dashboard</a></button>

</body>
</html>
