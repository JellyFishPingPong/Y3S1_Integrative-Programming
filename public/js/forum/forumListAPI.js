$(document).ready(function() {
    // Retrieve the forum from the hidden div
    var posts = JSON.parse($('#post-data').text());
    console.log("Parsed post data:", posts);

    // Loop through each post and make an AJAX request to the external PHP web service
    posts.forEach(function(post) {
        console.log("Sending request for forum ID:", post.forum_id, "Timestamp:", post.created_at);

        $.ajax({
            url: 'http://localhost/myservice/time-difference.php', // Your PHP web service URL
            type: 'GET',
            data: {
                timestamp: post.created_at // Pass the created_at timestamp to the service
            },
            success: function(response) {
                console.log("Raw response for forum ID:", post.forum_id, response);

                // If the response is already an object, skip parsing
                var data = typeof response === "object" ? response : JSON.parse(response);

                console.log("Parsed data for forum ID:", post.forum_id, data);

                // Update the time-text with the returned time difference
                var timeTextElement = $('#time-text-' + post.forum_id);
                if (timeTextElement.length > 0) {
                    timeTextElement.text(data.time_difference);
                } else {
                    console.warn("No time-text element found for forum ID:", post.forum_id);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error("AJAX request failed for forum ID:", post.forum_id, textStatus, errorThrown);
                $('#time-text-' + post.forum_id).text('Error loading time');
            }
        });
    });
});