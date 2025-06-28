<?php

declare(strict_types=1);

namespace App\Domains\Note\Infrastructure\Database\Eloquent\Factories;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Domains\Note\Infrastructure\Database\Eloquent\Models\Stack;
use App\Domains\Note\Infrastructure\Database\Eloquent\Models\Notebook;
use App\Core\Infrastructure\Database\Factories\Concerns\RefreshOnCreate;

/**
 * NotebookFactory
 *
 * @extends Factory<Notebook>
 */
final class NotebookFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<Notebook>
     */
    protected $model = Notebook::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $stack = Stack::factory()->create();
        
        return [
            'id' => Str::uuid(),
            'user_id' => $stack->user_id,
            'space_id' => $stack->space_id,
            'stack_id' => $stack->id,
            'name' => $this->faker->name,
            'created_at' => now(),
            'updated_at' => now()
        ];
    }
}
