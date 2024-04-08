<?php
include("../settings/config.php");
include("../functions/fetch_all.php");

$photos = fetchAllPhotosWithPhotographerNames($connection);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Photographer Portfolios</title>
  <style>
    /* Add CSS styles for responsiveness */
    .portfolio-item img {
      max-width: 100%;
      height: auto;
    }
  </style>
  <link rel="stylesheet" href="../css/portfolio.css">
</head>
<body>
<header>
    <div class="user-profile">
        <!-- Assuming the profile picture is stored in "profile-picture.jpg" -->
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
  <main>
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
    <section class="sorting">
      <label>Sort by:
        <select id="sort">
          <option value="popularity">Popularity</option>
          <option value="date">Date</option>
          <option value="rating">Rating</option>
        </select>
      </label>
    </section>
    <section class="portfolio-grid" id="portfolioGrid">
      <?php foreach ($photos as $photo): ?>
        <div class="portfolio-item" data-category="<?= $photo['category'] ?>">
          <img src="data:image/jpeg;base64,<?= base64_encode($photo['productImage']) ?>" alt="<?= $photo['productName'] ?>">
          <h3><?= $photo['productName'] ?></h3>
          <p>Photographer: <?= $photo['photographer_name'] ?></p>
          <?php if ($photo['isForSale']): ?>
            <button class="buy-button">Buy</button>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>
    </section>
    <div class="lightbox" id="lightbox">
      <span class="close-lightbox">&times;</span>
      <img src="" alt="Full-size Image" id="lightboxImage">
    </div>
  </main>
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
  <script>
    document.addEventListener("DOMContentLoaded", function() {
      const filterSelect = document.getElementById("filter");
      const sortSelect = document.getElementById("sort");
      const portfolioGrid = document.getElementById("portfolioGrid");
      const lightbox = document.getElementById("lightbox");
      const lightboxImage = document.getElementById("lightboxImage");

      // Function to filter portfolios based on selected category
      filterSelect.addEventListener("change", function() {
        const category = this.value;
        const portfolioItems = portfolioGrid.querySelectorAll(".portfolio-item");
        portfolioItems.forEach(item => {
          const itemCategory = item.getAttribute("data-category");
          if (category === "all" || itemCategory === category) {
            item.style.display = "block";
          } else {
            item.style.display = "none";
          }
        });
      });

      // Function to close lightbox when close button is clicked
      lightbox.addEventListener("click", function(e) {
        if (e.target === this || e.target.classList.contains("close-lightbox")) {
          lightbox.style.display = "none";
        }
      });

      // Add event listener to each portfolio item to display lightbox
      const portfolioItems = portfolioGrid.querySelectorAll(".portfolio-item");
      portfolioItems.forEach(item => {
        item.addEventListener("click", () => {
          lightboxImage.src = item.querySelector("img").src;
          lightbox.style.display = "flex";
        });
      });
    });
  </script>
</body>
</html>
