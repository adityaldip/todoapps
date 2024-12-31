<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use App\Models\Tag;
use Illuminate\Http\Request;
use App\Models\Comment;

class TodoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $todos = Todo::with('tags', 'comments')->latest()->get();
    
        return inertia('Todos/Index', [
            'todos' => $todos,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $tags = Tag::all();
        return inertia('Todos/Create', [
            'tags' => $tags,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'tags' => 'array',
            'tags.*' => 'exists:tags,id',
        ]);
        $todo = Todo::create($validated);
        $todo->tags()->attach($request->tags);
    
        return redirect()->route('todos.index')->with('success', 'Todo created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Todo $todo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Todo $todo)
    {
        $tags = Tag::all();
        $selectedTags = $todo->tags->pluck('id');
    
        return inertia('Todos/Edit', [
            'todo' => $todo,
            'tags' => $tags,
            'selectedTags' => $selectedTags,
        ]);
    }
    

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Todo $todo)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'tags' => 'array',
            'tags.*' => 'exists:tags,id',
        ]);
    
        $todo->update($validated);
        $todo->tags()->sync($request->tags);
    
        return redirect()->route('todos.index')->with('success', 'Todo updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Todo $todo)
    {
        $todo->delete();
    
        return redirect()->route('todos.index')->with('success', 'Todo deleted successfully.');
    }

    public function addComment(Request $request, Todo $todo)
    {
        $validated = $request->validate([
            'content' => 'required|string|max:1000',
        ]);
    
        $todo->comments()->create($validated);
    
        return redirect()->back();
    }

}
