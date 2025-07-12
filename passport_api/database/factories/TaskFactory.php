<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id'     => \App\Models\User::factory(),
            'title'       => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(),
            'status'      => $this->faker->randomElement(['pending','in_progress','completed']),
            'due_date'    => $this->faker->date(),              // adds due_date
            'priority'    => $this->faker->numberBetween(1, 5),
        ];
    }
}
