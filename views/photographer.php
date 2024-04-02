<?php
session_start(); // Start session to access session variables

include("../settings/config.php");

function fetchPhotographerPhotos($connection, $photographer_id) {
    $photos = array(); // Initialize an empty array to store photos
    $photo_content='';
    $photo_id='';
    // Prepare and execute query to fetch photos for the given photographer
    $query = "SELECT id, content FROM Photographs WHERE photographer_id = ?";
    if ($stmt = $connection->prepare($query)) {
        // Bind parameters
        $stmt->bind_param("i", $photographer_id);
        // Execute the query
        $stmt->execute();
        // Bind result variables
        $stmt->bind_result($photo_id, $photo_content);
        
        // Fetch rows and populate photos array
        while ($stmt->fetch()) {
            // Add photo content and ID to the array
            $photos[] = ['id' => $photo_id, 'content' => $photo_content];
        }

        // Close statement
        $stmt->close();
    }

    // Return the array of photos
    return $photos;
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if user is not logged in
    header("Location: login.php");
    exit();
}

$photographer_id = $_SESSION['user_id'];

// Fetch photographer's photos
$photos = fetchPhotographerPhotos($connection, $photographer_id);

// Check if the Photographer_Bio table exists
$table_name = 'Photographer_Bio';
if (!$connection->query("SHOW TABLES LIKE '$table_name'")->num_rows) {
    echo "The table '$table_name' does not exist in the database.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Photographer Page</title>
    <link rel="stylesheet" href="../css/photographer.css">
</head>
<body>
    <header>
        <h1>Photographer Name</h1>
        <p>Professional Photographer</p>
    </header>
    <nav>
        <a href="#bio">Bio</a>
        <a href="#portfolio">Portfolio</a>
        <a href="#sessions">Session Listings</a>
        <a href="#contact">Contact</a>
    </nav>
    <div class="container">
        <section id="bio" class="bio">
            <h2>Bio</h2>
            <p>Insert photographer's bio here.</p>
        </section>
        <section id="portfolio">
            <h2>Portfolio</h2>
            <button id="addPhotoBtn">Add New Photo</button>
            <div class="portfolio">
                <!-- Display fetched photos -->
                <?php foreach ($photos as $photo): ?>
                    <?php $base64_image = base64_encode($photo['content']); ?>
                    <div class="photo-item" data-photo-id="<?= $photo['id'] ?>">
                        <img src="data:image/jpeg;base64,<?= $base64_image ?>" alt="Portfolio Image">
                        <!-- Move the buttons inside this div -->
                        <div class="photo-buttons">
                            <button class="delete-btn">Delete</button>
                            <button class="edit-btn">Edit</button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <form id="uploadForm" method="POST" enctype="multipart/form-data" style="display: none;">
                <input type="file" name="file" id="fileInput">
                <button type="button" id="uploadBtn">Upload</button>
            </form>
        </section>
        <section id="sessions" class="session-listings">
            <h2>Session Listings</h2>
            <ul>
                <li>Session 1 - $XXX</li>
                <li>Session 2 - $XXX</li>
                <!-- Add more session listings as needed -->
            </ul>
        </section>
        <section id="contact" class="contact-form">
            <h2>Contact</h2>
            <p>Use the form below to inquire about booking a session.</p>
            <!-- Add contact form here -->
            <form>
                <label for="name">Name:</label><br>
                <input type="text" id="name" name="name"><br>
                <label for="email">Email:</label><br>
                <input type="email" id="email" name="email"><br>
                <label for="message">Message:</label><br>
                <textarea id="message" name="message" rows="4" cols="50"></textarea><br>
                <input type="submit" value="Submit">
            </form>
        </section>
    </div>
    <footer>
        &copy; 2024 Photographer Name. All rights reserved.
    </footer>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
    $(document).ready(function() {
        // Event listener for the delete button
        $('.portfolio').on('click', '.delete-btn', function() {
            var photoItem = $(this).closest('.photo-item');
            var photoId = photoItem.data('photo-id');
            if (confirm("Are you sure you want to delete the picture?")) {
                $.ajax({
                    url: '../functions/delete_photo.php',
                    type: 'POST',
                    data: { photo_id: photoId },
                    success: function(response) {
                        photoItem.remove(); // Remove the photo item from the DOM
                        alert(response); // Display success message
                    },
                    error: function(xhr, status, error) {
                        alert("Error deleting photo: " + error);
                    }
                });
            }
        });

        // Event listener for the edit button
        $('.portfolio').on('click', '.edit-btn', function() {
            // Handle edit functionality here
        });
    });

    // Function to handle file upload
    document.getElementById('addPhotoBtn').addEventListener('click', function() {
        document.getElementById('uploadForm').style.display = 'block';
    });

    document.getElementById('uploadBtn').addEventListener('click', function() {
        var fileInput = document.getElementById('fileInput');
        var file = fileInput.files[0];
        var formData = new FormData();
        formData.append('file', file);

        var xhr = new XMLHttpRequest();
        xhr.open('POST', '../functions/upload_photo.php', true);
        xhr.onload = function() {
            if (xhr.status == 200) {
                var newPhoto = document.createElement('div');
                newPhoto.className = 'photo-item';
                var img = document.createElement('img');
                img.src = URL.createObjectURL(file);
                img.alt = 'Portfolio Image';
                var photoButtons = document.createElement('div');
                photoButtons.className = 'photo-buttons';
                var deleteBtn = document.createElement('button');
                deleteBtn.className = 'delete-btn';
                deleteBtn.textContent = 'Delete';
                var editBtn = document.createElement('button');
                editBtn.className = 'edit-btn';
                editBtn.textContent = 'Edit';
                photoButtons.appendChild(deleteBtn);
                photoButtons.appendChild(editBtn);
                newPhoto.appendChild(img);
                newPhoto.appendChild(photoButtons);
                document.querySelector('.portfolio').appendChild(newPhoto);
                document.getElementById('uploadForm').style.display = 'none'; // Hide upload form after successful upload
            } else {
                alert('Error uploading file.');
            }
        };
        xhr.send(formData);
    });
</script>

</body>
</html>
