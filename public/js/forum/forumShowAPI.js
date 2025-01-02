
$(document).ready(function () {
    // Retrieve the comment data and forum from the hidden div
    var comments = JSON.parse($('#comment-data').text());
    var post = JSON.parse($('#forum-data').text());

    console.log("Parsed post data:", post);

    $.ajax({
        url: 'http://localhost/myservice/time-difference.php',
        type: 'GET',
        data: {
            timestamp: post.created_at
        },
        success: function (response) {
            if (typeof response === "object") {
                var data = response;
            } else {
                var data = JSON.parse(response);
            }

            $('#time-text-' + post.forum_id).text(data.time_difference);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            $('#time-text-' + post.forum_id).text('Error loading time');
        }
    });

    // Loop through each comment and make an AJAX request to the external PHP web service
    comments.forEach(function (comment) {
        // Make AJAX request for parent comments
        console.log("Sending request for comment ID:", comment.comment_id, "Timestamp:", comment.created_at);

        $.ajax({
            url: 'http://localhost/myservice/time-difference.php', // Your PHP web service URL
            type: 'GET',
            data: {
                timestamp: comment.created_at // Pass the created_at timestamp to the service
            },
            success: function (response) {
                // If the response is already an object, skip parsing
                if (typeof response === "object") {
                    var data = response;
                } else {
                    var data = JSON.parse(response);
                }

                console.log("Response for comment ID:", comment.comment_id, "Data:", data);

                // Update the time-text with the returned time difference
                $('#time-text-' + comment.comment_id).text(data.time_difference);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.error("AJAX request failed for comment ID:", comment.comment_id, textStatus, errorThrown);
                $('#time-text-' + comment.comment_id).text('Error loading time');
            }
        });

        // If the comment has children (replies), loop through them
        if (comment.children.length > 0) {
            comment.children.forEach(function (child) {
                console.log("Sending request for child comment ID:", child.comment_id, "Timestamp:", child.created_at);

                $.ajax({
                    url: 'http://localhost/myservice/time-difference.php', // PHP web service for children
                    type: 'GET',
                    data: {
                        timestamp: child.created_at
                    },
                    success: function (response) {
                        // If the response is already an object, skip parsing
                        if (typeof response === "object") {
                            var data = response;
                        } else {
                            var data = JSON.parse(response);
                        }

                        console.log("Response for child comment ID:", child.comment_id, "Data:", data);

                        // Update the time-text for child comments
                        $('#time-text-' + child.comment_id).text(data.time_difference);
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.error("AJAX request failed for child comment ID:", child.comment_id, textStatus, errorThrown);
                        $('#time-text-' + child.comment_id).text('Error loading time');
                    }
                });
            });
        }
    });
});