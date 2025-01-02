<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;
    use HasFactory;

    // Specify the table name
    protected $table = 'users';

    // Specify the primary key
    protected $primaryKey = 'user_id';

    // Allow mass assignment for these fields
    protected $fillable = ['username', 'name', 'email', 'passwd', 'picture'];

    // Disable auto-incrementing if userId is not auto-incrementing
    public $incrementing = true;

    // Define data types for attributes
    protected $casts = [
        //'picture' => 'binary',
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_role', 'user_id', 'role_id');
    }

    // Users that the current user follows
    public function followings()
    {
        return $this->belongsToMany(User::class, 'follows', 'follower_id', 'followee_id');
    }

    // Users who follow the current user
    public function followers()
    {
        return $this->belongsToMany(User::class, 'follows', 'followee_id', 'follower_id');
    }

    public function likedForums()
    {
        return $this->belongsToMany(Forum::class, 'forum_like', 'user_id', 'forum_id');
    }

    public function forums()
    {
        return $this->belongsToMany(Forum::class, 'reported_posts')
            ->withPivot('reason')
            ->withTimestamps();
    }

    // Disable timestamps if you don't want created_at and updated_at fields
    // public $timestamps = false;
}
