@props(['post'])

<div class="forum-content">
    <a href="{{ route('forum.show', ['id' => $post->forum_id]) }}" class="forum-link flex-c gap-10" id="forum-link-{{ $post->forum_id }}">
        <div class="title">
            {{ htmlspecialchars($post->title) }}
        </div>
        <div>
            <div class="truncated-content" id="content-{{ $post->forum_id }}">
                {!! nl2br(htmlspecialchars($post->content)) !!}
            </div>
            <span class="see-more" id="see-more-{{ $post->forum_id }}" style="display: none;">see more</span>
        </div>
    </a>
</div>