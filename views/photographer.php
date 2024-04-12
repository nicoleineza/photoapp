<?php
session_start(); // Start session to access session variables
include_once("../settings/config.php");
include("../functions/fetch_session.php"); 
include("../functions/fetch_photo.php");
include("../functions/booking_requests.php");
include("../functions/fetch_reviews.php");

if (isset($_SESSION['flash_message'])) {
    echo '<div class="flash-message">' . $_SESSION['flash_message'] . '</div>';
    
    unset($_SESSION['flash_message']);
}

$photographer_id = $_SESSION['user_id'];

// Fetch photos for the currently logged in photographer
$photos = fetchPhotographerPhotos($connection, $photographer_id);

// Fetch sessions for the currently logged in photographer
$sessions = fetchPhotographerSessions($connection, $photographer_id);
$user_id = $_SESSION['user_id'];

$sql_user_info = "SELECT * FROM Users WHERE user_id = ?";
$stmt_user_info = $connection->prepare($sql_user_info);
$stmt_user_info->bind_param("i", $user_id);
$stmt_user_info->execute();
$result_user_info = $stmt_user_info->get_result();

$user_info = [];
if ($result_user_info->num_rows > 0) {
    $user_info = $result_user_info->fetch_assoc();
}
//code to check if the user logged in is a photographer to display the create button
$is_photographer = $user_info['user_type'] === 'photographer';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Photographer Page</title>
    <link rel="stylesheet" href="../css/photographer.css">
    <link rel="icon" href="../assets/appicon.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<style>.flash-message {
    background-color: #4CAF50;
    color: white;
    text-align: center;
    padding: 10px;
    margin-bottom: 20px;
}
</style>
<body>
<header>
    <div class="nav-left">
        <h1><img src="../assets/profile.png" alt="Profile Picture" style="width: 50px; height: 50px; border-radius: 50%;"> <?php echo $user_info['username']; ?>!</h1>
    </div>
        </form>
    </div>
</header>
<nav class="side-nav">
    <div class="user-profile">
        <img src="../assets/profile.png" alt="Profile Picture" class="profile-picture">
        <p class="username"><?php echo $user_info['username']; ?></p>
    </div>
    <ul>
        <?php if ($is_photographer): ?>
            <li><a href="photographer.php?photographer" id="pdashboard"><i class="fas fa-plus-circle"></i> Create</a></button>
        <?php endif; ?>
        <li><a href="pdashboard.php?page=pdashboard" id="pdashboard"><i class="fas fa-home"></i> Home</a></li>
        <li><a href="picture.php?page=portfolio" id="portfolio"><i class="fas fa-camera"></i> Portfolios</a></li>
        <li><a href="addsession.php?page=sessions" id="sessions"><i class="fas fa-check-circle"></i>Sessions</a></li>
        <li><a href="profile.php?page=profile" id="profile"><i class="fas fa-user"></i> Profile</a></li>
        <li><a href="../login/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
    </ul>
    
</nav>
    <div class="container">
        <section id="bio" class="bio">
            <h2>Booking Requests</h2>
            <p>View people who have booked your sessions and you can confirm the booking or not</p>
            <?php
            $bookingRequests = requests($connection, $photographer_id);

            if (!empty($bookingRequests)) {
                foreach ($bookingRequests as $booking) {
                    ?>
                    <div class="booking">
                        <p>Booking ID: <?= $booking['booking_id'] ?></p>
                        <p>Session Name: <?= $booking['session_name'] ?></p>
                        <p>Date: <?= $booking['date'] ?></p>
                        <p>Location: <?= $booking['location'] ?></p>
                        <p>Price: $<?= $booking['price'] ?></p>
                        <form action="../functions/status.php" method="post" class="change-status-form">
                            <input type="hidden" name="booking_id" value="<?= $booking['booking_id'] ?>">
                            <select name="status">
                                <option value="pending">Pending</option>
                                <option value="approved">Approved</option>
                                <option value="rejected">Rejected</option>
                            </select>
                            <input type="submit" value="Change Status">
                        </form>
                    </div>
                    <hr>
                    <?php
                }
            } else {
                echo "<p>No one has booked any of your sessions yet.</p>";
            }
            ?>
        </section>
        <section id="portfolio">
    <h2>Portfolio</h2>
    <p>Add pictures for our community to be able to discover what you do and appreciate!</p>
    <a href="picture.php"><button id="addPhotoBtn">Add New Photo</button></a>
    <div class="portfolio">
        <!-- Display fetched photos -->
        <?php if (!empty($photos)): ?>
            <?php foreach ($photos as $photo): ?>
                <?php 
                    $base64_image = base64_encode($photo['productImage']); 
                    // Fetch reviews associated with the current photo
                    $reviews = fetchReviewsForImage($connection, $photo['id']);
                    
                    // Calculate average rating
                    $totalRating = 0;
                    $numReviews = count($reviews);
                    foreach ($reviews as $review) {
                        $totalRating += $review['rating'];
                    }
                    $averageRating = $numReviews > 0 ? $totalRating / $numReviews : 0;
                ?>
                <div class="photo-item" data-photo-id="<?= $photo['id'] ?>">
                    <img src="data:image/jpeg;base64,<?= $base64_image ?>" alt="Portfolio Image">
                    <!-- Display product name -->
                    <p class="description"><?= $photo['productName'] ?></p>
                    <!-- Display sale status and price -->
                    <?php if ($photo['isForSale'] == 1): ?>
                        <p>This picture  is for sale</p>
                        <p class="price">Price: $<?= $photo['productPrice'] ?></p>
                    <?php else: ?>
                        <p>This picture is not for sale</p>
                    <?php endif; ?>
                    <!-- Display average rating -->
                    <p>Average Rating: <?= number_format($averageRating, 1) ?></p>
                    <!-- Button to show comments -->
                    <button class="show-comments-btn" data-photo-id="<?= $photo['id'] ?>">Show Comments</button>
                    <!-- Display comments -->
                    <div class="comments-container" style="display: none;">
                        <?php if (!empty($reviews)): ?>
                            <ul>
                                <?php foreach ($reviews as $review): ?>
                                    <li><?= $review['comment'] ?> - Rating: <?= $review['rating'] ?></li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <p>No reviews yet.</p>
                        <?php endif; ?>
                    </div>
                    <!-- Edit and delete buttons -->
                    <div class="buttons">
                        <button class="edit-btn" data-photo-id="<?= $photo['id'] ?>">Edit</button>
                        <button class="delete-btn" data-photo-id="<?= $photo['id'] ?>">Delete</button>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No photos found.</p>
        <?php endif; ?>
    </div>
