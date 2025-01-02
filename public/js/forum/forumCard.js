// Store the current slide index for each forum
let slideIndexes = {};

// Initialize the slideshows
document.addEventListener('DOMContentLoaded', function () {
    const slideshows = document.querySelectorAll('.slideshow-container');

    slideshows.forEach((slideshow) => {
        const forumId = slideshow.id.split('-')[1]; // Extract forum ID from the ID
        slideIndexes[forumId] = 1;  // Set initial slide index for each slideshow
        showSlides(forumId, slideIndexes[forumId]);
    });
});

// Function to increment/decrement the slide index
function plusSlides(forumId, n) {
    showSlides(forumId, slideIndexes[forumId] += n);
}

// Function to display the current slide
function showSlides(forumId, n) {
    let slides = document.querySelectorAll('.mySlides-' + forumId);

    if (n > slides.length) {
        slideIndexes[forumId] = 1;
    }

    if (n < 1) {
        slideIndexes[forumId] = slides.length;
    }

    // Hide all slides for this forum
    for (let i = 0; i < slides.length; i++) {
        slides[i].style.display = "none";
    }

    // Show the current slide
    slides[slideIndexes[forumId] - 1].style.display = "flex";
}


/*see more feature */
document.addEventListener('DOMContentLoaded', function () {
    // Select all the truncated-content divs and their corresponding see-more buttons
    var contentBlocks = document.querySelectorAll('.truncated-content');
    var seeMoreButtons = document.querySelectorAll('.see-more');

    contentBlocks.forEach(function (contentBlock, index) {
        var seeMoreButton = seeMoreButtons[index]; // Corresponding see-more button

        // Get the line height and height of the content block
        var lineHeight = parseInt(window.getComputedStyle(contentBlock).lineHeight);
        var contentHeight = contentBlock.scrollHeight;
        var maxHeight = lineHeight * 5; // Maximum height for 5 lines

        // If content exceeds 5 lines, show the "See More" button
        if (contentHeight > maxHeight) {
            seeMoreButton.style.display = 'inline-block';
        }
    });
});


// Toggle dropdown menu
function toggleDropdown(forumId) {
    const dropdown = document.getElementById(`dropdown-menu-${forumId}`);
    dropdown.style.display = dropdown.style.display === 'none' ? 'block' : 'none';
}

// Close the dropdown if clicked outside
document.addEventListener('click', function (e) {
    var menuContainers = document.querySelectorAll('.menu-container');
    menuContainers.forEach(function (container) {
        if (!container.contains(e.target)) {
            var dropdown = container.querySelector('.dropdown-menu');
            dropdown.style.display = 'none';
        }
    });
});



document.addEventListener('DOMContentLoaded', function () {
    // Function to handle follow/unfollow logic
    function followUser(userId) {
        fetch(`/follow/${userId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                user_id: userId
            })
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.querySelectorAll(`.follow-btn[data-user-id="${userId}"]`).forEach(button => {
                        if (data.following) {
                            button.innerText = 'Following';
                            button.classList.remove('btn-blue');
                            button.classList.add('btn-gray');
                        } else {
                            button.innerText = 'Follow';
                            button.classList.remove('btn-gray');
                            button.classList.add('btn-blue');
                        }
                    });

                    // If unfollowing, update "People you follow" section by removing the entry
                    if (!data.following) {
                        document.querySelectorAll(`.following[data-user-id="${userId}"]`).forEach(element => {
                            console.log(element)
                            element.remove();
                        });
                    } else {
                        // If following, add the user to the "People you follow" section
                        const followingsContainer = document.querySelector('.followings');
                        if (!document.querySelector(`.following[data-user-id="${userId}"]`)) {
                            const followingDiv = document.createElement('div');
                            followingDiv.classList.add('following', 'flex-r', 'sidebar-list');
                            followingDiv.setAttribute('data-user-id', userId);

                            followingDiv.innerHTML = `
                            <div class="prof-pic-side">
                                <img src="${data.picture ? data.picture : '/images/forum/test.jpeg'}" alt="Profile Picture">
                            </div>
                            <div class="forum-username">${data.username}</div>
                        `;

                            followingsContainer.insertBefore(followingDiv, followingsContainer.firstChild);
                        }
                    }
                }
            })
            .catch(error => console.error('Error:', error));
    }

    // Attach the event listener to all follow buttons
    document.querySelectorAll('.follow-btn').forEach(button => {
        const userId = button.getAttribute('data-user-id');
        button.addEventListener('click', function () {
            followUser(userId);
        });
    });
});




