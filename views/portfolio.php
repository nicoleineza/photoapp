
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Photographer Portfolios</title>
  <link rel="stylesheet" href="../css/portfolio.css">
</head>
<body>
  <header>
    <h1>Photographer Portfolios</h1>
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
      <!-- Portfolio items will be dynamically added here -->
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
  <script>document.addEventListener("DOMContentLoaded", function() {
    const filterSelect = document.getElementById("filter");
    const sortSelect = document.getElementById("sort");
    const portfolioGrid = document.getElementById("portfolioGrid");
    const lightbox = document.getElementById("lightbox");
    const lightboxImage = document.getElementById("lightboxImage");

    // Sample portfolio data (replace with your own)
    const portfolios = [
      { title: "Photo 1", category: "landscape", image: "image1.jpg" },
      { title: "Photo 2", category: "portrait", image: "image2.jpg" },
      { title: "Photo 3", category: "macro", image: "image3.jpg" },
      // Add more portfolio items as needed
    ];

    // Function to render portfolio items
    function renderPortfolio(portfolios) {
      portfolioGrid.innerHTML = "";
      portfolios.forEach(portfolio => {
        const portfolioItem = document.createElement("div");
        portfolioItem.classList.add("portfolio-item");
        portfolioItem.innerHTML = `
          <img src="${portfolio.image}" alt="${portfolio.title}">
          <h3>${portfolio.title}</h3>
        `;
        portfolioItem.addEventListener("click", () => {
          lightboxImage.src = portfolio.image;
          lightbox.style.display = "flex";
        });
        portfolioGrid.appendChild(portfolioItem);
      });
    }

    // Filter portfolios based on selected category
    filterSelect.addEventListener("change", function() {
      const category = this.value;
      const filteredPortfolios = category === "all" ? portfolios : portfolios.filter(portfolio => portfolio.category === category);
      renderPortfolio(filteredPortfolios);
    });

    // Sort portfolios based on selected criteria
    sortSelect.addEventListener("change", function() {
      const criteria = this.value;
      const sortedPortfolios = [...portfolios].sort((a, b) => {
        if (criteria === "popularity") {
          // Implement popularity sorting logic
        } else if (criteria === "date") {
          // Implement date sorting logic
        } else if (criteria === "rating") {
          // Implement rating sorting logic
        }
      });
      renderPortfolio(sortedPortfolios);
    });

    // Close lightbox when close button is clicked
    lightbox.addEventListener("click", function(e) {
      if (e.target === this || e.target.classList.contains("close-lightbox")) {
        lightbox.style.display = "none";
      }
    });

    // Initial rendering of portfolio items
    renderPortfolio(portfolios);
  });
  </script>
</body>
</html>
