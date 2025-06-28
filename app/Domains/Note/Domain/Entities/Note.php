<?php

declare(strict_types=1);

namespace App\Domains\Note\Domain\Entities;

use Carbon\Carbon;
use Ramsey\Uuid\UuidInterface;
use App\Core\Support\Undefined;
use App\Core\Domain\Entities\Entity;
use App\Domains\Note\Domain\Entities\Contracts\NoteEntityContract;

final class Note extends Entity implements NoteEntityContract
{
    public function __construct(
        UuidInterface|string|null $id = null,
        UuidInterface|string|null $userId = null,
        UuidInterface|string|null $notebookId = null,
        mixed $notebook = null,
        string $title = '',
        string $content = '',
        ?Carbon $createdAt = null,
        ?Carbon $updatedAt = null
    ) {
        $this->initAttributes(get_defined_vars());
    }

    public function getUserId(): UuidInterface|string
    {
        return $this->getAttribute('userId');
    }

    public function getNotebookId(): UuidInterface|string|null
    {
        return $this->getAttribute('notebookId');
    }

    public function getNotebook(): mixed
    {
        return $this->getAttribute('notebook');
    }

    public function getTitle(): string
    {
        return $this->getAttribute('title');
    }

    public function getContent(): string
    {
        return $this->getAttribute('content');
    }

    public function rename(string $newTitle): self
    {
        if (empty(trim($newTitle))) {
            throw new \InvalidArgumentException('Title cannot be empty.');
        }

        $this->setAttribute('title', $newTitle);

        return $this;
    }

    public function updateContent(string $newContent): self
    {
        $this->setAttribute('content', $newContent);

        return $this;
    }

    public function set(
        Undefined|UuidInterface|string|null $userId = new Undefined,
        Undefined|UuidInterface|string|null $notebookId = new Undefined,
        Undefined|string $title = new Undefined,
        Undefined|string $content = new Undefined,
        Undefined|Carbon $createdAt = new Undefined,
        Undefined|Carbon $updatedAt = new Undefined
    ): self
    {
        foreach(get_defined_vars() as $key => $value) {
            if (!($value instanceof Undefined)) {
                $this->setAttribute($key, $value);
            }
        }

        return $this;
    }
}
