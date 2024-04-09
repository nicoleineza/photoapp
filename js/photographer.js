
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
    

