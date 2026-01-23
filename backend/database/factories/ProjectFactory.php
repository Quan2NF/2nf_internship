<?php

namespace Database\Factories;

use App\Models\User;
use App\Enums\Project\ProjectStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $plannedStart = fake()->dateTimeBetween('-1 month', '+1 month');
        $plannedEnd   = fake()->dateTimeBetween($plannedStart, '+6 months');

        return [
            'code' => 'PRJ-' . fake()->unique()->numberBetween(1000, 9999),

            'name'        => fake()->sentence(3),
            'description' => fake()->optional()->paragraph(),

            'status' => fake()->randomElement(ProjectStatus::cases()),

            'planned_start_date' => $plannedStart,
            'planned_end_date'   => $plannedEnd,

            'start_date' => fake()->optional()->dateTimeBetween(
                $plannedStart,
                $plannedEnd
            ),

            'end_date' => null,

            'progress_rate' => fake()->numberBetween(0, 100),

            'is_public' => fake()->boolean(),
            'is_active' => true,

            // creator / updater
            'created_by' => User::factory(),
            'updated_by' => User::factory(),
        ];
    }

    /**
     * Project already completed
     */
    public function completed(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => ProjectStatus::COMPLETED,
                'progress_rate' => 100,
                'end_date' => now()->subDays(rand(1, 30)),
            ];
        });
    }

    /**
     * Project in progress
     */
    public function inProgress(): static
    {
        return $this->state(fn () => [
            'status' => ProjectStatus::ACTIVE,
            'progress_rate' => fake()->numberBetween(1, 99),
        ]);
    }

    /**
     * Public project
     */
    public function public(): static
    {
        return $this->state(fn () => [
            'is_public' => true,
        ]);
    }
}
