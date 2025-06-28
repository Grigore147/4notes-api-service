<?php

declare(strict_types=1);

namespace App\Domains\Note\Application\Services\Contracts;

use Ramsey\Uuid\UuidInterface;
use Illuminate\Support\Collection;
use App\Domains\Note\Domain\Entities\Contracts\NoteEntityContract;

interface NoteServiceContract
{
    public function create(NoteEntityContract|array $note): NoteEntityContract;

    public function update(string|UuidInterface|NoteEntityContract $note, array $data = []): NoteEntityContract;

    public function delete(string|UuidInterface|NoteEntityContract $note): bool;

    public function deleteNotes(Collection|array|int|string $ids): bool;
}