</section>



        <!-- Sessions Section -->
        <section id="sessions" class="session-listings">
            <h2>Session Listings</h2>
            <p>Create slots for when you are available for picture sessions</p>
            <a href="addsession.php"><button id="addSessionBtn">Add New Session</button></a>
            <?php
            // Check if sessions are available
            if (!empty($sessions)) {
                foreach ($sessions as $session) {
                    ?>
                    <div class="session-item" data-session-id="<?= $session['session_id'] ?>">
                        <h3><?= $session['session_name'] ?></h3>
                        <p>Date: <?= $session['date'] ?></p>
                        <p>Location: <?= $session['location'] ?></p>
                        <p>Description: <?= $session['description'] ?></p>
                        <p>Price: $<?= $session['price'] ?></p>
                        <div class="buttons">
                            <button class="edit-session-btn" data-session-id="<?= $session['session_id'] ?>">Edit</button>
                            <button class="delete-session-btn" data-session-id="<?= $session['session_id'] ?>">Delete</button>
                        </div>
                    </div>
                    <?php
                }
            } else {
                echo "<p>No sessions found.</p>";
            }
            ?>
            
        </section>
    </div>


    <!-- Modal for editing photo details -->
    <div id="editPhotoModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Edit Photo Details</h2>
            <form id="editPhotoForm">
                <input type="hidden" id="editPhotoId" name="editPhotoId">
                <label for="editDescription">Description:</label><br>
                <textarea id="editDescription" name="editDescription" rows="4" cols="50"></textarea><br>
                <label for="editIsForSale">Is for Sale:</label><br>
                <select id="editIsForSale" name="editIsForSale">
                    <option value="1">Yes</option>
                    <option value="0">No</option>
                </select><br>
                <label for="editPrice">Price:</label><br>
                <input type="number" id="editPrice" name="editPrice" step="0.01"><br>
                <input type="submit" value="Save Changes">
            </form>
        </div>
    </div>

    <!-- Modal for editing session details -->
    <div id="editSessionModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Edit Session Details</h2>
            <form id="editSessionForm">
                <input type="hidden" id="editSessionId" name="editSessionId">
                <label for="editSessionDate">Date:</label><br>
                <input type="date" id="editSessionDate" name="editSessionDate" required><br>
                <label for="editSessionLocation">Location:</label><br>
                <input type="text" id="editSessionLocation" name="editSessionLocation" required><br>
                <input type="submit" value="Save Changes">
            </form>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="../js/photographer.js"></script>
    <script>
        // JavaScript to toggle the visibility of comments when clicking the button
document.addEventListener('DOMContentLoaded', function() {
    var buttons = document.querySelectorAll('.show-comments-btn');
    buttons.forEach(function(button) {
        button.addEventListener('click', function() {
            var photoId = this.dataset.photoId;
            var commentsContainer = document.querySelector('.photo-item[data-photo-id="' + photoId + '"] .comments-container');
            commentsContainer.style.display = commentsContainer.style.display === 'none' ? 'block' : 'none';
        });
    });
});

    </script>
</body>
</html>
