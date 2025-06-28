<?php

declare(strict_types=1);

namespace App\Domains\Note\Infrastructure\Database\Eloquent\Factories;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Domains\Note\Infrastructure\Database\Eloquent\Models\Note;
use App\Domains\Note\Infrastructure\Database\Eloquent\Models\Notebook;

/**
 * NoteFactory
 *
 * @extends Factory<Note>
 */
final class NoteFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<Note>
     */
    protected $model = Note::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $notebook = Notebook::factory()->create();

        return [
            'id' => Str::uuid(),
            'user_id' => $notebook->user_id,
            'notebook_id' => $notebook->id,
            'title' => $this->faker->name,
            'content' => $this->faker->text,
            'created_at' => now(),
            'updated_at' => now()
        ];
    }
}
