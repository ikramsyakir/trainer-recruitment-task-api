<?php

namespace Database\Factories;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->create(),
            'title' => $this->faker->title,
            'description' => $this->faker->text,
            'status' => $this->faker->randomElement([Task::PENDING, Task::IN_PROGRESS, Task::COMPLETED]),
            'due_date' => $this->faker->dateTimeBetween('1 day', '30 days'),
        ];
    }
}
