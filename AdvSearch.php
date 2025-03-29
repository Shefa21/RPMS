<?php
include 'db_connect.php'; 

if (isset($_GET['query'])) {
    $search = htmlspecialchars($_GET['query']); 
    $param = "%$search%";

    $limit = 5; 
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $start = ($page - 1) * $limit;

   
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


<div class="back-button-container">
    <a href="vdash.html" class="back-btn"> DashBoard </a>
</div>


<style>
    body {
        
        font-family: Arial, sans-serif;
        background-color: white;
        color: #333;
        padding: 20px;
       background : #95c8d8;
       
    }

    .results {
        max-width: 800px;
        margin: 0 auto;
        background: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .paper {
        background: #fff;
        padding: 15px;
        border: 1px solid #ddd;
        margin-bottom: 15px;
        border-radius: 8px;
    }

    .paper h3 {
        font-size: 22px;
        color: #2980b9;
    }

    .paper p {
        font-size: 18px;
        line-height: 1.6;
    }

    .read-btn {
        display: inline-block;
        font-size: 18px;
        padding: 10px 15px;
        background-color: #3498db;
        color: white;
        text-decoration: none;
        border-radius: 5px;
        transition: 0.3s;
    }

    .read-btn:hover {
        background-color: #2980b9;
    }
    
.back-btn {
    position: fixed;
    bottom: 20px;
    right: 20px;
    display: inline-block;
    font-size: 16px;
    padding: 10px 15px;
    background-color:rgb(15, 106, 166);
    color: white;
    text-decoration: none;
    border-radius: 5px;
    transition: 0.3s;
}

.back-btn:hover {
    background-color:rgb(12, 94, 62);
}

</style>
