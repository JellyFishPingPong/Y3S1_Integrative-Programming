<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ForumComment;
use App\Models\Forum;
use App\Models\User;

class ForumCommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Fetch a random forum and user for the comment
        $forums = Forum::all();
        $users = User::all();

        // Create some parent comments
        foreach ($forums as $forum) {
            foreach ($users as $user) {
                ForumComment::create([
                    'comment' => 'This is a parent comment by ' . $user->username,
                    'parent' => null, // Parent is null for top-level comments
                    'forum_id' => $forum->forum_id,
                    'user_id' => $user->user_id,
                ]);

                // Create child comments (replies) to the parent comment
                $parentComment = ForumComment::latest()->first();
                
                ForumComment::create([
                    'comment' => 'This is a reply to the parent comment by ' . $user->username,
                    'parent' => $parentComment->comment_id, // This comment is a reply to the parent
                    'forum_id' => $forum->forum_id,
                    'user_id' => $user->user_id,
                ]);
            }
        }
    }
}
