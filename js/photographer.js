$(document).ready(function() {
    // Function to fetch and display sessions
    function fetchAndDisplaySessions() {
        $.ajax({
            url: '../functions/fetch_sessions.php',
            type: 'GET',
            success: function(response) {
                console.log("Sessions response:", response); // Log the response received
                $("#sessions").html(response); // Update the sessions section with the fetched data
            },
            error: function(xhr, status, error) {
                console.error("Error fetching sessions:", error);
                if (xhr.status !== 0) { // Check if there's an actual error response
                    alert("Error fetching sessions. Please try again later.");
                }
            }
        });
    }

    // Event listener for the "Add New Session" button
    $("#addSessionBtn").click(function() {
        $("#addSessionModal").show(); // Show the add session modal
    });

    // Event listener for closing the modals
    $(".close").click(function() {
        $(this).closest('.modal').hide(); // Close the modal when clicking on the close button
    });

    // Submit handler for adding new session form
    $("#addSessionForm").submit(function(e) {
        e.preventDefault();
        // Get form data
        var formData = $(this).serialize();

        // Perform AJAX request to add new session
        $.ajax({
            url: '../functions/create_session.php',
            type: 'POST',
            data: formData,
            success: function(response) {
                console.log("Session added:", response);
                alert(response); // Display success message
                $("#addSessionModal").hide(); // Hide the modal after adding session
                fetchAndDisplaySessions(); // Fetch and display updated sessions
            },
            error: function(xhr, status, error) {
                console.error("Error adding session:", error);
                alert("Error adding session. Please try again later.");
            }
        });
    });

    // Event listener for edit buttons
    $(".edit-btn").click(function() {
        // Get photo details
        var photoItem = $(this).closest('.photo-item');
        var photoId = photoItem.data('photo-id');
        var description = photoItem.find('.description').text();
        var isForSale = photoItem.find('p:contains("This product is for sale")').length > 0 ? 1 : 0;
        var price = photoItem.find('.price').text().replace("Price: $", "").trim();

        // Populate modal fields with current photo details
        $("#editPhotoId").val(photoId);
        $("#editDescription").val(description);
        $("#editIsForSale").val(isForSale);
        $("#editPrice").val(price);

        // Show the edit photo modal
        $("#editPhotoModal").show();
    });

    // Event listener for delete buttons
    $(".delete-btn").click(function() {
        if (confirm("Are you sure you want to delete this photo?")) {
            var photoItem = $(this).closest('.photo-item');
            var photoId = photoItem.data('photo-id');

            // Perform AJAX request to delete the photo
            $.ajax({
                url: '../functions/delete_photo.php',
                type: 'POST',
                data: { photoId: photoId },
                success: function(response) {
                    console.log("Photo deleted:", response);
                    photoItem.remove(); // Remove the deleted photo item from the page
                },
                error: function(xhr, status, error) {
                    console.error("Error deleting photo:", error);
                    alert("Error deleting photo. Please try again later.");
                }
            });
        }
    });

    
});