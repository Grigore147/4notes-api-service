<?php

declare(strict_types=1);

namespace App\Domains\Note\Domain\Services\Contracts;

use Ramsey\Uuid\UuidInterface;
use Illuminate\Support\Collection;
use App\Domains\Note\Domain\Entities\Contracts\NoteEntityContract;

interface NoteService
{
    /**
     * Create Note
     *
     * @param NoteEntityContract|array $entity
     * @return NoteEntityContract
     */
    public function create(NoteEntityContract|array $note): NoteEntityContract;

    /**
     * Update Note
     *
     * @param string|UuidInterface|NoteEntityContract $entity
     * @param array $data
     * @return NoteEntityContract
     */
    public function update(string|UuidInterface|NoteEntityContract $note, array $data = []): NoteEntityContract;

    /**
     * Delete Note
     *
     * @param string|UuidInterface|NoteEntityContract $id
     * @return bool
     */
    public function delete(string|UuidInterface|NoteEntityContract $note): bool;

    /**
     * Delete multiple Notes
     *
     * @param Collection|array|int|string $ids
     * @return bool True if all notes were deleted
     */
    public function deleteNotes(Collection|array|int|string $ids): bool;
}
