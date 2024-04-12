<?php
session_start();
include("../settings/auto.php");
$user_id = $_SESSION['user_id'];
include_once('../settings/config.php');
$sql_user_info = "SELECT * FROM Users WHERE user_id = ?";
$stmt_user_info = $connection->prepare($sql_user_info);
$stmt_user_info->bind_param("i", $user_id);
$stmt_user_info->execute();
$result_user_info = $stmt_user_info->get_result();

if ($result_user_info->num_rows > 0) {
    $user_info = $result_user_info->fetch_assoc();
}
$current_date = date("Y-m-d");
$sql_active_sessions = "SELECT s.session_id, s.session_name, s.description, s.date, s.location, s.price, u.username
                        FROM Sessions s
                        INNER JOIN Users u ON s.photographer_id = u.user_id
                        WHERE s.status = 'open'
                        AND s.date >= '$current_date'
                        AND s.session_id NOT IN (
                            SELECT session_id FROM Bookings
                        )";
//filter function
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
$sql_booked_sessions = "SELECT s.session_name, s.date, s.location, s.price, u.username, b.status, b.booking_id
                        FROM Sessions s
                        INNER JOIN Users u ON s.photographer_id = u.user_id
                        INNER JOIN Bookings b ON s.session_id = b.session_id
                        WHERE b.user_id = $user_id";
$result_booked_sessions = $connection->query($sql_booked_sessions);
$is_photographer = $user_info['user_type'] === 'photographer';
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
    <style>
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
        }
        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 60%;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
        .modal-buttons {
            margin-top: 20px;
            text-align: center;
        }
        button {
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Navigation bar -->
        <nav class="side-nav">
            <div class="user-profile">
                <img src="../assets/profile.png" alt="Profile Picture" class="profile-picture">
                <p class="username"><?php echo $user_info['username']; ?></p>
            </div>
            <!-- Navigation Links -->
            <ul>
                <!--this line of code displays the create link if the user is a photographer which leads them back to the photographer's dashboard-->
                <?php if ($is_photographer): ?>
                    <li><a href="photographer.php?photographer" id="pdashboard"><i class="fas fa-plus-circle"></i> Create</a></button>
                <?php endif; ?>
                <li><a href="pdashboard.php?page=pdashboard" id="pdashboard"><i class="fas fa-home"></i> Home</a></li>
                <li><a href="portfolio.php?page=portfolio" id="portfolio"><i class="fas fa-camera"></i> Portfolios</a></li>
                <li><a href="sessions.php?page=sessions" id="sessions"><i class="fas fa-check-circle"></i> Sessions</a></li>
                <li><a href="profile.php?page=profile" id="profile"><i class="fas fa-user"></i> Profile</a></li>
                <li><a href="../login/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </nav>
        <header>
            <div class="nav-left">
                <h1><?php echo $user_info['username']; ?>!</h1>
            </div>
            
        </header>
        <div id="main-content">
            <!-- Active Sessions -->
            <div class="session-container">
                <h1>Session Listings</h1>
                <!-- Filters -->
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
                <!-- Active Sessions Table -->
                <div class="session-table">
                    <table>
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Description</th>
                                <th>Date</th>
                                <th>Location</th>
                                <th>Price</th>
                                <th>Photographer</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($result_active_sessions->num_rows > 0) {
                                while($row_active_sessions = $result_active_sessions->fetch_assoc()) {
                                    echo "<tr>
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
                                echo "<tr><td colspan='7'>No active sessions found.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- Booked Sessions -->
            <div class="session-container">
                <h2>Sessions Booked by You</h2>
                <!-- Booked Sessions Table -->
                <div class="session-table">
                    <table>
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Date</th>
                                <th>Location</th>
                                <th>Price</th>
                                <th>Photographer</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($result_booked_sessions->num_rows > 0) {
                                while($row_booked_sessions = $result_booked_sessions->fetch_assoc()) {
                                    echo "<tr>
                                            <td>".$row_booked_sessions["session_name"]."</td>
                                            <td>".$row_booked_sessions["date"]."</td>
                                            <td>".$row_booked_sessions["location"]."</td>
                                            <td>".$row_booked_sessions["price"]."</td>
                                            <td>".$row_booked_sessions["username"]."</td>
                                            <td>".$row_booked_sessions["status"]."</td>
                                            <td><button onclick='openDeleteModal(".$row_booked_sessions["booking_id"].")'>Delete</button></td>
                                        </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='7'>No sessions booked yet.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- Delete Confirmation Modal -->
    <div id="deleteConfirmationModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <p>Are you sure you no longer want this session?</p>
            <div class="modal-buttons">
                <button id="confirmDelete">Yes</button>
                <button id="cancelDelete">No</button>
            </div>
        </div>
    </div>
    <!-- Book Confirmation Modal -->
    <div id="bookConfirmationModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <p>Do you want to book this session?</p>
            <div class="modal-buttons">
                <button id="confirmBook">Yes</button>
                <button id="cancelBook">No</button>
            </div>
        </div>
    </div>
    <script src="../js/sessions.js"></script>
</body>
</html>
