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
} else {
    // Redirect the user to the login page if user information is not found
    header("Location: login.php");
    exit();
}

// Fetch featured photographers
$sql_featured_photographers = "SELECT * FROM Users WHERE user_type = 'photographer' ORDER BY RAND() LIMIT 3";
$result_featured_photographers = $connection->query($sql_featured_photographers);

// Fetch sessions booked by the user
$sql_booked_sessions = "SELECT s.session_id, s.session_name, s.description, s.date, s.location, s.price, u.username
                        FROM Sessions s
                        INNER JOIN Users u ON s.photographer_id = u.user_id
                        INNER JOIN Bookings b ON s.session_id = b.session_id
                        WHERE b.user_id = ?";
$stmt_booked_sessions = $connection->prepare($sql_booked_sessions);
$stmt_booked_sessions->bind_param("i", $user_id);
$stmt_booked_sessions->execute();
$result_booked_sessions = $stmt_booked_sessions->get_result();


// Fetch notifications for session bookings
$sql_notifications = "SELECT s.session_name, b.status
                      FROM Sessions s
                      INNER JOIN Bookings b ON s.session_id = b.session_id
                      WHERE b.user_id = ? AND (b.status = 'rejected' OR b.status = 'approved')";
$stmt_notifications = $connection->prepare($sql_notifications);
$stmt_notifications->bind_param("i", $user_id);
$stmt_notifications->execute();
$result_notifications = $stmt_notifications->get_result();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="icon" href="../assets/appicon.png">
    <link rel="stylesheet" href="../css/pdashboard.css"> 
</head>
<body>
<header>
    <div class="nav-left">
        <h1><img src="../assets/profile.png" alt="Profile Picture" style="width: 50px; height: 50px; border-radius: 50%;"> <?php echo $user_info['username']; ?>!</h1>
    </div>
    <div class="search-box">
            <form action="../functions/search.php" method="GET">
                <input type="text" name="query" placeholder="Search photographers...">
                <button type="submit"><i class="fas fa-search"></i></button>
            </form>
        </div>
    <nav>
        <ul>
            <li><a href="pdashboard.php?page=pdashboard" id="pdashboard"><i class="fas fa-home"></i> Home</a></li>
            <li><a href="portfolio.php?page=portfolio" id="portfolio"><i class="fas fa-home"></i> Portfolios</a></li>
            <li><a href="sessions.php?page=sessions" id="sessions"><i class="fas fa-home"></i> Sessions</a></li>
            <li><a href="profile.php?page=profile" id="profile"><i class="fas fa-user"></i> Profile</a></li>
        </ul>
        
        
    </nav>
</header>


<div id="content-container">

    <div class="main-content">
    <section id="notifications">
        <h2>Notifications</h2>
        <div class="notifications">
            <?php
            if ($result_notifications->num_rows > 0) {
                while ($row_notification = $result_notifications->fetch_assoc()) {
                $status_message = ($row_notification['status'] == 'rejected') ? 'rejected' : 'approved';
                echo "<div class='notification'>
                        <p>Your session booking for '{$row_notification['session_name']}' has been {$status_message}.</p>
                      </div>";
                }
            } else {
            echo "<p>No new notifications.</p>";
            }
            ?>
        </div>
    </section>

        <section id="featured-photographers">
            <h2>Featured Photographers</h2>
            <div class="photographers-grid">
                <?php
                while ($row_featured_photographers = $result_featured_photographers->fetch_assoc()) {
                    echo "<div class='photographer'>
                            <img src='{$row_featured_photographers['profile_picture']}' alt='Photographer'>
                            <p>{$row_featured_photographers['username']}</p>
                          </div>";
                }
                ?>
            </div>
        </section>
        <section id="booked-sessions">
            <h2>Your Booked Sessions</h2>
            <div class="sessions">
                <?php
                if ($result_booked_sessions->num_rows > 0) {
                    while ($row_booked_sessions = $result_booked_sessions->fetch_assoc()) {
                        echo "<div class='session'>
                                <h3>{$row_booked_sessions['session_name']}</h3>
                                <p>{$row_booked_sessions['description']}</p>
                                <p>Date: {$row_booked_sessions['date']}</p>
                                <p>Location: {$row_booked_sessions['location']}</p>
                                <p>Price: {$row_booked_sessions['price']}</p>
                                <p>Photographer: {$row_booked_sessions['username']}</p>
                              </div>";
                    }
                } else {
                    echo "<p>No sessions booked yet.</p>";
                }
                ?>
            </div>
        </section>

        <section id="book-sessions">
            <h2>Book Sessions</h2>
            <a href="sessions.php" class="btn">Book Now</a>
        </section>

       
    </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
    $(document).ready(function() {
    $('.nav-link').click(function(e) {
        e.preventDefault(); // Prevent default link behavior

        var url = $(this).attr('href'); // Get the URL from the clicked link

        // Fetch the content of the linked page using AJAX
        $.get(url, function(data) {
            $('#content-container').html(data); // Replace the content of the container with the fetched data
        });
    });
});

    </script>

</body>
</html>
