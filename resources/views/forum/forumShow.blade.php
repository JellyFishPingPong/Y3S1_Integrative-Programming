@extends('layouts.forumLayout')

@section('title', 'Forum') <!-- This provides the title for the page -->

@section('header')
<link rel="stylesheet" href="{{ asset('css/forum/forumComment.css') }}">

@section('forum-content')
<x-report-modal />

<!-- Main content here -->
<div>
    <div class="border-card">
        <div class="pad-card flex-c gap-10">
            <x-forum-card-top :post="$post" :followings="$followings" />

            <div class="forum-content">
                <!-- also now redirect -->
                <div class="title">
                    {{ htmlspecialchars($post->title) }}
                </div>
                <div>
                    <!-- no truncated-content for forum.show -->
                    <div class="" id="content-{{ $post->forum_id }}">
                        {!! nl2br(htmlspecialchars($post->content)) !!}
                    </div>
                    <span class="see-more" id="see-more-{{ $post->forum_id }}" style="display: none;">see more</span>
                </div>

            </div>


            <x-forum-card-tags :post="$post" />
            <x-forum-card-images :post="$post" />

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
                    <a class="forum-link">
                        <span id="comments-count-{{ $post->forum_id }}">
                            @if ($post->comments->count() > 0)
                            {{ $post->comments->count() . ' comments' }}
                            @endif
                        </span>
                    </a>
                </div>

                <div class="forum-actions forum-actions-btm flex-r">
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
                        <div class="forum-action flex-r">
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

            <div class="comment-section">
                <!-- Add New Comment Box -->
                <div class="new-comment-box">
                    <form action="{{ route('comments.store', ['id' => $post->forum_id]) }}" method="POST">
                        @csrf
                        <div class="comment-input-wrapper">
                            <!-- Profile picture of the current user (assumed to be logged in) -->
                            <div class="profile-pic-comment">
                                <img src="{{ asset('images/forum/test.jpeg') }}" alt="Profile Picture">
                            </div>

                            <!-- Comment input field -->
                            <div class="comment-input-box">
                                <!-- Dynamic 'Replying to' message -->
                                <div id="replying-to" style="display: none; ">
                                    Replying to <span id="replying-to-username"></span>
                                    <a href="#" id="cancel-reply">&times;</a>
                                </div>
                                <textarea id="comment-textarea" name="comment" placeholder="Write a comment..." required></textarea>
                            </div>
                        </div>

                        <!-- Hidden fields to store forum ID and parent comment ID -->
                        <input type="hidden" name="forum_id" value="{{ $post->forum_id }}">
                        <input type="hidden" id="parent-comment-id" name="parent_id" value="">

                        <!-- Submit button -->
                        <div class="comment-submit">
                            <button type="submit">Post Comment</button>
                        </div>
                    </form>
                </div>

                <!-- Display Comments -->
                @foreach ($comments as $comment)
                @php
                $likeColor = $comment->likes->contains(Auth::id()) ? '#097EFF' : '#65686C';
                @endphp
                <div class="comment-group">
                    <!-- Parent Comment Box -->
                    <div class="comment-wrapper">
                        <div class="profile-pic-comment">
                            <img src="{{ $comment->user->picture ? asset('storage/' . $comment->user->picture) : asset('images/forum/test.jpeg') }}" alt="Profile Picture">
                        </div>
                        <div class="comment-box depth-0">
                            <div class="comment-body">
                                <p class="username">{{ $comment->user->username }}</p>
                                <p class="comment-text">{{ $comment->comment }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="comment-actions">
                        <span class="time-text" id="time-text-{{ $comment->comment_id }}">Loading...</span>
                        <span class="like-text comment-like-action"
                            data-comment-id="{{ $comment->comment_id }}"
                            id="like-text-{{ $comment->comment_id }}"
                            style="color:<?= $likeColor ?>;">
                            @if ($comment->likes->count() == 0)
                            Like
                            @else
                            {{ $comment->likes->count() }} likes
                            @endif
                        </span>
                        <span class="reply-text" onclick="replyToComment('{{ $comment->comment_id }}', '{{ $comment->user->username }}')">Reply</span>
                    </div>

                    <!-- Child Comments (Replies) -->
                    @if ($comment->children->isNotEmpty())
                    <div class="child-comment-group">
                        @foreach ($comment->children as $child)
                        @php
                        $childLikeColor = $child->likes->contains(Auth::id()) ? '#097EFF' : '#65686C';
                        @endphp
                        <div class="comment-wrapper">
                            <div class="profile-pic-comment">
                                <img src="{{ $child->user->picture ? asset('storage/' . $child->user->picture) : asset('images/forum/test.jpeg') }}" alt="Profile Picture">
                            </div>
                            <div class="comment-box depth-1">
                                <div class="comment-body">
                                    <p class="username">{{ $child->user->username }}</p>
                                    <p class="comment-text">{{ $child->comment }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="comment-actions">
                            <span class="time-text" id="time-text-{{ $child->comment_id }}">Loading...</span>
                            <span class="like-text comment-like-action"
                                data-comment-id="{{ $child->comment_id }}"
                                id="like-text-{{ $child->comment_id }}"
                                style="color:<?= $childLikeColor ?>;">
                                @if ($child->likes->count() == 0)
                                Like
                                @else
                                {{ $child->likes->count() }} likes
                                @endif
                            </span>
                            <span class="reply-text" onclick="replyToComment('{{ $child->parent }}', '{{ $child->user->username }}')">Reply</span>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
                @endforeach

            </div>

            <script src="{{ asset('js/forum/forumComment.js') }}"></script>

            <!-- Hidden div to store comment data -->
            <div id="forum-data" style="display: none;">
                {{ json_encode($post) }}
            </div>
            <div id="comment-data" style="display: none;">
                {{ json_encode($comments) }}
            </div>

            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script src="{{ asset('js/forum/forumShowAPI.js') }}"></script>
            <script src="{{ asset('js/forum/commentAction.js') }}"></script>
        </div>
    </div>


    <script src="{{ asset('js/forum/forumCard.js') }}"></script>
    <script src="{{ asset('js/forum/forumCardActions.js') }}"></script>


</div>

@endsection