<?php
include 'db.php';

if (isset($_GET['paper_id'])) {
    $paper_id = $_GET['paper_id'];

    $sql = "SELECT * FROM papers WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $paper_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        echo json_encode($row);  // Return paper details as JSON
    } else {
        echo json_encode(["error" => "Paper not found"]);
    }

    $stmt->close();
    $conn->close();
}
?>
