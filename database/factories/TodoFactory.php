<?php

namespace Database\Factories;

use App\Models\Todo;
use Illuminate\Database\Eloquent\Factories\Factory;

class TodoFactory extends Factory
{
    protected $model = Todo::class;

    public function definition()
    {
        return [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'priority' => $this->faker->randomElement(['low', 'medium', 'high']),
            'due_date' => $this->faker->date(),
            'status' => $this->faker->randomElement(['todo', 'in_progress', 'review', 'done']),
        ];
    }
}