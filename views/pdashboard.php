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
    $_SESSION['flash_message'] = 'you are not logged in';
    header("Location: login.php");
    exit();
}
if(isset($_GET['query'])) {
    $search_query = $_GET['query'];

    // Fetch photographers based on search query
    $sql_search_photographers = "SELECT * FROM Users WHERE user_type = 'photographer' AND username LIKE ? ORDER BY username";
    $stmt_search_photographers = $connection->prepare($sql_search_photographers);
    $search_param = "%{$search_query}%";
    $stmt_search_photographers->bind_param("s", $search_param);
    $stmt_search_photographers->execute();
    $result_search_photographers = $stmt_search_photographers->get_result();
} else {
    // Fetch featured photographers if no search query is submitted
    $sql_featured_photographers = "SELECT * FROM Users WHERE user_type = 'photographer' ORDER BY RAND() LIMIT 5";
    $result_featured_photographers = $connection->query($sql_featured_photographers);
}


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
//to check if the logged in user is a photographer
$is_photographer = $user_info['user_type'] === 'photographer';
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
        <!--<h1><img src="../assets/profile.png" alt="Profile Picture" style="width: 50px; height: 50px; border-radius: 50%;"> <?php echo $user_info['username']; ?>!</h1>-->
    </div>
    <div class="search-box">
        <form action="" method="GET">
            <input type="text" name="query" placeholder="Search photographers...">
            <button type="submit"><i class="fas fa-search"></i></button>
        </form>
    </div>
</header>
<nav class="side-nav">
    <div class="user-profile">
        <img src="../assets/profile.png" alt="Profile Picture" class="profile-picture">
        <p class="username"><?php echo $user_info['username']; ?></p>
    </div>
    <ul><li>
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


<div id="content-container">

    <div class="main-content">
        <div class="section-container">
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
    </div>
    <div class="section-container">

    <section id="featured-photographers">
    <h2>Featured Photographers</h2>
    <div class="photographers-grid">
        <?php
        if (isset($result_search_photographers)) {
            if ($result_search_photographers->num_rows > 0) {
                while ($row_search_photographers = $result_search_photographers->fetch_assoc()) {
                    echo "<div class='photographer'>
                    <p>{$row_search_photographers['username']}</p>
                    <a href='../sessions.php?photographer_id={$row_search_photographers['user_id']}' class='book-session-btn' data-photographer-id='{$row_search_photographers['user_id']}'>Book Session</a>
                </div>";
                
                }
            } else {
                echo "<p>No photographers found.</p>";
            }
        } else {
            // Display featured photographers if no search query is submitted
            while ($row_featured_photographers = $result_featured_photographers->fetch_assoc()) {
                echo "<div class='photographer'>
                        <p>{$row_featured_photographers['username']}</p>
                        <button class='book-session-btn' data-photographer-id='{$row_featured_photographers['user_id']}'>Book Session</button>
                      </div>";
            }
        }
        ?>
    </div>
    </section>
    </div>
    <div class="section-container">
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
            </div>

        <div class="section-container">
        <section id="book-sessions">
            <h2>Book Sessions</h2>
            <a href="sessions.php" class="btn">Book Now</a>
        </section>

       
    </div>
    </div>
            </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        $('.nav-link').click(function(e) {
            e.preventDefault(); 

            var url = $(this).attr('href'); 

            $.get(url, function(data) {
                $('#content-container').html(data); 
            });
        });
    });
</script>


</body>
</html>
