<?php
session_start(); // Start the session

include("../settings/auto.php");
// Retrieve user ID from session
$user_id = $_SESSION['user_id'];

// Database connection
include_once('../settings/config.php');

// Retrieve user's information
$sql_user_info = "SELECT * FROM Users WHERE user_id = ?";
$stmt_user_info = $connection->prepare($sql_user_info);
$stmt_user_info->bind_param("i", $user_id);
$stmt_user_info->execute();
$result_user_info = $stmt_user_info->get_result();

if ($result_user_info->num_rows > 0) {
    $user_info = $result_user_info->fetch_assoc();
}

// Get filter criteria from user input
$location = isset($_GET['location']) ? $_GET['location'] : '';
$date = isset($_GET['date']) ? $_GET['date'] : '';
$priceMin = isset($_GET['priceMin']) ? $_GET['priceMin'] : '';
$priceMax = isset($_GET['priceMax']) ? $_GET['priceMax'] : '';

// Construct the SQL query for filtering sessions
$current_date = date("Y-m-d");
$sql_filter_sessions = "SELECT s.session_id, s.session_name, s.description, s.date, s.location, s.price, u.username
                        FROM Sessions s
                        INNER JOIN Users u ON s.photographer_id = u.user_id
                        WHERE s.status = 'open' 
                        AND s.date >= '$current_date'
                        AND s.session_id NOT IN (SELECT session_id FROM Bookings WHERE user_id = $user_id)";

// Add filter conditions to the SQL query
if (!empty($location)) {
    $sql_filter_sessions .= " AND s.location LIKE '%$location%'";
}
if (!empty($date)) {
    $sql_filter_sessions .= " AND s.date = '$date'";
}
if (!empty($priceMin) && !empty($priceMax)) {
    $sql_filter_sessions .= " AND s.price BETWEEN $priceMin AND $priceMax";
}

// Execute the SQL query
$result_filtered_sessions = $connection->query($sql_filter_sessions);

// Check if there are any filtered sessions
if (!$result_filtered_sessions) {
    die("Error fetching filtered sessions: " . $connection->error);
}

// Fetch all filtered sessions into an array
$filtered_sessions = [];
if ($result_filtered_sessions->num_rows > 0) {
    while ($row = $result_filtered_sessions->fetch_assoc()) {
        $filtered_sessions[] = $row;
    }
}
?>
