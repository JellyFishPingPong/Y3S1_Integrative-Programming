<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    // Specify the table name if it's not the pluralized form "roles"
    protected $table = 'roles';

    // Specify the primary key
    protected $primaryKey = 'roleId';

    // Allow mass assignment for these fields
    protected $fillable = ['roleName'];

    // Disable auto-incrementing if not needed (it's true by default)
    public $incrementing = true;

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_role', 'role_id', 'user_id');
    }
}
