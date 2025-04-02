<?php


function getCitationFromDOI($doi) {
    global $conn;

    // Check if DOI exists in the papers table
    $stmt = $conn->prepare("SELECT id FROM papers WHERE doi = ?");
    $stmt->bind_param("s", $doi);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($paper_id);
        $stmt->fetch();
        return getCitationForPaper($paper_id, $doi);
    } else {
        return "Error: This DOI does not exist in the database.";
    }
}

function getCitationForPaper($paper_id, $doi) {
    global $conn;
    
    // Check if citation already exists
    $stmt = $conn->prepare("SELECT citation_format FROM citations WHERE paper_id = ? AND doi = ?");
    $stmt->bind_param("is", $paper_id, $doi);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($citation);
        $stmt->fetch();
        return $citation; // Return the saved citation
    }
    
    // Fetch citation from CrossRef API
    $url = "https://api.crossref.org/works/$doi/transform/application/x-bibtex";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "User-Agent: RPMS/1.0 (mailto:your-email@domain.com)"
    ]);
    
    $citation = curl_exec($ch);
    curl_close($ch);
    
    // Save citation if successfully retrieved
    if (!empty($citation)) {
        $stmt = $conn->prepare("INSERT INTO citations (paper_id, doi, citation_format) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $paper_id, $doi, $citation);
        $stmt->execute();
    }
    
    return $citation ?: "Error: Unable to fetch citation.";
}
?>
