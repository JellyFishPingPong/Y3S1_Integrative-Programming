<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Forum;

class ForumSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Forum::create([
            'title' => 'Welcome to the Forum!',
            'content' => 'This is a post from User 1.',
            'user_id' => 1,
        ]);

        Forum::create([
            'title' => 'Second Post',
            'content' => 'User 2 is sharing some interesting thoughts.',
            'user_id' => 2,
        ]);

        Forum::create([
            'title' => 'Laravel Tips and Tricks',
            'content' => 'User 3 discusses helpful Laravel techniques.',
            'user_id' => 3,
        ]);
    }
}
