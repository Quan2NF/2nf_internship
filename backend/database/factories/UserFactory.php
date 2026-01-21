<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Position;
use Illuminate\Support\Str;
use App\Enums\User\UserGender;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'employee_code' => 'EMP-' . fake()->unique()->numberBetween(1000, 9999),

            'name'  => fake()->name(),
            'email' => fake()->unique()->safeEmail(),

            // password is auto-hashed by cast
            'password' => '123456',

            'phone_number' => fake()->optional()->numerify('##########'),

            'birthday' => fake()->optional()->date(),

            'gender' => fake()->optional()->randomElement([
                UserGender::Male,
                UserGender::Female,
            ]),

            'join_date'   => fake()->date(),
            'resign_date' => null,

            'avatar' => null,

            'is_active' => true,
        ];
    }

    /**
     * Inactive user
     */
    public function inactive(): static
    {
        return $this->state(fn () => [
            'is_active' => false,
        ]);
    }

    /**
     * Resigned user
     */
    public function resigned(): static
    {
        return $this->state(fn () => [
            'resign_date' => now()->subDays(rand(1, 365)),
            'is_active'   => false,
        ]);
    }

    public function admin(): static
    {
        return $this->afterCreating(function (User $user) {
            $adminPosition = Position::where('code', 'ADMIN')->firstOrFail();

            $user->positions()->attach($adminPosition->id, [
                'start_date' => now(),
                'end_date'   => null,
            ]);
        });
    }

    public function user(): static
    {
        return $this->afterCreating(function ($user) {
            static $positionIds;

            $positionIds ??= Position::whereIn('code', [
                'PHP-DEV',
                'C-SHARP-DEV',
                'HR',
            ])->pluck('id');

            $count = fake()->numberBetween(1, min(3, $positionIds->count()));

            $selectedIds = $positionIds->random($count);

            $user->positions()->attach(
                $selectedIds->mapWithKeys(fn ($id) => [
                    $id => [
                        'start_date' => now(),
                        'end_date'   => null,
                    ],
                ])->toArray()
            );
        });
    }
}
