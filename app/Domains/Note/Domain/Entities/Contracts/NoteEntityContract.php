<?php

declare(strict_types=1);

namespace App\Domains\Note\Domain\Entities\Contracts;

use Carbon\Carbon;
use Ramsey\Uuid\UuidInterface;
use App\Core\Domain\Entities\EntityContract;

interface NoteEntityContract extends EntityContract
{
    /**
     * Get the user ID of the note's owner.
     *
     * @return UuidInterface|string
     */
    public function getUserId(): UuidInterface|string;

    /**
     * Get the notebook ID of the note.
     *
     * @return UuidInterface|string|null
     */
    public function getNotebookId(): UuidInterface|string|null;

    /**
     * Get the notebook of the note.
     *
     * @return mixed
     */
    public function getNotebook(): mixed;

    /**
     * Get the note's title.
     *
     * @return string
     */
    public function getTitle(): string;

    /**
     * Get the note's content.
     *
     * @return string
     */
    public function getContent(): string;

    /**
     * Rename the note.
     *
     * @param string $newTitle
     * @return self
     */
    public function rename(string $newTitle): self;

    /**
     * Update the note's content.
     *
     * @param string $newContent
     * @return self
     */
    public function updateContent(string $newContent): self;
}
