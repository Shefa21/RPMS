<?php
include 'db.php';

// Get the filter parameters from the AJAX request
$search = isset($_GET['search']) ? $_GET['search'] : '';
$author = isset($_GET['author']) ? $_GET['author'] : '';
$year = isset($_GET['year']) ? $_GET['year'] : '';
$domain = isset($_GET['domain']) ? $_GET['domain'] : '';
$category = isset($_GET['category']) ? $_GET['category'] : '';

// Start building the query
$sql = "SELECT * FROM papers WHERE 1=1";

// Add conditions based on the filters
if (!empty($search)) {
    $sql .= " AND (title LIKE ? OR abstract LIKE ?)";
}

if (!empty($author)) {
    $sql .= " AND authors LIKE ?";
}

if (!empty($year)) {
    $sql .= " AND YEAR(publication_date) = ?";
}

if (!empty($domain)) {
    $sql .= " AND category LIKE ?";
}

if (!empty($category)) {
    $sql .= " AND category = ?";
}

$stmt = $conn->prepare($sql);

// Bind parameters dynamically based on the filters
if (!empty($search) && !empty($author) && !empty($year) && !empty($domain) && !empty($category)) {
    $stmt->bind_param("ssssss", "%$search%", "%$search%", "%$author%", $year, "%$domain%", $category);
} elseif (!empty($search) && !empty($author) && !empty($year) && !empty($domain)) {
    $stmt->bind_param("ssss", "%$search%", "%$search%", "%$author%", $year);
} // Add other conditions based on the filters

// Execute the query
$stmt->execute();
$result = $stmt->get_result();

// Check if any papers match the criteria
if ($result->num_rows > 0) {
    while ($paper = $result->fetch_assoc()) {
        echo "<div class='paper-item'>";
        echo "<h3>" . htmlspecialchars($paper['title']) . "</h3>";
        echo "<p><strong>Authors:</strong> " . htmlspecialchars($paper['authors']) . "</p>";
        echo "<p><strong>Category:</strong> " . htmlspecialchars($paper['category']) . "</p>";
        echo "<p><strong>Publication Date:</strong> " . date("F j, Y", strtotime($paper['publication_date'])) . "</p>";
        echo "<p><strong>Abstract:</strong> " . nl2br(htmlspecialchars($paper['abstract'])) . "</p>";
        echo "<a href='" . htmlspecialchars($paper['file_path']) . "' target='_blank'>Download PDF</a>";
        echo "</div>";
    }
} else {
    echo "<p>No papers found matching your criteria.</p>";
}

$stmt->close();
$conn->close();
?>
