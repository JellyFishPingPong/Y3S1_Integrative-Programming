<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use App\Models\Tag;
use App\Models\User;

class ViewComposerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Use the composer to pass followings and tags to specific views
        View::composer(['forum.*'], function ($view) {
            if (Auth::check()) {
                // Fetch followings and trending tags
                $currentUserId = Auth::id();
                $followings = User::join('follows', 'users.user_id', '=', 'follows.followee_id')
                    ->where('follows.follower_id', $currentUserId)
                    ->orderBy('follows.created_at', 'desc')
                    ->get(['users.*']);

                // Fetch trending tags (top 5 based on the number of posts)
                $tags = Tag::select('tags.tag_id', 'tags.tag_name')
                    ->join('forum_tag', 'tags.tag_id', '=', 'forum_tag.tag_id')
                    ->selectRaw('COUNT(forum_tag.forum_id) as post_count')
                    ->groupBy('tags.tag_id', 'tags.tag_name')
                    ->orderBy('post_count', 'desc')
                    ->limit(5)
                    ->get();

                // Share the data with the view
                $view->with('followings', $followings)->with('tags', $tags);
            }
        });
    }
}
