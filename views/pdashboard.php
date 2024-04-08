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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-jb/2LDVYfH9c6ITrdC39DY/dZ8Jhk0Rtnb2KlFegsBY9tWzVWJASpOn5WTdWRSZ3ZFsmxuT8sZaxEOfgnxlbkA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="icon" href="../assets/appicon.png">
    <link rel="stylesheet" href="../css/pdashboard.css"> 
</head>
<body>
    <header>
        <div class="nav-left">
            <h1><img src="<?php echo $user_info['profile_picture']; ?>" alt="Profile Picture" style="width: 50px; height: 50px; border-radius: 50%;"> <?php echo $user_info['username']; ?>!</h1>
        </div>
        <nav>
    <ul>
        <li><a href="pdashboard.php"><i class="fas fa-home"></i> Home</a></li>
        <li><a href="portfolio.php"><i class="fas fa-home"></i> Portifolios</a></li>
        <li><a href="sessions.php"><i class="fas fa-home"></i> sessions</a></li>
        <li><a href="profile.php"><i class="fas fa-user"></i> Profile</a></li>
        <li><a href="settings.php"><i class="fas fa-cog"></i> Settings</a></li>
        <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
    </ul>
</nav>

    </header>

    <div class="main-content">
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

        <section id="book-sessions">
            <h2>Book Sessions</h2>
            <a href="sessions.php" class="btn">Book Now</a>
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
</body>
</html>
