<?php

namespace Database\Factories;

use App\Enum\TaskStatusEnum;
use App\Models\User;
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
        $status = $this->faker->randomElement(TaskStatusEnum::AllEnumArrayValues());

        return [
            'title' => $this->faker->sentence(4),
            'description' => $this->faker->paragraph(),
            'status' => $status,
            'completed_at' => $status === TaskStatusEnum::COMPLETED ? $this->faker->dateTimeBetween('-1 month', 'now') : null,
            'user_id' => User::factory()->create()->id,
        ];
    }
}
