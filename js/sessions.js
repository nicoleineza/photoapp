// Function to open the delete confirmation modal
function openDeleteModal(bookingId) {
    var modal = document.getElementById("deleteConfirmationModal");
    modal.style.display = "block";
    document.getElementById("confirmDelete").setAttribute("data-booking-id", bookingId);
}

// Function to open the book confirmation modal
function openBookModal(sessionId) {
    var modal = document.getElementById("bookConfirmationModal");
    modal.style.display = "block";
    document.getElementById("confirmBook").setAttribute("data-session-id", sessionId);
}

// Function to close the modal
document.querySelectorAll(".close").forEach(function(closeButton) {
    closeButton.onclick = function() {
        var modal = this.parentElement.parentElement;
        modal.style.display = "none";
    }
});

// Function to handle deletion confirmation
document.getElementById("confirmDelete").onclick = function() {
    var modal = document.getElementById("deleteConfirmationModal");
    modal.style.display = "none";
    var bookingId = this.getAttribute("data-booking-id");
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            window.location.reload();
        }
    };
    xhr.open("POST", "../functions/delete_booking.php", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.send("booking_id=" + bookingId);
}

// Function to handle cancellation of deletion
document.getElementById("cancelDelete").onclick = function() {
    var modal = document.getElementById("deleteConfirmationModal");
    modal.style.display = "none";
}

// Function to handle booking confirmation
document.getElementById("confirmBook").onclick = function() {
    var modal = document.getElementById("bookConfirmationModal");
    modal.style.display = "none";
    var sessionId = this.getAttribute("data-session-id");
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            alert("Session booked! Session ID: " + sessionId);
            window.location.reload();
        }
    };
    xhr.open("POST", "../functions/book_session.php", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.send("session_id=" + sessionId);
}

// Function to handle cancellation of booking
document.getElementById("cancelBook").onclick = function() {
    var modal = document.getElementById("bookConfirmationModal");
    modal.style.display = "none";
}