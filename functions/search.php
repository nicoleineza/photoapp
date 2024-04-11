<?php
// Include database connection
include_once ('../settings/config.php');

// Initialize an empty array to store search results
$searchResults = [];

// Check if the search query is submitted
if (isset($_GET['query'])) {
    // Sanitize the search query to prevent SQL injection
    $searchQuery = mysqli_real_escape_string($connection, $_GET['query']);

    // Query to search for photographers based on username
    $sql = "SELECT * FROM Users WHERE user_type = 'photographer' AND username LIKE '%$searchQuery%'";
    $result = mysqli_query($connection, $sql);

    // Check if any photographers are found
    if (mysqli_num_rows($result) > 0) {
        // Fetch and store search results in an array
        while ($row = mysqli_fetch_assoc($result)) {
            // Get photographer's sessions that are not booked
            $photographerId = $row['user_id'];
            $sessionsSql = "SELECT * FROM Sessions WHERE photographer_id = '$photographerId' AND status = 'open' AND session_id NOT IN (SELECT session_id FROM Bookings)";
            $sessionsResult = mysqli_query($connection, $sessionsSql);
            $sessions = [];
            while ($sessionRow = mysqli_fetch_assoc($sessionsResult)) {
                $sessions[] = $sessionRow;
            }

            // Combine photographer's information with their sessions
            $row['sessions'] = $sessions;

            // Add photographer to search results
            $searchResults[] = $row;
        }
    }
}

// Close the database connection
mysqli_close($connection);

// Send search results as JSON response
header('Content-Type: application/json');
echo json_encode($searchResults);
?>
