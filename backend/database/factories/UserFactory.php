<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    protected static ?string $password;

    public function definition(): array
    {
        return [
            'employee_code' => 'EMP' . str_pad($this->faker->unique()->numberBetween(1, 9999), 4, '0', STR_PAD_LEFT),
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'password' => static::$password ??= Hash::make('password'),
            'phone_number' => fake()->phoneNumber(),
            'birthday' => fake()->date(),
            'gender' => fake()->randomElement([User::GENDER_MALE, User::GENDER_FEMALE, User::GENDER_OTHER]),
            'join_date' => fake()->date(),
            'resign_date' => null,
            'avatar' => null,
            'is_active' => User::STATUS_ACTIVE,
            'role' => User::ROLE_USER,
        ];
    }

    /**
     * Indicate that the user is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => User::STATUS_INACTIVE,
        ]);
    }

    /**
     * Indicate that the user has resigned.
     */
    public function resigned(): static
    {
        return $this->state(fn (array $attributes) => [
            'resign_date' => fake()->dateTimeBetween('-1 year', 'now'),
            'is_active' => User::STATUS_INACTIVE,
        ]);
    }
}
