<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Session Listings</title>
  <link rel="stylesheet" href="../css/sessions.css">
</head>
<body>
  <div class="container">
    <h1>Session Listings</h1>
    <div class="filters">
      <label for="location">Location:</label>
      <input type="text" id="location">
      <label for="date">Date:</label>
      <input type="text" id="date">
      <label for="price">Price Range:</label>
      <input type="number" id="priceMin" placeholder="Min">
      <span>-</span>
      <input type="number" id="priceMax" placeholder="Max">
      <button id="filterBtn">Filter</button>
    </div>
    <table>
      <tr>
        <th>Title</th>
        <th>Photographer</th>
        <th>Price</th>
        <th>Availability</th>
        <th></th>
      </tr>
      <tr>
        <td>Family Portraits</td>
        <td>Jane Doe</td>
        <td>$200</td>
        <td>March 30 - April 5</td>
        <td><button>Book Now</button></td>
      </tr>
      <tr>
        <td>Landscape</td>
        <td>John Smith</td>
        <td>$150</td>
        <td>April 1 - April 30</td>
        <td><button>Book Now</button></td>
      </tr>
      <tr>
        <td>Newborn Shoot</td>
        <td>Emily Johnson</td>
        <td>$250</td>
        <td>April 5 - April 15</td>
        <td><button>Book Now</button></td>
      </tr>
      <tr>
        <td>Wedding Package</td>
        <td>David Lee</td>
        <td>$1000</td>
        <td>May 1 - May 31</td>
        <td><button>Book Now</button></td>
      </tr>
    </table>
    
  </div>
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
</body>
</html>
