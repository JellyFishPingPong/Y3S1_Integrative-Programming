document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.comment-like-action').forEach(function (element) {
        element.addEventListener('click', function () {
            const commentId = element.getAttribute('data-comment-id'); // Get the comment ID

            console.log("Comment ID: " + commentId);

            // Make an AJAX POST request to the server to like/unlike a comment
            fetch(`/comments/${commentId}/like`, { // Use commentId in the URL
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') // CSRF Token for Laravel
                },
                body: JSON.stringify({
                    comment_id: commentId // Optional, depending on your backend logic
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

                    // Update the like count in the DOM using the actual count from the server
                    const likeCountElement = document.getElementById(`like-text-${commentId}`);

                    // Update the like count or display "Like"
                    if (data.like_count === 0) {
                        likeCountElement.innerText = "Like";
                    } else {
                        likeCountElement.innerText = `${data.like_count} likes`;
                    }

                    // Change the Like/Unlike text or image based on the current state
                    if (data.liked) {
                        likeCountElement.style.color = '#097EFF';
                    } else {
                        likeCountElement.style.color = '#65686C';
                    }
                } else {
                    console.log('Failed to like/unlike the comment.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });
    });
});
