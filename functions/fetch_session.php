<?php
function fetchPhotographerSessions($connection, $photographer_id) {
    $sessions = array(); 

    $query = "SELECT * FROM Sessions WHERE photographer_id = ?";
    if ($data = $connection->prepare($query)) {
        $data->bind_param("i", $photographer_id);
        // Execute the query
        $data->execute();
        // Get result
        $result = $data->get_result();
        
        // Fetch rows and populate sessions array
        while ($row = $result->fetch_assoc()) {
            $sessions[] = $row;
        }

        // Close statement
        $data->close();
    } else {
        // Print error message if the query preparation fails
        echo "Error preparing query: " . $connection->error;
    }

    // Return the array of sessions
    return $sessions;
}
?>
