<?php
include 'db.php';
// Fetching papers based on the selected category (topic)
if (isset($_GET['category'])) {
    $category = mysqli_real_escape_string($conn, $_GET['category']);
    $query = "SELECT * FROM papers WHERE category = '$category'";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        die("Query failed: " . mysqli_error($conn));
    }

    while ($row = mysqli_fetch_assoc($result)) {
        echo "<div class='paper'>";
        echo "<h3><a href='#'>" . $row['title'] . "</a></h3>";
        echo "<p><strong>Authors:</strong> " . $row['authors'] . "</p>";
        echo "<p><strong>Category:</strong> " . $row['category'] . "</p>";
        echo "<p><strong>Published:</strong> " . $row['publication_date'] . "</p>";
        echo "</div>";
    }
} else {
    echo "No papers found for the selected category.";
}
?>
