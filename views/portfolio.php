<?php
include("../settings/config.php");
include("../functions/fetch_all.php");

// Function to fetch comments for a given image ID
function fetchComments($connection, $imageId) {
    $sql = "SELECT * FROM Reviews WHERE image_id = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("i", $imageId);
    $stmt->execute();
    $result = $stmt->get_result();
    $comments = [];
    while ($row = $result->fetch_assoc()) {
        $comments[] = $row['comment'];
    }
    return $comments;
}

$photos = fetchAllPhotosWithPhotographerNames($connection);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Photographer Portfolios</title>
  <!-- External CSS link -->
  <link rel="stylesheet" href="../css/portfolio.css">
</head>
<body>
  <!-- Header Section -->
  <header>
    <div class="user-profile">
      <img src="profile-picture.jpg" alt="Profile Picture">
      <span class="username">John Doe</span>
    </div>
    <nav>
      <ul class="navigation-links">
        <li><a href="pdashboard.php"><i class="fas fa-home"></i> Home</a></li>
        <li><a href="portfolio.php"><i class="fas fa-home"></i> Portfolios</a></li>
        <li><a href="profile.php"><i class="fas fa-user"></i> Profile</a></li>
        <li><a href="settings.php"><i class="fas fa-cog"></i> Settings</a></li>
        <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
      </ul>
    </nav>
  </header>

  <!-- Main Content Section -->
  <main>
    <!-- Filters Section -->
    <section class="filters">
      <label>Filter by:
        <select id="filter">
          <option value="all">All</option>
          <option value="landscape">Landscape</option>
          <option value="portrait">Portrait</option>
          <option value="macro">Macro</option>
          <!-- Add more options as needed -->
        </select>
      </label>
    </section>

    <!-- Sorting Section -->
    <section class="sorting">
      <label>Sort by:
        <select id="sort">
          <option value="popularity">Popularity</option>
          <option value="date">Date</option>
          <option value="rating">Rating</option>
        </select>
      </label>
    </section>

    <!-- Portfolio Grid Section -->
    <section class="portfolio-grid" id="portfolioGrid">
      <?php foreach ($photos as $photo): ?>
        <div class="portfolio-item" data-photographer-id="<?= $photo['photographer_id'] ?>" data-image-id="<?= $photo['id'] ?>">
          <img src="data:image/jpeg;base64,<?= base64_encode($photo['productImage']) ?>" alt="<?= $photo['productName'] ?>">
          <div class="portfolio-details">
            <h3><?= $photo['productName'] ?></h3>
            <p>Photographer: <?= $photo['photographer_name'] ?></p>
          </div>
          <div class="button-container">
            <?php if ($photo['isForSale']): ?>
              <button class="buy-button">Buy</button>
            <?php endif; ?>
            <button class="review-button">Review Photographer</button>
            <button class="comments-button">View Comments</button>
          </div>
          <div class="comments-container" style="display: none;">
            <!-- Display comments for this image -->
            <?php
            $imageComments = fetchComments($connection, $photo['id']);
            if (!empty($imageComments)) {
              foreach ($imageComments as $comment) {
                echo "<p>$comment</p>";
              }
            } else {
              echo "<p>No comments yet.</p>";
            }
            ?>
          </div>
        </div>
      <?php endforeach; ?>
    </section>

    <!-- Review Form Section -->
    <div class="review-form-container">
      <form id="reviewForm" style="display: none;" method="post" action="../functions/review.php">
        <input type="hidden" id="photographerId" name="photographer_id">
        <input type="hidden" id="imageId" name="image_id">
        <label for="rating">Rating:</label>
        <select id="rating" name="rating">
          <option value="1">1 Star</option>
          <option value="2">2 Stars</option>
          <option value="3">3 Stars</option>
          <option value="4">4 Stars</option>
          <option value="5">5 Stars</option>
        </select>
        <label for="comment">Comment:</label>
        <textarea id="comment" name="comment" rows="4"></textarea>
        <button type="submit">Submit Review</button>
      </form>
      <div id="reviewMessage" class="success-message" style="display: none;"></div>
    </div>
  </main>

  <!-- Footer Section -->
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

  <!-- External JavaScript -->
  <script src="../js/portfolio.js"></script>
</body>
</html>