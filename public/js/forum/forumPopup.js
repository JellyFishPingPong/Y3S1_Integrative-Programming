
/**
 * Reusable function to display the popup with a custom message.
 *
 * @param {string} message - The message to display in the popup.
 */
function showPopup(message, timeout = 3) {
    const popup = document.getElementById('share-popup');
    const popupText = document.getElementById('share-link');

    // Set the custom message inside the popup
    popupText.innerHTML = message;

    // Show the popup
    popup.style.display = 'flex';

    console.log("called with message " + message + popupText + popup);
    // Automatically hide the popup after 3 seconds (3000 milliseconds)
    setTimeout(function () {
        popup.style.display = 'none';
    }, 1000 * timeout); // Adjust the time as per your requirement
}
