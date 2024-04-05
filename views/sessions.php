<?php
session_start(); // Start the session

// Database connection
include_once('../settings/config.php');

// Check connection
if ($connection->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch active sessions that have not passed the current date
$current_date = date("Y-m-d");
$sql = "SELECT s.session_id, s.session_name, s.description, s.date, s.location, s.price, u.username
        FROM Sessions s
        INNER JOIN Users u ON s.photographer_id = u.user_id
        WHERE s.status = 'open' AND s.date >= '$current_date'";
$result = $connection->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Session Listings</title>
    <link rel="stylesheet" href="../css/sessions.css">
</head>
<body>
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
                <th></th> <!-- New column for the book button -->
            </tr>
            <!-- PHP code to fetch session data from the database -->
            <?php
            if ($result->num_rows > 0) {
                // Output data of each row
                while($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>".$row["session_name"]."</td>
                            <td>".$row["description"]."</td>
                            <td>".$row["date"]."</td>
                            <td>".$row["location"]."</td>
                            <td>".$row["price"]."</td>
                            <td>".$row["username"]."</td>
                            <td><button onclick='bookSession(".$row["session_id"].")'>Book Now</button></td>
                        </tr>";
                }
            } else {
                echo "<tr><td colspan='7'>No active sessions found.</td></tr>";
            }
            ?>
        </table>
    </div>
    
    <!-- Table to show sessions a user has booked -->
    <div class="container">
        <h2>Sessions Booked by You</h2>
        <table>
            <tr>
                <th>Title</th>
                <th>Date</th>
                <th>Location</th>
                <th>Price</th>
                <th>Photographer</th>
            </tr>
            <!-- PHP code to fetch sessions booked by the user -->
            <?php
            if (isset($_SESSION['user_id'])) {
                $user_id = $_SESSION['user_id']; // Assuming you have stored user ID in session
                $sql_booked = "SELECT s.session_name, s.date, s.location, s.price, u.username, b.status
                               FROM Sessions s
                               INNER JOIN Users u ON s.photographer_id = u.user_id
                               INNER JOIN Bookings b ON s.session_id = b.session_id
                               WHERE b.user_id = $user_id";
                $result_booked = $connection->query($sql_booked);

                if ($result_booked->num_rows > 0) {
                    // Output data of each row
                    while($row_booked = $result_booked->fetch_assoc()) {
                        echo "<tr>
                                <td>".$row_booked["session_name"]."</td>
                                <td>".$row_booked["date"]."</td>
                                <td>".$row_booked["location"]."</td>
                                <td>".$row_booked["price"]."</td>
                                <td>".$row_booked["username"]."</td>
                            </tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No sessions booked yet.</td></tr>";
                }
            }
            ?>
        </table>
    </div>

    <footer>
        <div class="footer-container">
            <div class="footer-links">
                <a href="#">About Us</a>
                <a href="#">Terms of Service</a>
                <a href="#">Privacy Policy</a>
                <a href="#">Contact</a>
            </div>
            <div class="social-icons">
                <a href="#"><img src="facebook-icon.png" alt="Facebook"></a>
                <a href="#"><img src="twitter-icon.png" alt="Twitter"></a>
                <a href="#"><img src="instagram-icon.png" alt="Instagram"></a>
            </div>
        </div>
    </footer>
    
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
    </script>
</body>
</html>
