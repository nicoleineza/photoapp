<?php
// Function to fetch photographer's sessions
function fetchPhotographerSessions($connection, $photographer_id) {
    $sessions = array(); // Initialize an empty array to store sessions

    // Prepare and execute query to fetch sessions for the given photographer
    $query = "SELECT * FROM Sessions WHERE photographer_id = ?";
    if ($stmt = $connection->prepare($query)) {
        // Bind parameters
        $stmt->bind_param("i", $photographer_id);
        // Execute the query
        $stmt->execute();
        // Get result
        $result = $stmt->get_result();
        
        // Fetch rows and populate sessions array
        while ($row = $result->fetch_assoc()) {
            $sessions[] = $row;
        }

        // Close statement
        $stmt->close();
    } else {
        // Print error message if the query preparation fails
        echo "Error preparing query: " . $connection->error;
    }

    // Return the array of sessions
    return $sessions;
}
?>
