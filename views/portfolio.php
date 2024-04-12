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
<?php

session_start(); // Start the session

// Retrieve user ID from session
$user_id = $_SESSION['user_id'];

// Retrieve user's information
$sql_user_info = "SELECT * FROM Users WHERE user_id = ?";
$stmt_user_info = $connection->prepare($sql_user_info);
$stmt_user_info->bind_param("i", $user_id);
$stmt_user_info->execute();
$result_user_info = $stmt_user_info->get_result();

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
  <title>Photographer Portfolios</title>
  <link rel="stylesheet" href="../css/portfolio.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
  <header>
    <!--<div class="nav-left">
        <h1><img src="../assets/profile.png" alt="Profile Picture" style="width: 50px; height: 50px; border-radius: 50%;"> <?php echo $user_info['username']; ?>!</h1>
    </div>
    <div class="search-box">
        <form action="" method="GET">
            <input type="text" name="query" placeholder="Search photographers...">
            <button type="submit"><i class="fas fa-search"></i></button>
        </form>
    </div>-->
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
        <li><a href="portfolio.php?page=portfolio" id="portfolio"><i class="fas fa-camera"></i> Portfolios</a></li>
        <li><a href="sessions.php?page=sessions" id="sessions"><i class="fas fa-check-circle"></i> Sessions</a></li>
        <li><a href="profile.php?page=profile" id="profile"><i class="fas fa-user"></i> Profile</a></li>
        <li><a href="../login/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
    </ul>
  </nav>


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
  <script src="../js/portfolio.js"></script>
</body>
</html>