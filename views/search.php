<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Photographer Search</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Photographer Search</h1>
        <form id="searchForm">
            <input type="text" id="searchQuery" name="query" placeholder="Enter photographer username...">
            <button type="submit">Search</button>
        </form>
        <div id="results" class="results">
            <!-- Search results will be displayed here -->
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script> $(document).ready(function() {
    $('#searchForm').submit(function(e) {
        e.preventDefault(); // Prevent form submission

        var query = $('#searchQuery').val(); // Get the search query from the input field

        // Send AJAX request to the backend
        $.get('search.php', { query: query }, function(data) {
            displaySearchResults(data); // Display search results
        });
    });
});

function displaySearchResults(results) {
    var resultsContainer = $('#results');

    // Clear previous search results
    resultsContainer.empty();

    // Check if any photographers are found
    if (results.length > 0) {
        // Iterate through each photographer found
        $.each(results, function(index, photographer) {
            var photographerCard = $('<div class="result-card"></div>');

            // Display photographer's username
            var username = $('<h3></h3>').text('Username: ' + photographer.username);
            photographerCard.append(username);

            // Display photographer's profile picture
            var profilePicture = $('<img>').attr('src', photographer.profile_picture);
            photographerCard.append(profilePicture);

            // Display photographer's active sessions
            if (photographer.sessions.length > 0) {
                var sessionsList = $('<ul></ul>');
                $.each(photographer.sessions, function(index, session) {
                    var sessionItem = $('<li></li>').text('Session Name: ' + session.session_name + ', Date: ' + session.date + ', Location: ' + session.location);
                    sessionsList.append(sessionItem);
                });
                photographerCard.append('<h4>Active Sessions:</h4>');
                photographerCard.append(sessionsList);
            } else {
                photographerCard.append('<p>No active sessions available.</p>');
            }

            resultsContainer.append(photographerCard);
        });
    } else {
        resultsContainer.append('<p>No photographers found.</p>');
    }
}
</script>
</body>
</html>
