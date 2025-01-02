<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ForumComment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CommentController extends Controller
{

    public function store(Request $request)
    {
        // Get the current logged-in user ID (use Auth::id() when authentication is implemented)
        $currentUserId = Auth::id(); // Replace with Auth::id()s

        // Validate the request
        $validatedData = $request->validate([
            'comment' => 'required|string|max:500',
            'forum_id' => 'required|exists:forums,forum_id',
            'parent_id' => 'nullable|exists:forum_comments,comment_id', // Ensure the parent comment exists or is null
        ]);

        $parent_id = $validatedData['parent_id'];

        // Store the comment in the database
        ForumComment::create([
            'comment' => $validatedData['comment'],
            'forum_id' => $validatedData['forum_id'],
            'user_id' => $currentUserId, // Assumes the user is authenticated
            'parent' => $parent_id,
        ]);

        // Redirect back to the forum post or reload the page
        return back();
    }

    public function like(Request $request, $id)
    {
        // Assuming you have authentication
        $userId = Auth::id();

        // Find the comment
        $comment = ForumComment::findOrFail($id);

        // Check if the user has already liked the comment
        $hasLiked = $comment->likes()->where('forum_comment_like.user_id', $userId)->exists();

        if ($hasLiked) {
            // Unlike the post
            DB::table('forum_comment_like')
                ->where('comment_id', $id)
                ->where('user_id', $userId)
                ->delete();
        } else {
            // Like the post
            DB::table('forum_comment_like')->insert([
                'comment_id' => $id,
                'user_id' => $userId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Get the updated like count
        $likeCount = DB::table('forum_comment_like')
            ->where('comment_id', $id)
            ->count();

        return response()->json([
            'success' => true,
            'liked' => !$hasLiked,
            'like_count' => $likeCount,
            'message' => $hasLiked ? 'Post unliked successfully' : 'Post liked successfully',
        ]);

    }
}
