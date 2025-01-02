<!-- resources/views/layouts/forum-layout.blade.php -->
@extends('layouts.app') <!-- Extends the base layout -->

@section('header')
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
<link rel="stylesheet" href="{{ asset('css/forum/forum.css') }}">
<link rel="stylesheet" href="{{ asset('css/forum/forumCard.css') }}">
<script>
    const likeIconPath = "{{ asset('images/forum/like-icon.png') }}";
    const likedIconPath = "{{ asset('images/forum/liked-icon.jpg') }}";
</script>




@section('content')


<div class="content-wrapper">
    <div class="dummy-space"></div> <!-- Dummy div for spacing -->
    <div class="sidebar">
        <!-- Sidebar content here -->
        <div class="follow-user">
            <p class="sidebar-header">People you follow</p>
            <div class="followings flex-c">
                @if ($followings->isEmpty())
                <p class="flex-r sidebar-text">
                    Not following anyone
                </p>
                @else
                @foreach ($followings as $following)
                <a class="following flex-r sidebar-list" data-user-id="{{ $following->user_id }}">
                    <div class="prof-pic-side">
                        <img src="{{ $following->picture ? asset('storage/' . $following->picture) : asset('images/forum/test.jpeg') }}" alt="Profile Picture">
                    </div>
                    <div class="forum-username">{{ $following->username }}</div>
                </a>
                @endforeach
                @endif
            </div>
        </div>
        <hr>
        <div class="sidebar-category">
            <p class="sidebar-header">Trending tags</p>
            <div class="categories flex-c">
                @if ($tags->isEmpty())
                <p class="flex-r sidebar-text">
                    No trending tags
                </p>
                @else
                @foreach ($tags as $tag)
                <a href="/forum/tags/{{ $tag->tag_name }}" class=" flex-r sidebar-list">
                    <div class="forum-username"># {{ $tag->tag_name }}</div>
                </a>
                @endforeach
                @endif
            </div>
        </div>
        <hr>
    </div>

    <div class="main-content flex-c">
        @yield('forum-content') <!-- Yield to individual forum pages -->

    </div>

    <div class="right-content">
        <!-- Right-side content here -->
    </div>

</div>


<div id="share-popup" class="share-popup">
    <p id="share-link"></p>
    <button id="close-popup">
        <img src="{{ asset('images/forum/x.png') }}" alt="">
    </button>
</div>
<script src="{{ asset('js/forum/forumPopup.js') }}"></script>
@if (session('error'))
<script>
    showPopup("{{ session('error') }}", 5);
</script>
@endif

@if (session('success'))
<script>
    showPopup("{{ session('success') }}", 5);
</script>
@endif
@endsection