<?php

namespace Database\Factories;

use App\Enums\Project\ProjectStatus;
use App\Models\ProjectMember;
use App\Models\Role;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
    public function configure(): static
    {
        return $this->afterCreating(function ($project) {

            $users = User::factory()
                ->count(random_int(2, 5))
                ->create();

            $pmUser = $users->random();

            $roles = Role::pluck('id', 'code');

            foreach ($users as $user) {

                $member = ProjectMember::create([
                    'project_id' => $project->id,
                    'user_id' => $user->id,
                ]);

                if ($user->id === $pmUser->id) {
                    $member->roles()->attach($roles['PM']);
                } else {
                    $member->roles()->attach(
                        collect([$roles['DEV'], $roles['TESTER']])->random()
                    );
                }
            }

            Task::factory()
                ->count(random_int(8, 12))
                ->create([
                    'project_id' => $project->id,
                    'assigned_to' => $users->random()->id,
                    'created_by'  => $pmUser->id,
                ]);
        });
    }
    
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $plannedStart = $this->faker->dateTimeBetween('-1 month', '+1 month');
        $plannedEnd   = $this->faker->dateTimeBetween($plannedStart, '+6 months');

        return [
            'code' => 'PRJ-' . $this->faker->unique()->numberBetween(1000, 9999),

            'name'        => $this->faker->sentence(3),
            'description' => $this->faker->optional()->paragraph(),

            'status' => $this->faker->randomElement(ProjectStatus::cases()),

            'planned_start_date' => $plannedStart,
            'planned_end_date'   => $plannedEnd,

            'start_date' => $this->faker->optional()->dateTimeBetween(
                $plannedStart,
                $plannedEnd
            ),

            'end_date' => null,

            'progress_rate' => $this->faker->numberBetween(0, 100),

            'is_public' => $this->faker->boolean(),
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
            'progress_rate' => $this->faker->numberBetween(1, 99),
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
