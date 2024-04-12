<?php
include_once ('../settings/config.php');

$searchResults = [];

if (isset($_GET['query'])) {
    $searchQuery = mysqli_real_escape_string($connection, $_GET['query']);

    // Query to search for photographers based on username
    $sql = "SELECT * FROM Users WHERE user_type = 'photographer' AND username LIKE '%$searchQuery%'";
    $result = mysqli_query($connection, $sql);
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $photographerId = $row['user_id'];
            $sessionsSql = "SELECT * FROM Sessions WHERE photographer_id = '$photographerId' AND status = 'open' AND session_id NOT IN (SELECT session_id FROM Bookings)";
            $sessionsResult = mysqli_query($connection, $sessionsSql);
            $sessions = [];
            while ($sessionRow = mysqli_fetch_assoc($sessionsResult)) {
                $sessions[] = $sessionRow;
            }

            $row['sessions'] = $sessions;
            $searchResults[] = $row;
        }
    }
}

mysqli_close($connection);
header('Content-Type: application/json');
echo json_encode($searchResults);
?>
