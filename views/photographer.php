<?php
session_start(); // Start session to access session variables
include_once("../settings/config.php");
include("../functions/fetch_session.php"); 
include("../functions/fetch_photo.php");

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
    <style>
        /* Add your custom CSS styles here */
    </style>
</head>
<body>
    <header>
        <h1>Photographer Name</h1>
        <p>Professional Photographer</p>
        <!-- Logout button -->
        <form action="../login/logout.php" method="post">
            <input type="submit" value="Logout">
        </form>
    </header>
    <nav>
        <a href="#bio">Bio</a>
        <a href="#portfolio">Portfolio</a>
        <a href="sessions.php">Session Listings</a>
        <a href="#contact">Contact</a>
    </nav>
    <!-- Success message div -->
    <div id="successMessage" style="display: none; background-color: green; padding: 10px; margin: 10px;">
        <strong>Success:</strong> Changes saved successfully.
    </div>

    <div class="container">
        <section id="bio" class="bio">
            <h2>Bio</h2>
            <p>Insert photographer's bio here.</p>
        </section>
        <section id="portfolio">
            <h2>Portfolio</h2>
            <a href="addphoto.php"><button id="addPhotoBtn">Add New Photo</button></a>
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
    <footer>
        &copy; 2024 Photographer Name. All rights reserved.
    </footer>

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
    <script>
    $(document).ready(function() {
        // Event listener for edit buttons
        $(".edit-btn").click(function() {
            var photoItem = $(this).closest('.photo-item');
            var photoId = $(this).data('photo-id');
            var description = photoItem.find('.description').text();
            var isForSale = photoItem.find('p:contains("This product is for sale")').length > 0 ? 1 : 0;
            var price = photoItem.find('.price').text().replace("Price: $", "").trim();

            $("#editPhotoId").val(photoId);
            $("#editDescription").val(description);
            $("#editIsForSale").val(isForSale);
            $("#editPrice").val(price);

            $("#editPhotoModal").show();
        });

        // Event listener for edit session buttons
        $(".edit-session-btn").click(function() {
            var sessionId = $(this).data('session-id');
            var sessionItem = $(".session-item[data-session-id='" + sessionId + "']");
            var sessionDate = sessionItem.find('p').eq(0).text().replace("Date: ", "").trim();
            var sessionLocation = sessionItem.find('p').eq(1).text().replace("Location: ", "").trim();

            $("#editSessionId").val(sessionId);
            $("#editSessionDate").val(sessionDate);
            $("#editSessionLocation").val(sessionLocation);

            $("#editSessionModal").show();
        });

        // Submit handler for editing photo form
        $("#editPhotoForm").submit(function(event) {
            event.preventDefault();
            var formData = $(this).serialize();

            $.ajax({
                url: '../functions/edit_picture.php',
                type: 'POST',
                data: formData,
                success: function(response) {
                    console.log("Photo details edited:", response);
                    var photoId = $("#editPhotoId").val();
                    var photoItem = $(".photo-item[data-photo-id='" + photoId + "']");
                    var description = $("#editDescription").val();
                    var price = $("#editPrice").val();
                    photoItem.find('.description').text(description);
                    photoItem.find('.price').text("Price: $" + price);

                    // Display success message
                    $("#successMessage").fadeIn().delay(3000).fadeOut();

                    $("#editPhotoModal").hide();
                },
                error: function(xhr, status, error) {
                    console.error("Error editing photo details:", error);
                    alert("Error editing photo details. Please try again later.");
                }
            });
        });

        // Submit handler for editing session form
        $("#editSessionForm").submit(function(event) {
            event.preventDefault();
            var formData = $(this).serialize();

            $.ajax({
                url: '../functions/edit_session.php',
                type: 'POST',
                data: formData,
                success: function(response) {
                    console.log("Session details edited:", response);
                    var sessionId = $("#editSessionId").val();
                    var sessionItem = $(".session-item[data-session-id='" + sessionId + "']");
                    var sessionDate = $("#editSessionDate").val();
                    var sessionLocation = $("#editSessionLocation").val();
                    sessionItem.find('p').eq(0).text("Date: " + sessionDate);
                    sessionItem.find('p').eq(1).text("Location: " + sessionLocation);

                    // Display success message
                    $("#successMessage").fadeIn().delay(3000).fadeOut();

                    $("#editSessionModal").hide();
                },
                error: function(xhr, status, error) {
                    console.error("Error editing session details:", error);
                    alert("Error editing session details. Please try again later.");
                }
            });
        });

        // Event listener for delete buttons
        $(".delete-btn").click(function() {
            var photoId = $(this).data('photo-id');
            if (confirm("Are you sure you want to delete this photo?")) {
                $.ajax({
                    url: '../functions/delete_photo.php',
                    type: 'POST',
                    data: { photoId: photoId },
                    success: function(response) {
                        console.log("Photo deleted:", response);
                        $(".photo-item[data-photo-id='" + photoId + "']").remove();
                        // Display success message
                        $("#successMessage").fadeIn().delay(3000).fadeOut();
                    },
                    error: function(xhr, status, error) {
                        console.error("Error deleting photo:", error);
                        alert("Error deleting photo. Please try again later.");
                    }
                });
            }
        });

        // Event listener for delete session buttons
        $(".delete-session-btn").click(function() {
            var sessionId = $(this).data('session-id');
            if (confirm("Are you sure you want to delete this session?")) {
                $.ajax({
                    url: '../functions/delete_session.php',
                    type: 'POST',
                    data: { sessionId: sessionId },
                    success: function(response) {
                        console.log("Session deleted:", response);
                        $(".session-item[data-session-id='" + sessionId + "']").remove();
                        // Display success message
                        $("#successMessage").fadeIn().delay(3000).fadeOut();
                    },
                    error: function(xhr, status, error) {
                        console.error("Error deleting session:", error);
                        alert("Error deleting session. Please try again later.");
                    }
                });
            }
        });

        // Event listener for closing modals when clicking outside of them
        $(window).click(function(event) {
            if (event.target.className === "modal") {
                $(".modal").hide();
            }
        });
    });
    </script>


</body>
</html>
