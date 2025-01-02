<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    // Specify the table name if it's not pluralized automatically
    protected $table = 'tags';

    // Specify the primary key
    protected $primaryKey = 'tag_id';

    // Allow mass assignment for these fields
    protected $fillable = ['tag_name'];

    // Tag Model
    public function forums()
    {
        return $this->belongsToMany(Forum::class, 'forum_tag', 'tag_id', 'forum_id');
    }
}
