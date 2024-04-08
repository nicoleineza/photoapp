<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Picture</title>
    <link rel="icon" href="../assets/appicon.png">
</head>
<body>
    <h2>Upload Picture</h2>
    <form id="uploadForm" method="post" enctype="multipart/form-data">
        <input type="file" name="file" id="fileInput">
        <button type="submit">Upload</button>
    </form>

    <!-- Display upload status -->
    <div id="uploadStatus"></div>

    <!-- JavaScript to handle form submission -->
    <script>
        document.getElementById("uploadForm").addEventListener("submit", function(event) {
            event.preventDefault();
            var formData = new FormData(this);

            fetch("../functions/add_photoaction.php", {
                method: "POST",
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                document.getElementById("uploadStatus").textContent = data;
            })
            .catch(error => {
                console.error("Error:", error);
            });
        });
    </script>
</body>
</html>
