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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Session Listings</title>
    <link rel="icon" href="../assets/appicon.png">
    <link rel="stylesheet" href="../css/sessions.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body><<div id="main-content">

    <div class="container">
        <h1>Session Listings</h1>
        <div class="filters">
            <label for="location">Location:</label>
            <input type="text" id="location">
            <label for="date">Date:</label>
            <input type="text" id="date">
            <label for="price">Price Range:</label>
            <input type="number" id="priceMin" placeholder="Min">
            <span>-</span>
            <input type="number" id="priceMax" placeholder="Max">
            <button id="filterBtn">Filter</button>
        </div>
        <table>
            <tr>
                <th>Title</th>
                <th>Description</th>
                <th>Date</th>
                <th>Location</th>
                <th>Price</th>
                <th>Photographer</th>
                <th></th>
            </tr>
            <?php
            $current_date = date("Y-m-d");
            $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
            $sql_active_sessions = "SELECT s.session_id, s.session_name, s.description, s.date, s.location, s.price, u.username
                                    FROM Sessions s
                                    INNER JOIN Users u ON s.photographer_id = u.user_id
                                    WHERE s.status = 'open' 
                                    AND s.date >= '$current_date'
                                    AND s.session_id NOT IN (SELECT session_id FROM Bookings WHERE user_id = $user_id)";
            $result_active_sessions = $connection->query($sql_active_sessions);

            if (!$result_active_sessions) {
                die("Error fetching active sessions: " . $connection->error);
            }

            if ($result_active_sessions->num_rows > 0) {
                while($row_active_sessions = $result_active_sessions->fetch_assoc()) {
                    echo "<tr>
                            <td>".$row_active_sessions["session_name"]."</td>
                            <td>".$row_active_sessions["description"]."</td>
                            <td>".$row_active_sessions["date"]."</td>
                            <td>".$row_active_sessions["location"]."</td>
                            <td>".$row_active_sessions["price"]."</td>
                            <td>".$row_active_sessions["username"]."</td>
                            <td><button onclick='bookSession(".$row_active_sessions["session_id"].")'>Book Now</button></td>
                        </tr>";
                }
            } else {
                echo "<tr><td colspan='7'>No active sessions found.</td></tr>";
            }
            ?>
        </table>
    </div>
    <div class="container">
        <h2>Sessions Booked by You</h2>
        <table>
            <tr>
                <th>Title</th>
                <th>Date</th>
                <th>Location</th>
                <th>Price</th>
                <th>Photographer</th>
                <th>Status</th>
                <th>Action</th>
            <?php
                $sql_booked_sessions = "SELECT s.session_name, s.date, s.location, s.price, u.username, b.status, b.booking_id
                                        FROM Sessions s
                                        INNER JOIN Users u ON s.photographer_id = u.user_id
                                        INNER JOIN Bookings b ON s.session_id = b.session_id
                                        WHERE b.user_id = $user_id";
                $result_booked_sessions = $connection->query($sql_booked_sessions);

                if (!$result_booked_sessions) {
                    die("Error fetching booked sessions: " . $connection->error);
                }

                if ($result_booked_sessions->num_rows > 0) {
                    while($row_booked_sessions = $result_booked_sessions->fetch_assoc()) {
                        echo "<tr>
                                <td>".$row_booked_sessions["session_name"]."</td>
                                <td>".$row_booked_sessions["date"]."</td>
                                <td>".$row_booked_sessions["location"]."</td>
                                <td>".$row_booked_sessions["price"]."</td>
                                <td>".$row_booked_sessions["username"]."</td>
                                <td>".$row_booked_sessions["status"]."</td>
                                <td><button onclick='deleteBooking(".$row_booked_sessions["booking_id"].")'>Delete</button></td>
                            </tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>No sessions booked yet.</td></tr>";
                }
            
            ?>
        </table>
    </div>

    
    
    <script>
    function bookSession(sessionId) {
        // You can handle the booking logic here, such as redirecting to a booking page or displaying a confirmation message
        alert("Session booked! Session ID: " + sessionId);
        
        // AJAX request to book the session
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
                // Reload the page to update the booked sessions table
                window.location.reload();
            }
        };
        xhr.open("POST", "../functions/book_session.php", true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.send("session_id=" + sessionId);
    }

    function deleteBooking(bookingId) {
        // AJAX request to delete the booking
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
                // Reload the page to update the booked sessions table
                window.location.reload();
            }
        };
        xhr.open("POST", "../functions/delete_booking.php", true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.send("booking_id=" + bookingId);
    }
    </script>
    </div>
</body>
</html>
