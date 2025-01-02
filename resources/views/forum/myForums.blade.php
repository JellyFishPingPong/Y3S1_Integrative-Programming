<!-- resources/views/products/create.blade.php -->

@extends('layouts.forumLayout') <!-- This links the view to the layout -->

@section('title', 'Forum') <!-- This provides the title for the page -->

@section('header')
<link rel="stylesheet" href="{{ asset('css/forum/forumCreate.css') }}">

@section('forum-content') <!-- This section is injected into the @yield('content') of the layout -->

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Main content here -->
<div>
    <h2 class="forum-header">My posts</h2>


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

    <script src="{{ asset('js/forum/forumCard.js') }}"></script>
    <script src="{{ asset('js/forum/forumReport.js') }}"></script>
    <script src="{{ asset('js/forum/forumCardActions.js') }}"></script>
    <script src="{{ asset('js/forum/forumListAPI.js') }}"></script>


</div>

@endsection