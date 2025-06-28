<?php

declare(strict_types=1);

namespace App\Domains\Note\Infrastructure\Database\Eloquent\Factories;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Domains\Auth\Infrastructure\Models\User;
use App\Domains\Note\Infrastructure\Database\Eloquent\Models\Space;

/**
 * SpaceFactory
 *
 * @extends Factory<Space>
 */
final class SpaceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<Space>
     */
    protected $model = Space::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => Str::uuid(),
            'name' => $this->faker->name,
            'description' => $this->faker->sentence,
            'user_id' => User::factory(),
            'created_at' => now(),
            'updated_at' => now()
        ];
    }
}
