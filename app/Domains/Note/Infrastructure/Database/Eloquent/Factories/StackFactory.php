<?php

declare(strict_types=1);

namespace App\Domains\Note\Infrastructure\Database\Eloquent\Factories;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Domains\Note\Infrastructure\Database\Eloquent\Models\Space;
use App\Domains\Note\Infrastructure\Database\Eloquent\Models\Stack;

/**
 * StackFactory
 *
 * @extends Factory<Stack>
 */
final class StackFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<Stack>
     */
    protected $model = Stack::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $space = Space::factory()->create();

        return [
            'id' => Str::uuid(),
            'user_id' => $space->user_id,
            'space_id' => $space->id,
            'name' => $this->faker->name,
            'created_at' => now(),
            'updated_at' => now()
        ];
    }
}
