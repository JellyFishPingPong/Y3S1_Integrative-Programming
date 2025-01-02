<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ForumComment extends Model
{
    use HasFactory;

    // Specify the table name
    protected $table = 'forum_comments';

    // Specify the primary key
    protected $primaryKey = 'comment_id';

    // Allow mass assignment for these fields
    protected $fillable = ['comment', 'parent', 'forum_id', 'user_id'];

    // Define the relationship to the Forum model
    public function forum()
    {
        return $this->belongsTo(Forum::class, 'forum_id', 'forum_id');
    }

    // Define the relationship to the User model
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    // Define a self-referencing relationship for replies
    public function children()
    {
        return $this->hasMany(ForumComment::class, 'parent', 'comment_id');
    }

    public function parentComment()
    {
        return $this->belongsTo(ForumComment::class, 'parent', 'comment_id');
    }

    public function likes()
    {
        return $this->belongsToMany(User::class, 'forum_comment_like', 'comment_id', 'user_id');
    }
}
