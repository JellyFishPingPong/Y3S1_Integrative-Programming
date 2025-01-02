

// Function to handle replying to a comment
function replyToComment(commentId, username) {
    // Set the parent comment ID in the hidden input field
    document.getElementById('parent-comment-id').value = commentId;

    // Show the "Replying to..." message
    document.getElementById('replying-to').style.display = 'block';
    document.getElementById('replying-to-username').innerText = username;

    // Focus on the comment input box
    document.getElementById('comment-textarea').focus();
}

// Function to cancel the reply
document.getElementById('cancel-reply').addEventListener('click', function(event) {
    event.preventDefault();

    // Clear the parent comment ID
    document.getElementById('parent-comment-id').value = '';

    // Hide the "Replying to..." message
    document.getElementById('replying-to').style.display = 'none';
});