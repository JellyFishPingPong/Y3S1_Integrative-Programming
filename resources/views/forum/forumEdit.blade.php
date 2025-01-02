@extends('layouts.forumLayout') <!-- Assuming you have a layout to extend -->

@section('title', 'Edit Post')

@section('header')
<link rel="stylesheet" href="{{ asset('css/forum/forumCreate.css') }}">

@section('forum-content')
<div class="600-px" style="max-width: 600px;">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="createPostModalLabel">Edit Post</h5>
        </div>
        <div class="modal-body">
        <form id="forum-form" action="{{ route('forum.update', ['id' => $post->forum_id]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT') <!-- For the update method -->

                <!-- Title -->
                <div class="form-group">
                    <label for="post-title">Title</label>
                    <textarea class="form-control" id="post-title" name="title" rows="1" maxlength="255" required>{{ $post->title }}</textarea>
                </div>

                <!-- Tags -->
                <div class="form-group">
                    <label for="post-title">Tag (optional - make posts more accessible)</label>
                    <textarea class="form-control" id="post-tag" name="tag" rows="1" placeholder="Maximum 3 tags (separate with space)">{{ implode(' ', $post->tags->pluck('tag_name')->toArray()) }}</textarea>
                </div>

                <!-- Content -->
                <div class="form-group">
                    <label for="post-content">Content</label>
                    <textarea class="form-control" id="post-content" name="content" rows="5" placeholder="Write your post here...">{{ $post->content }}</textarea>
                </div>

                <div id="existing-images-data" style="display: none;">
                    {{ json_encode($existingImages) }}
                </div>

                <!-- Image Preview Container -->
                <div id="image-preview-container"></div> <!-- Image preview container for existing and new images -->

                <div class="flex-r create-bottom">
                    <div class="">
                        <button type="button" class="btn btn-primary" id="upload-image-button">Upload Image</button>
                        <input type="file" class="d-none" id="post-images" name="images[]" multiple accept="image/*"> <!-- Hidden input -->
                    </div>
                    <button type="submit" class="btn btn-primary">Update Post</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectedFiles = []; // Array to hold newly selected files
        const existingFiles = []; // Array to hold existing files
        const deletedFiles = []; // Array to track deleted existing files
        const maxFiles = 10; // Max number of files allowed
        const inputElement = document.getElementById('post-images');
        const previewContainer = document.getElementById('image-preview-container');

        // Load existing images from the server
        console.log(document.getElementById('forum-form'));
        const existingImagesData = document.getElementById('existing-images-data').innerText;
        const existingImages = JSON.parse(existingImagesData);

        // Load existing images into the preview and track them
        existingImages.forEach(imageUrl => {
            existingFiles.push(imageUrl); // Add to the existing files array
            createImagePreviewFromUrl(imageUrl); // Preview the existing images
        });

        // Trigger file input click when the upload button is clicked
        document.getElementById('upload-image-button').addEventListener('click', function() {
            inputElement.click();
        });

        // Function to handle file input changes (for newly selected files)
        inputElement.addEventListener('change', function(event) {
            const files = Array.from(event.target.files);

            // Combine the existing files and selected files count
            const totalFiles = existingFiles.length + selectedFiles.length + files.length;

            // Check if the total number of files exceeds the maximum limit
            if (totalFiles > maxFiles) {
                alert(`You can only upload a maximum of ${maxFiles} images.`);
                return;
            }

            // Add newly selected files to the selectedFiles array
            files.forEach(file => {
                if (!selectedFiles.some(f => f.name === file.name && f.size === file.size)) {
                    selectedFiles.push(file); // Add to the list of selected files
                    createImagePreview(file); // Create the preview for the new files
                }
            });

            // Update the input field with the current list of files
            updateFileInput();
        });

        // Function to create image preview for newly selected files
        function createImagePreview(file) {
            const reader = new FileReader();

            reader.onload = function(e) {
                const previewDiv = document.createElement('div');
                previewDiv.classList.add('image-preview-div');

                const img = document.createElement('img');
                img.src = e.target.result;
                img.classList.add('img-fluid');
                img.style.maxHeight = '100px';
                img.style.width = '100%';
                img.style.objectFit = 'cover';

                // Close button for newly selected file
                const closeButton = document.createElement('div');
                closeButton.classList.add('close-image', 'position-absolute');
                closeButton.innerHTML = `<img src="../../images/forum/x.png" />`;

                // Remove the preview and the corresponding file
                closeButton.onclick = function() {
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

        // Function to create image preview for existing images
        function createImagePreviewFromUrl(imageUrl) {
            const previewDiv = document.createElement('div');
            previewDiv.classList.add('image-preview-div');

            const img = document.createElement('img');
            img.src = imageUrl;
            img.classList.add('img-fluid');
            img.style.maxHeight = '100px';
            img.style.width = '100%';
            img.style.objectFit = 'cover';

            // Close button for existing image
            const closeButton = document.createElement('div');
            closeButton.classList.add('close-image', 'position-absolute');
            closeButton.innerHTML = `<img src="../../images/forum/x.png" />`;

            // Remove the preview and mark the image for deletion
            closeButton.onclick = function() {
                previewDiv.remove(); // Remove the preview div
                const index = existingFiles.indexOf(imageUrl);
                if (index > -1) {
                    existingFiles.splice(index, 1); // Remove the image from the existing files array
                    deletedFiles.push(imageUrl); // Add the image URL to the deleted files array
                    console.log(imageUrl + " deleted");
                }
            };

            previewDiv.appendChild(img);
            previewDiv.appendChild(closeButton);
            previewContainer.appendChild(previewDiv);
        }

        // Function to update the input with the current list of newly selected files
        function updateFileInput() {
            const dataTransfer = new DataTransfer();

            selectedFiles.forEach(file => {
                dataTransfer.items.add(file); // Add each new file to the input field
            });

            inputElement.files = dataTransfer.files; // Update the file input with the modified file list
        }


        const formElement = document.getElementById('forum-form'); // Or use ID if needed

        formElement.addEventListener('submit', function(event) {

            console.log("hi"); // Check if the event is firing

            const deletedFilesInput = document.createElement('input');
            deletedFilesInput.type = 'hidden';
            deletedFilesInput.name = 'deleted_files';
            deletedFilesInput.value = JSON.stringify(deletedFiles); // Pass the deleted files as JSON
            this.appendChild(deletedFilesInput);

            console.log('Deleted files:', deletedFiles); // Log the deleted files array
            console.log('Hidden input value:', deletedFilesInput.value); // Check the input value

            // Check if the hidden input has been appended
            console.log('Form content with new input:', this);
        });
    });

    document.addEventListener('DOMContentLoaded', function() {});
</script>
@endsection