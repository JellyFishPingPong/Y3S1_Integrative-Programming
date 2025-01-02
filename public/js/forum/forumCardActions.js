document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.like-action').forEach(function (element) {
        element.addEventListener('click', function () {
            const forumId = element.getAttribute('data-forum-id');

            console.log("Forum ID: " + forumId);

            // Make an AJAX POST request to the server to like/unlike a post
            fetch(`/forum/${forumId}/like`, { // Use forumId in the URL
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') // CSRF Token for Laravel
                },
                body: JSON.stringify({
                    forum_id: forumId // Optional, depending on your backend logic
                })
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json(); // This line will throw the error if the response is not valid JSON
                })
                .then(data => {
                    console.log(data); // This should print the JSON response
                    if (data.success) {
                        console.log(data.message);
                        const likeCountElement = document.getElementById(`like-count-${forumId}`);

                        // Update the like count in the DOM using the actual count from the server
                        likeCountElement.innerText = `${data.like_count} likes`;

                        // Get the image element by forum ID
                        const likeImage = document.getElementById(`like-image-${forumId}`);
                        // Handle the like/unlike UI changes
                        if (data.liked) {
                            // Post was liked, change the image to "liked-icon.jpg"
                            likeImage.src = likedIconPath;
                        } else {
                            // Post was unliked, change the image back to "like-icon.png"
                            likeImage.src = likeIconPath;
                        }
                    } else {
                        console.log('Failed to like/unlike the post.');
                    }
                }).finally(() => {
                    element.disabled = false; // Re-enable button after request is complete
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        });
    });
});


//comment button navigate 
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.comment-action').forEach(function (element) {
        element.addEventListener('click', function () {
            const forumId = element.getAttribute('data-forum-id');
            // Redirect to /forum/{id}
            window.location.href = `/forum/${forumId}`;
        });
    });
});

document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.share-action').forEach(function (element) {
        element.addEventListener('click', function () {
            const forumId = element.getAttribute('data-forum-id');
            const shareUrl = `http://127.0.0.1:8000/forum/${forumId}`;

            // Copy the shareable link to the clipboard
            navigator.clipboard.writeText(shareUrl)
                .then(() => {
                    console.log('Link copied to clipboard!');
                    showPopup('Link copied to clipboard');
                })
                .catch(err => {
                    console.error('Failed to copy: ', err);
                    showPopup('Failed to copy link');
                });
        });
    });

    // Close popup manually on button click
    document.getElementById('close-popup').addEventListener('click', function () {
        document.getElementById('share-popup').style.display = 'none';
    });
});


