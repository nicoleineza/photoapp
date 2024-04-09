<?php
session_start(); // Start session to access session variables
include_once("../settings/config.php");
include("../functions/fetch_session.php"); 
include("../functions/fetch_photo.php");
include("../functions/booking_requests.php");

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if user is not logged in
    header("Location: ../login/login.php");
    exit();
}

$photographer_id = $_SESSION['user_id'];

// Fetch photos for the currently logged in photographer
$photos = fetchPhotographerPhotos($connection, $photographer_id);

// Fetch sessions for the currently logged in photographer
$sessions = fetchPhotographerSessions($connection, $photographer_id);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Photographer Page</title>
    <link rel="stylesheet" href="../css/photographer.css">
    <link rel="icon" href="../assets/appicon.png">
</head>
<body>
<h1 style="
    font-size: 24px;
    margin: 0; padding: 10px;
    color: #fff;
    font-weight: bold;
    text-transform: uppercase;
    background-color: #ff6f61;
    text-align: center;
    position: fixed; /* Make the <h1> fixed */
    top: 0; /* Align it to the top */
    left: 0; /* Align it to the left */
    width: 100%; /* Make it full width */
    z-index: 1000; /* Ensure it's above other content */
">Dashboard</h1>
    <header>
    
        <?php        
        // Check if user is logged in
        if (isset($_SESSION['user_id'])) {
            $photographer_id = $_SESSION['user_id'];
            
            // Query to fetch photographer's name based on user ID
            $query = "SELECT username FROM Users WHERE user_id = ?";
            $statement = $connection->prepare($query);
            $statement->bind_param("i", $photographer_id);
            $statement->execute();
            $result = $statement->get_result();
            
            // Check if the query was successful and if the photographer's name is found
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                // Display the photographer's name
                echo "<h1>" . $row['username'] . "</h1>";
            } else {
                // Display a default message if the photographer's name is not found
                echo "<h1>Photographer Name</h1>";
            }
            
            // Close the statement
            $statement->close();
        } else {
            // Redirect to login page if user is not logged in
            header("Location: ../login/login.php");
            exit();
        }
        ?>
        <p>Professional Photographer</p>
    
        <!-- Navigation Section -->
        
        <nav>
            <a href="#bio">Booking</a>
            <a href="#portfolio">Portfolio</a>
            <a href="#sessions">Session Listings</a>
            
        </nav>
        <form action="../login/logout.php" method="post">
            <input type="submit" value="Logout" style =" margin-left:-60px; margin-bottom:70px;color:white; width:100px; height:50px; background-color:grey;">
        </form>
    </header>
    
    
    
    <!-- Main Content -->
    <div class="container">
        <section id="bio" class="bio">
            <h2>Booking Requests</h2>
            <?php
            // Fetch booking requests where the session was created by the logged-in photographer
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

                        <!-- Form to change booking status -->
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
                echo "<p>No one has booked any of your sessions.</p>";
            }
            ?>
        </section>

        <!-- Portfolio Section -->
        <section id="portfolio">
            <h2>Portfolio</h2>
            <a href="picture.php"><button id="addPhotoBtn">Add New Photo</button></a>
            <div class="portfolio">
                <!-- Display fetched photos -->
                <?php if (!empty($photos)): ?>
                    <?php foreach ($photos as $photo): ?>
                        <?php $base64_image = base64_encode($photo['productImage']); ?>
                        <div class="photo-item" data-photo-id="<?= $photo['id'] ?>">
                            <img src="data:image/jpeg;base64,<?= $base64_image ?>" alt="Portfolio Image">
                            <!-- Display product name -->
                            <p class="description"><?= $photo['productName'] ?></p>
                            <!-- Display sale status and price -->
                            <?php if ($photo['isForSale'] == 1): ?>
                                <p>This product is for sale</p>
                                <p class="price">Price: $<?= $photo['productPrice'] ?></p>
                            <?php else: ?>
                                <p>This product is not for sale</p>
                            <?php endif; ?>
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
            <?php
            // Check if sessions are available
            if (!empty($sessions)) {
                // Iterate over sessions and display them
                foreach ($sessions as $session) {
                    ?>
                    <div class="session-item" data-session-id="<?= $session['session_id'] ?>">
                        <h3><?= $session['session_name'] ?></h3>
                        <p>Date: <?= $session['date'] ?></p>
                        <p>Location: <?= $session['location'] ?></p>
                        <p>Description: <?= $session['description'] ?></p>
                        <p>Price: $<?= $session['price'] ?></p>
                        <!-- Add buttons to edit and delete session -->
                        <div class="buttons">
                            <button class="edit-session-btn" data-session-id="<?= $session['session_id'] ?>">Edit</button>
                            <button class="delete-session-btn" data-session-id="<?= $session['session_id'] ?>">Delete</button>
                        </div>
                        <!-- Add more details as needed -->
                    </div>
                    <?php
                }
            } else {
                // If no sessions are found, display a message
                echo "<p>No sessions found.</p>";
            }
            ?>
            <a href="addsession.php"><button id="addSessionBtn">Add New Session</button></a>
        </section>
    </div>


    <!-- Modal for editing photo details -->
    <div id="editPhotoModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Edit Photo Details</h2>
            <!-- Form for editing photo details -->
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
            <!-- Form for editing session details -->
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
</body>
</html>
