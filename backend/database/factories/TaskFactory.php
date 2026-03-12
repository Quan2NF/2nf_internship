<?php

namespace Database\Factories;

use App\Models\Task;
// use App\Models\TaskType;
use App\Models\TaskStatus;
use App\Models\TaskPriority;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    protected $model = Task::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = $this->faker->dateTimeBetween('-1 month', '+1 month');
        $dueDate   = (clone $startDate)->modify('+' . rand(1, 14) . ' days');

        $isClosed = $this->faker->boolean(35);
        $closedAt = $isClosed
            ? $this->faker->dateTimeBetween($startDate, (clone $dueDate)->modify('+5 days'))
            : null;

        return [
            'project_id' => null,
            'parent_id'  => null,

            'subject'     => $this->faker->sentence(6),
            'description' => $this->faker->paragraph(),

            // Reference tables (must exist)
            'status_id' => TaskStatus::query()->inRandomOrder()->value('id'),
            // 'type_id'   => TaskType::query()->inRandomOrder()->value('id'),
            'type_id' => random_int(1, 2),
            'priority_id' => TaskPriority::query()->inRandomOrder()->value('id'),

            'assigned_to' => null,
            'created_by'  => null,

            'start_date' => $startDate->format('Y-m-d'),
            'due_date'   => $dueDate->format('Y-m-d'),

            'estimated_hours' => $this->faker->randomFloat(2, 1, 40),
            'actual_hours'    => $this->faker->randomFloat(2, 0, 40),

            'progress_rate' => $this->faker->numberBetween(0, 100),
            'is_private'    => false,

            'closed_at' => $closedAt,
        ];
    }

    public function private(): static
    {
        return $this->state(fn () => [
            'is_private' => true,
        ]);
    }

    public function completed(): static
    {
        return $this->state(fn () => [
            'progress_rate' => 100,
            'closed_at'     => now(),
        ]);
    }

    public function overdue(): static
    {
        return $this->state(fn () => [
            'due_date' => now()->subDays(rand(1, 10))->format('Y-m-d'),
        ]);
    }

    public function subTask(Task $parent): static
    {
        return $this->state(fn () => [
            'project_id' => $parent->project_id,
            'parent_id'  => $parent->id,
        ]);
    }
}
