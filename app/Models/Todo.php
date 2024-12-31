<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{

    protected $fillable = [
        'title', 'description',
    ];

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'todo_tags', 'todo_id', 'tag_id');
    }
    
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
    
}
