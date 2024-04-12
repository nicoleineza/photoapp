<?php
session_start();
include("../settings/auto.php");
include_once('../settings/config.php');

$current_date = $_POST['currentDate'];
$locationFilter = isset($_POST['location']) ? $_POST['location'] : '';
$dateFilter = isset($_POST['date']) ? $_POST['date'] : '';
$priceMinFilter = isset($_POST['priceMin']) ? $_POST['priceMin'] : '';
$priceMaxFilter = isset($_POST['priceMax']) ? $_POST['priceMax'] : '';
$sql_active_sessions = "SELECT s.session_id, s.session_name, s.description, s.date, s.location, s.price, u.username
                        FROM Sessions s
                        INNER JOIN Users u ON s.photographer_id = u.user_id
                        WHERE s.status = 'open'
                        AND s.date >= '$current_date'
                        AND s.session_id NOT IN (
                            SELECT session_id FROM Bookings
                        )";

if (!empty($locationFilter)) {
    $sql_active_sessions .= " AND s.location LIKE '%$locationFilter%'";
}
if (!empty($dateFilter)) {
    $sql_active_sessions .= " AND s.date = '$dateFilter'";
}
if (!empty($priceMinFilter) && !empty($priceMaxFilter)) {
    $sql_active_sessions .= " AND s.price BETWEEN $priceMinFilter AND $priceMaxFilter";
}
$result_active_sessions = $connection->query($sql_active_sessions);

// Generate HTML for session listings
$html = '';
if ($result_active_sessions->num_rows > 0) {
    while($row_active_sessions = $result_active_sessions->fetch_assoc()) {
        // Append HTML for each session
        $html .= "<tr>
                    <td>".$row_active_sessions["session_name"]."</td>
                    <td>".$row_active_sessions["description"]."</td>
                    <td>".$row_active_sessions["date"]."</td>
                    <td>".$row_active_sessions["location"]."</td>
                    <td>".$row_active_sessions["price"]."</td>
                    <td>".$row_active_sessions["username"]."</td>
                    <td><button onclick='openBookModal(".$row_active_sessions["session_id"].")'>Book Now</button></td>
                </tr>";
    }
} else {
    // Output message for no sessions found
    $html = "<tr><td colspan='7'>No active sessions found.</td></tr>";
}
echo $html;
?>
