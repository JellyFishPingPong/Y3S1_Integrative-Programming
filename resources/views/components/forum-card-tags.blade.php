@props(['post'])

<div class="forum-tags flex-r">
    @foreach ($post->tags as $tag)
    <a href="{{ route('forum.tag', ['tag_name' => $tag->tag_name]) }}" class="tag-block">
        {{ $tag->tag_name }}
    </a>
    @endforeach
</div>