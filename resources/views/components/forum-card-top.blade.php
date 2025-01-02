@props(['post'])

<div class="card-top flex-r">
    <div class="profile flex-r">
        <div class="prof-pic">
            <img src="{{ $post->user->picture ? asset('storage/' . $post->user->picture) : asset('images/forum/test.jpeg') }}" alt="Profile Picture">
        </div>
        <div class="profile-info flex-c">
            <div class="forum-username">{{ htmlspecialchars($post->user->username) }}</div>
            <div class="forum-createTime" id="time-text-{{ $post->forum_id }}">Loading...</div>
        </div>
        <!-- Follow button if the post is not the current user's post -->
        @if (Auth::id() !== $post->user_id)
        <div class="follow-button">
            @if($post->user->followers->contains(Auth::id()))
            <button class="btn btn-sm btn-primary follow-btn btn-gray" data-user-id="{{ $post->user_id }}">
                Following
            </button>
            @else
            <button class="btn btn-sm btn-primary follow-btn btn-blue" data-user-id="{{ $post->user_id }}">
                Follow
            </button>
            @endif
        </div>
        @endif
    </div>

    <!-- Three-dot icon and dropdown menu -->
    <div class="menu-container">
        <img class="three-dots" src="{{ asset('images/three-dots-icon.png') }}" alt="Menu" data-forum-id="{{ $post->forum_id }}" onclick="toggleDropdown(`{{ $post->forum_id }}`)">
        <div class="dropdown-menu" id="dropdown-menu-{{ $post->forum_id }}" style="display: none;">
            <ul>
                @if (Auth::id() === $post->user_id)
                <!-- Show Edit and Delete for the current user's post -->
                <li><a href="{{ route('forum.edit', $post->forum_id) }}">Edit</a></li>
                <li>
                    <form action="{{ route('forum.delete', $post->forum_id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this post?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="dropdown-item no-style">Delete</button>
                    </form>
                </li>
                @else
                <!-- Show Report option for other user's posts -->
                <li>
                    <a href="#" data-toggle="modal" data-target="#reportPostModal" onclick="setForumId(`{{ $post->forum_id }}`)">
                        Report
                    </a>
                </li>
                @endif
            </ul>
        </div>
    </div>
</div>