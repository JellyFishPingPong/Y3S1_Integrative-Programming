document.addEventListener('DOMContentLoaded', function () {
    const selectedFiles = []; // Array to hold selected files
    const maxFiles = 10; // Max number of files allowed
    const inputElement = document.getElementById('post-images');
    const previewContainer = document.getElementById('image-preview-container');

    // Trigger file input click when the upload button is clicked
    document.getElementById('upload-image-button').addEventListener('click', function () {
        inputElement.click();
    });

    // Function to handle file input changes
    inputElement.addEventListener('change', function (event) {
        const files = Array.from(event.target.files);

        // Check if the total number of files exceeds the maximum limit
        if (selectedFiles.length + files.length > maxFiles) {
            alert(`You can only upload a maximum of ${maxFiles} images.`);
            return;
        }

        // Add newly selected files to the selectedFiles array
        files.forEach(file => {
            if (!selectedFiles.some(f => f.name === file.name && f.size === file.size)) {
                selectedFiles.push(file); // Add to the list of selected files
                createImagePreview(file); // Create the preview
            }
        });

        // Update the input field with the current list of files
        updateFileInput();
    });

    // Function to create image preview
    function createImagePreview(file) {
        const reader = new FileReader();

        reader.onload = function (e) {
            const previewDiv = document.createElement('div');
            previewDiv.classList.add('image-preview-div');

            const img = document.createElement('img');
            img.src = e.target.result;
            img.classList.add('img-fluid');
            img.style.maxHeight = '100px';
            img.style.width = '100%';
            img.style.objectFit = 'cover';

            // Close button
            const closeButton = document.createElement('div');
            closeButton.classList.add('close-image', 'position-absolute');
            closeButton.innerHTML = `<img src="../../images/forum/x.png" />`;
            
            // Remove the preview and the corresponding file
            closeButton.onclick = function () {
                previewDiv.remove(); // Remove the preview div
                const index = selectedFiles.indexOf(file);
                if (index > -1) {
                    selectedFiles.splice(index, 1); // Remove the file from the array
                }
                updateFileInput(); // Update the file input field
            };

            previewDiv.appendChild(img);
            previewDiv.appendChild(closeButton);
            previewContainer.appendChild(previewDiv);
        };

        reader.readAsDataURL(file);
    }

    // Function to update the input with the current list of files
    function updateFileInput() {
        const dataTransfer = new DataTransfer();

        selectedFiles.forEach(file => {
            dataTransfer.items.add(file); // Add each file back to the input field
        });

        inputElement.files = dataTransfer.files; // Update the file input with the modified file list
    }
});

