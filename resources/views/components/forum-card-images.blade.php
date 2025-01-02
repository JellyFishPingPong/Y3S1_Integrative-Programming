@props(['post'])

<!-- Image Slideshow from forum_{forum_id} directory -->
<div class="image-slideshow">
    @php
    $forumId = $post->forum_id;
    $imageDir = '/uploads/forum_' . $forumId;
    $images = is_dir(public_path($imageDir)) ? File::files(public_path($imageDir)) : [];
    @endphp

    @if (!empty($images))
    <div id="slideshow-{{ $forumId }}" class="slideshow-container">
        @foreach ($images as $key => $image)
        <div class="mySlides-{{ $forumId }} mySlides" style="display: none;">
            <img src="{{ asset($imageDir . '/' . $image->getFilename()) }}">
        </div>
        @endforeach

        <!-- Next and Previous buttons with unique handlers for this slideshow -->
        @if(count($images) > 1)
        <a class="prev" onclick="plusSlides(<?php echo $forumId; ?>, -1)">&#10094;</a>
        <a class="next" onclick="plusSlides(<?php echo $forumId; ?>, 1)">&#10095;</a>
        @endif
    </div>
    @endif
</div>