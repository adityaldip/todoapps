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
            'priority' => 'required|in:low,medium,high',
            'due_date' => 'required|date',
            'status' => 'required|in:todo,in_progress,review,done',
            'tags' => 'array',
            'tags.*' => 'exists:tags,id',
        ]);
    
        // Separate tags from validated data since we'll attach them separately
        $tags = $validated['tags'] ?? [];
        unset($validated['tags']);
    
        // Create todo with validated data
        $todo = Todo::create($validated);
        
        // Attach tags if any
        if (!empty($tags)) {
            $todo->tags()->attach($tags);
        }
    
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
            'priority' => 'required|in:low,medium,high',
            'due_date' => 'required|date',
            'status' => 'required|in:todo,in_progress,review,done',
            'tags' => 'array',
            'tags.*' => 'exists:tags,id',
        ]);
    
        // Separate tags from validated data
        $tags = $validated['tags'] ?? [];
        unset($validated['tags']);
    
        // Update todo
        $todo->update($validated);
        
        // Sync tags
        $todo->tags()->sync($tags);
    
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

    public function updateComponent(Request $request, Todo $todo)
    {
        $validated = $request->validate([
            'priority' => ['sometimes', 'required', 'in:high,medium,low'],
            'status' => ['sometimes', 'required', 'in:todo,in_progress,review,done'],
            'due_date' => ['sometimes', 'required', 'date'],
        ]);

        $todo->update($validated);

        return redirect()->back();
    }

}
