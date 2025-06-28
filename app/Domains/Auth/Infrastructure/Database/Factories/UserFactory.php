<?php

declare(strict_types=1);

namespace App\Domains\Auth\Infrastructure\Database\Factories;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Domains\Auth\Infrastructure\Models\User;

/**
 * @extends Factory<User>
 */
final class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<User>
     */
    protected $model = User::class;

    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => Str::uuid(),
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }

    public function create($attributes = [], ?Model $parent = null)
    {
        if (!empty($attributes)) {
            return $this->state($attributes)->create([], $parent);
        }

        return $this->make($attributes, $parent);
    }
}
