<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="../css/profile.css">
</head>
<body>

<header>
    <h1>User Dashboard</h1>
</header>

<main>
    <section class="profile">
        <h2>Profile Management</h2>
        <!-- Edit Profile Form -->
        <form action="#" method="post">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            <label for="avatar">Profile Picture:</label>
            <input type="file" id="avatar" name="avatar">
            <button type="submit">Update Profile</button>
        </form>
        <!-- Change Password Form -->
        <form action="#" method="post">
            <label for="current-password">Current Password:</label>
            <input type="password" id="current-password" name="current-password" required>
            <label for="new-password">New Password:</label>
            <input type="password" id="new-password" name="new-password" required>
            <label for="confirm-password">Confirm New Password:</label>
            <input type="password" id="confirm-password" name="confirm-password" required>
            <button type="submit">Change Password</button>
        </form>
        <!-- Delete Account Button -->
        <button id="delete-account">Delete Account</button>
    </section>

    <section class="bookings">
        <h2>Booking Management</h2>
        <!-- Display Bookings -->
        <ul>
            <li>Booking 1 - Date/Time</li>
            <li>Booking 2 - Date/Time</li>
            <!-- Add more bookings dynamically -->
        </ul>
        <!-- Cancel Booking Button -->
        <button id="cancel-booking">Cancel Booking</button>
    </section>

    <section class="reviews">
        <h2>Review Management</h2>
        <!-- Display Reviews -->
        <ul>
            <li>Review 1</li>
            <li>Review 2</li>
            <!-- Add more reviews dynamically -->
        </ul>
        <!-- Respond to Review Form -->
        <form action="#" method="post">
            <textarea name="response" id="response" rows="4" placeholder="Write your response"></textarea>
            <button type="submit">Submit Response</button>
        </form>
    </section>
</main>

<footer>
    <div class="footer-links">
        <a href="#">About Us</a>
        <a href="#">Terms of Service</a>
        <a href="#">Privacy Policy</a>
        <a href="#">Contact Us</a>
    </div>
    <div class="social-icons">
        <!-- Add social media icons (font awesome, for example) -->
        <a href="#"><i class="fab fa-facebook"></i></a>
        <a href="#"><i class="fab fa-twitter"></i></a>
        <a href="#"><i class="fab fa-instagram"></i></a>
    </div>
</footer>

</body>
</html>
