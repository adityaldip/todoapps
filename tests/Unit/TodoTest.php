<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Todo;
use App\Models\Tag;
use App\Models\User;
use App\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;

class TodoTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_index_displays_todos()
    {
        // Create a todo with tags and comments
        $todo = Todo::factory()
            ->has(Tag::factory()->count(2))
            ->has(Comment::factory()->count(2))
            ->create();
        
        $response = $this->actingAs($this->user)
            ->get(route('todos.index'));

        $response->assertStatus(200);
        $response->assertInertia(fn (Assert $page) => $page
            ->component('Todos/Index')
            ->has('todos')
        );
    }

    public function test_store_creates_new_todo()
    {
        $tag = Tag::factory()->create();
        
        $todoData = [
            'title' => 'Test Todo',
            'description' => 'Test Description',
            'priority' => 'high',
            'due_date' => '2023-12-31',
            'status' => 'todo',
            'tags' => [$tag->id]
        ];

        $response = $this->actingAs($this->user)
            ->post(route('todos.store'), $todoData);

        $response->assertRedirect(route('todos.index'));
        $this->assertDatabaseHas('todos', [
            'title' => 'Test Todo',
            'description' => 'Test Description',
            'priority' => 'high',
            'status' => 'todo'
        ]);
    }

    public function test_update_modifies_existing_todo()
    {
        $todo = Todo::factory()->create();
        
        $updatedData = [
            'title' => 'Updated Todo',
            'description' => 'Updated Description',
            'priority' => 'medium',
            'due_date' => '2023-12-31',
            'status' => 'in_progress',
            'tags' => []
        ];

        $response = $this->actingAs($this->user)
            ->put(route('todos.update', $todo), $updatedData);

        $response->assertRedirect(route('todos.index'));
        $this->assertDatabaseHas('todos', [
            'id' => $todo->id,
            'title' => 'Updated Todo',
            'description' => 'Updated Description'
        ]);
    }

    public function test_destroy_deletes_todo()
    {
        $todo = Todo::factory()->create();

        $response = $this->actingAs($this->user)
            ->delete(route('todos.destroy', $todo));

        $response->assertRedirect(route('todos.index'));
        $this->assertDatabaseMissing('todos', ['id' => $todo->id]);
    }

    public function test_add_comment_to_todo()
    {
        $todo = Todo::factory()->create();
        
        $response = $this->actingAs($this->user)
            ->post("/todos/{$todo->id}/comments", [
                'content' => 'Test Comment'
            ]);

        $this->assertDatabaseHas('comments', [
            'content' => 'Test Comment',
            'commentable_id' => $todo->id,
            'commentable_type' => Todo::class
        ]);
    }
    public function test_update_component_updates_todo_status()
    {
        $todo = Todo::factory()->create(['status' => 'todo']);

        $response = $this->actingAs($this->user)
            ->patch(route('todos.updateComponent', $todo), [
                'status' => 'in_progress'
            ]);

        $this->assertDatabaseHas('todos', [
            'id' => $todo->id,
            'status' => 'in_progress'
        ]);
    }

    public function test_validation_fails_with_invalid_data()
    {
        $response = $this->actingAs($this->user)
            ->post(route('todos.store'), [
                'title' => '',
                'priority' => 'invalid',
                'status' => 'invalid',
                'due_date' => 'not-a-date'
            ]);

        $response->assertSessionHasErrors(['title', 'priority', 'status', 'due_date']);
    }
}

