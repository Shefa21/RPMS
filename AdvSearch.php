<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "rpms";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

include 'db_connect.php'; // Database connection

if (isset($_GET['query'])) {
    $search = htmlspecialchars($_GET['query']); // Prevent XSS
    $param = "%$search%";

    $limit = 5; // Number of results per page
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $start = ($page - 1) * $limit;

    // Get total number of matching results
    $count_sql = "SELECT COUNT(*) FROM papers WHERE 
                  title LIKE ? OR 
                  authors LIKE ? OR 
                  category LIKE ? OR 
                  abstract LIKE ?";
    $count_stmt = $conn->prepare($count_sql);
    $count_stmt->bind_param("ssss", $param, $param, $param, $param);
    $count_stmt->execute();
    $count_stmt->bind_result($total_results);
    $count_stmt->fetch();
    $count_stmt->close();

    // Fetch results with pagination
    $sql = "SELECT * FROM papers WHERE 
            title LIKE ? OR 
            authors LIKE ? OR 
            category LIKE ? OR 
            abstract LIKE ? 
            LIMIT ?, ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssii", $param, $param, $param, $param, $start, $limit);
    $stmt->execute();
    $result = $stmt->get_result();

    echo "<div class='results'>";
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<div class='paper'>";
            echo "<h3>" . htmlspecialchars($row['title']) . "</h3>";
            echo "<p><strong>Authors:</strong> " . htmlspecialchars($row['authors']) . "</p>";
            echo "<p><strong>Category:</strong> " . htmlspecialchars($row['category']) . "</p>";
            echo "<p><strong>Abstract:</strong> " . htmlspecialchars(substr($row['abstract'], 0, 150)) . "...</p>";
            echo "<a href='" . htmlspecialchars($row['file_path']) . "' target='_blank'>Read Paper</a>";
            echo "</div>";
        }
    } else {
        echo "<p>No results found.</p>";
    }
    echo "</div>";

    // Pagination
    $total_pages = ceil($total_results / $limit);
    if ($total_pages > 1) {
        echo "<div class='pagination'>";
        for ($i = 1; $i <= $total_pages; $i++) {
            echo "<a href='search.php?query=" . urlencode($search) . "&page=$i'>$i</a> ";
        }
        echo "</div>";
    }
}
?>
