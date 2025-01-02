@props(['post'])

<!-- Forum actions with forum_id added -->
<div class="forum-card-btm">
    <div class="forum-actions-info flex-r">
        <a class="forum-link">
            <span id="like-count-{{ $post->forum_id }}">
                @if ($post->likes->count() > 0)
                {{ $post->likes->count() . ' likes' }}
                @endif
            </span>
        </a>
        <a href="{{ route('forum.show', ['id' => $post->forum_id]) }}" class="forum-link">
            <span id="comments-count-{{ $post->forum_id }}">
                @if ($post->comments->count() > 0)
                {{ $post->comments->count() . ' comments' }}
                @endif
            </span>
        </a>
    </div>

    <div class="forum-actions flex-r">
        <div class="forum-action-wrapper">
            <div class="forum-action flex-r like-action" data-forum-id="{{ $post->forum_id }}">
                @if ($post->likes->contains(Auth::id()))
                <img id="like-image-{{ $post->forum_id }}" src="{{ asset('images/forum/liked-icon.jpg') }}"> Like
                @else
                <img id="like-image-{{ $post->forum_id }}" src="{{ asset('images/forum/like-icon.png') }}"> Like
                @endif
            </div>
        </div>
        <div class="forum-action-wrapper">
            <div class="forum-action flex-r comment-action" data-forum-id="{{ $post->forum_id }}">
                <img src="{{ asset('images/forum/comment-icon.png') }}"> Comment
            </div>
        </div>
        <div class="forum-action-wrapper">
            <div class="forum-action flex-r share-action" data-forum-id="{{ $post->forum_id }}">
                <img src="{{ asset('images/forum/share-icon.png') }}"> Share
            </div>
        </div>
    </div>
</div>