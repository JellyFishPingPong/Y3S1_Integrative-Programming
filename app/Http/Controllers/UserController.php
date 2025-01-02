<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    public function store(Request $request)
    {
    }

    public function follow($userId)
    {
        $currentUserId = Auth::id();
        $user = User::findOrFail($userId);

        // Check if already following
        $isFollowing = DB::table('follows')->where([
            ['follower_id', '=', $currentUserId],
            ['followee_id', '=', $userId],
        ])->exists();

        if ($isFollowing) {
            // Unfollow
            DB::table('follows')->where([
                ['follower_id', '=', $currentUserId],
                ['followee_id', '=', $userId],
            ])->delete();
        } else {
            // Follow
            DB::table('follows')->insert([
                'follower_id' => $currentUserId,
                'followee_id' => $userId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return response()->json([
            'success' => true, 
            'following' => !$isFollowing, 
            'picture' => $user->picture, 
            'username' => $user->username]);
    }
}
