<!-- resources/views/products/create.blade.php -->

@extends('layouts.forumLayout') <!-- This links the view to the layout -->

@section('title', 'Forum') <!-- This provides the title for the page -->

@section('forum-content') <!-- This section is injected into the @yield('content') of the layout -->

<!-- Modal -->
<div class="modal fade" id="createPostModal" tabindex="-1" aria-labelledby="createPostModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createPostModalLabel">Create Post</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('forum.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="post-title">Title</label>
                        <textarea class="form-control" id="post-title" name="title" rows="1" maxlength="255" placeholder="Enter title (max 255 characters)" required></textarea>
                    </div>

                    <div class="form-group">
                        <label for="post-title">Tag (optional - make posts more accessible)</label>
                        <textarea class="form-control" id="post-tag" name="tag" rows="1" placeholder="Maximum 3 tags (separate with space)"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="post-content">Content</label>
                        <textarea class="form-control" id="post-content" name="content" rows="5" placeholder="Write your post here..."></textarea>
                    </div>

                    <div id="image-preview-container"></div> <!-- Image preview container -->
                    <div class="flex-r create-bottom">
                        <div class="">
                            <button type="button" class="btn btn-primary" id="upload-image-button">Upload Image</button>
                            <input type="file" class="d-none" id="post-images" name="images[]" multiple accept="image/*"> <!-- Hidden input -->
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>


<x-report-modal />
<!-- Main content here -->
<div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <h2 class="forum-header">Explore</h2>

    <div class="forum-top flex-r">
        <!-- Highlight "Latest" when the current URL is /forum -->
        <div class="forum-category {{ request()->is('forum') ? 'selected' : '' }}" onclick="window.location.href=`{{ route('forum.index') }}`">Latest</div>

        <!-- Highlight "Following" when the current URL is /forum/following -->
        <div class="forum-category {{ request()->is('forum/following') ? 'selected' : '' }}" onclick="window.location.href=`{{ route('forum.following') }}`">Following</div>
    </div>

    <div class="border-card">
        <div class="full-pad-card flex-c">
            <h4 style="margin-left:5px">Search by tag</h4>
            <form class="forum-search-bar" onsubmit="return searchByTag()" style="flex-grow: 1;">
                <input type="text" id="search-tag" placeholder="Enter a tag..." style="width: 100%;" required>
                <button type="submit" style="display: none;"></button> <!-- Hidden button to enable 'Enter' key submission -->
            </form>
        </div>

    </div>

    <script>
        function searchByTag() {
            var tag = document.getElementById('search-tag').value.trim();
            if (tag) {
                window.location.href = `/forum/tags/${encodeURIComponent(tag)}`;
                return false; // Prevent default form submission
            }
            return false;
        }
    </script>
    <!-- Trigger Button -->
    <div type="" class=" border-card">
        <div class="full-pad-card gap-10 flex-r">
            <div class="prof-pic">
                <img src="{{ asset('images/forum/test.jpeg') }}" alt="">
            </div>
            <div class="create-forum-box" data-toggle="modal" data-target="#createPostModal">
                Create New Post
            </div>
        </div>
    </div>

    @if($posts->isEmpty())
    <p class="empty-forum-message">No posts available.</p>
    @else
    @foreach ($posts as $post)
    <div class="border-card">
        <div class="pad-card flex-c gap-10">
            <x-forum-card-top :post="$post" :followings="$followings" />
            <x-forum-card-content :post="$post" />
            <x-forum-card-tags :post="$post" />
            <x-forum-card-images :post="$post" />
            <x-forum-card-bottom :post="$post" />
        </div>
    </div>
    @endforeach
    @endif

    <div id="post-data" style="display: none;">
        {{ json_encode($posts) }}
    </div>

    <script>
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
    </script>



    <script src="{{ asset('js/forum/forumCard.js') }}"></script>
    <script src="{{ asset('js/forum/forumReport.js') }}"></script>
    <script src="{{ asset('js/forum/forumCardActions.js') }}"></script>


</div>
@endsection