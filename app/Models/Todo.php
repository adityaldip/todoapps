<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Todo extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'description','priority', 'status', 'due_date',
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
