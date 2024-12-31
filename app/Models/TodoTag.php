<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TodoTag extends Model
{
    protected $fillable = ['tag_id', 'todo_id'];
    public $timestamps = false; // If you don't need timestamps
}
