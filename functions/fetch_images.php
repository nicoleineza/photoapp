function fetchPhotographerPhotos($connection, $photographer_id) {
    $photos = array(); // Initialize an empty array to store photos

    // Prepare and execute query to fetch photos for the given photographer
    $query = "SELECT * FROM Images WHERE photographer_id = ?";
    if ($stmt = $connection->prepare($query)) {
        // Bind parameters
        $stmt->bind_param("i", $photographer_id);
        // Execute the query
        $stmt->execute();
        // Get result
        $result = $stmt->get_result();
        
        // Fetch rows and populate photos array
        while ($row = $result->fetch_assoc()) {
            $photos[] = $row;
        }

        // Close statement
        $stmt->close();
    } else {
        // Print error message if the query preparation fails
        echo "Error preparing query: " . $connection->error;
    }

    // Return the array of photos
    return $photos;
}