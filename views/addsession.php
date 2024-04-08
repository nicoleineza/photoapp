<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Session</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="icon" href="../assets/appicon.png">
</head>
<body>
    <header>
        <h1>Add New Session</h1>
        <nav>
            <a href="photographer.php">Back to Photographer Page</a>
        </nav>
    </header>
    <div class="container">
        <form action="../functions/create_session.php" method="post">
            <label for="session_name">Session Name:</label><br>
            <input type="text" id="session_name" name="session_name" required><br>
            <label for="description">Description:</label><br>
            <textarea id="description" name="description" rows="4" cols="50" required></textarea><br>
            <label for="date">Date:</label><br>
            <input type="date" id="date" name="date" required><br>
            <label for="location">Location:</label><br>
            <input type="text" id="location" name="location" required><br>
            <label for="price">Price:</label><br>
            <input type="number" id="price" name="price" step="0.01" required><br>
            <input type="submit" value="Add Session">
        </form>
    </div>
</body>
</html>
