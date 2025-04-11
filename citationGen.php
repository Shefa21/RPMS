<?php
include 'db.php';
 // Add this function in your citationGen.php
function getDOIFromTitleAndAuthor($title, $author) {
    // Prepare search query for the CrossRef API
    $title = urlencode($title);
    $author = urlencode($author);

    $url = "https://api.crossref.org/works?query.title=$title&query.author=$author";
    
    // Initialize cURL session to send the request
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "User-Agent: RPMS/1.0 (mailto:your-email@domain.com)"
    ]);
    
    $response = curl_exec($ch);
    curl_close($ch);
    
    // If response is not empty
    if (!empty($response)) {
        $data = json_decode($response, true);
        
        // Check if the response contains any works
        if (isset($data['message']['items'][0]['DOI'])) {
            // Return the DOI if found
            return $data['message']['items'][0]['DOI'];
        }
    }
    
    return null; // Return null if DOI not found
}


    function getCitationFromDOI($doi) {
        global $conn;
    
        // Check if DOI already exists in the citations table
        $stmt = $conn->prepare("SELECT paper_id, citation_format FROM citations WHERE doi = ?");
        $stmt->bind_param("s", $doi);
        $stmt->execute();
        $stmt->store_result();
    
        if ($stmt->num_rows > 0) {
            // If citation exists, return the stored citation
            $stmt->bind_result($paper_id, $citation);
            $stmt->fetch();
            return $citation;
        } else {
            // Fetch paper ID from the papers table
            $stmt = $conn->prepare("SELECT id FROM papers WHERE doi = ?");
            $stmt->bind_param("s", $doi);
            $stmt->execute();
            $stmt->store_result();
    
            if ($stmt->num_rows > 0) {
                $stmt->bind_result($paper_id);
                $stmt->fetch();
            } else {
                // If the paper is not found, insert a placeholder entry
                $stmt = $conn->prepare("INSERT INTO papers (title, authors, category, file_path, publication_date) 
                                        VALUES ('Unknown Title', 'Unknown Authors', 'Uncategorized', 'unknown.pdf', CURDATE())");
                $stmt->execute();
                $paper_id = $stmt->insert_id;
            }
    
            // Fetch citation from CrossRef API
            $url = "https://api.crossref.org/works/$doi/transform/application/x-bibtex";
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ["User-Agent: RPMS/1.0 (mailto:your-email@example.com)"]);
    
            $citation = curl_exec($ch);
            $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
    
            if ($http_status !== 200 || empty($citation)) {
                // If no valid citation is found, insert the default IEEE citation
                $citation = 'IEEE Citation Format (Placeholder)';
            }
    
            // Save the citation in the citations table
            $stmt = $conn->prepare("INSERT INTO citations (paper_id, doi, citation_format) VALUES (?, ?, ?)");
            $stmt->bind_param("iss", $paper_id, $doi, $citation);
            if (!$stmt->execute()) {
                // Log the error if the query fails
                file_put_contents('citation_error.log', "Error executing query: " . $stmt->error . "\n", FILE_APPEND);
                return "❌ Error: Unable to save citation.";
            }
    
            return $citation;
        }
    }
    

?>
