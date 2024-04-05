<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Product Upload Form</title>
</head>
<body>

<h2>Product Upload Form</h2>

<form id="productForm" action="upload_picture.php" method="post" enctype="multipart/form-data">
    <label for="productName">picture description:</label><br>
    <input type="text" id="productName" name="productName" required><br><br>

    <label for="productImage">choose Image from files to upload:</label><br>
    <input type="file" id="productImage" name="productImage" accept="image/*" required><br><br>

    <label for="isForSale">Do you intend to sell this image??</label><br>
    <input type="radio" id="yes" name="isForSale" value="1" required onclick="togglePriceInput()">
    <label for="yes">Yes</label>
    <input type="radio" id="no" name="isForSale" value="0" onclick="togglePriceInput()">
    <label for="no">No</label><br><br>

    <div id="priceInput" style="display:none;">
        <label for="productPrice">Enter Price for your Image:</label><br>
        <input type="number" id="productPrice" name="productPrice" step="0.01" min="0"><br><br>
    </div>

    <?php
    session_start();
    if (isset($_SESSION['user_id'])) {
        echo '<input type="hidden" name="photographerId" value="' . $_SESSION['user_id'] . '">';
    } else {
        echo '<p>Please log in to upload products</p>';
    }
    ?>

    <input type="submit" value="Submit">
</form>

<script>
function togglePriceInput() {
    var isForSale = document.getElementById("yes").checked;
    var priceInput = document.getElementById("priceInput");
    if (isForSale) {
        priceInput.style.display = "block";
    } else {
        priceInput.style.display = "none";
    }
}
</script>

</body>
</html>
