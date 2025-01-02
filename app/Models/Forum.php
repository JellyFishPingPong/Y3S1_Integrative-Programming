<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Forum extends Model
{
    use HasFactory;

    // Specify the table name if it's not the plural form of the model
    protected $table = 'forums';

    // Specify the primary key
    protected $primaryKey = 'forum_id';

    // Allow mass assignment for these fields
    protected $fillable = ['title', 'content', 'user_id'];

    // Define the relationship to the User model (optional)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function likes()
    {
        return $this->belongsToMany(User::class, 'forum_like', 'forum_id', 'user_id');
    }

    public function comments()
    {
        return $this->hasMany(ForumComment::class, 'forum_id');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'forum_tag', 'forum_id', 'tag_id');
    }

    public function reportedByUsers()
    {
        return $this->belongsToMany(User::class, 'reported_posts')
            ->withPivot('reason')
            ->withTimestamps();
    }
}
