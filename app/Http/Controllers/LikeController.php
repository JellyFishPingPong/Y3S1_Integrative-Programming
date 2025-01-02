<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Forum;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function likePost(Request $request, $id)
    {
        try {
            $userId = Auth::id(); // Ensure the user is authenticated

            // Check if the user already liked the post
            $likeExists = DB::table('forum_like')
                ->where('forum_id', $id)
                ->where('user_id', $userId)
                ->exists();

            if ($likeExists) {
                // Unlike the post
                DB::table('forum_like')
                    ->where('forum_id', $id)
                    ->where('user_id', $userId)
                    ->delete();
            } else {
                // Like the post
                DB::table('forum_like')->insert([
                    'forum_id' => $id,
                    'user_id' => $userId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Get the updated like count
            $likeCount = DB::table('forum_like')
                ->where('forum_id', $id)
                ->count();

            return response()->json([
                'success' => true,
                'liked' => !$likeExists,
                'like_count' => $likeCount,
                'message' => $likeExists ? 'Post unliked successfully' : 'Post liked successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
