<?php

declare(strict_types=1);

namespace App\Domains\Note\Domain\Events\Note;

use App\Core\Application\EventBus\DomainEvent;
use App\Domains\Note\Domain\Entities\Contracts\NoteEntityContract;

final class NoteUpdated extends DomainEvent
{
    public function __construct(
        public NoteEntityContract $note
    ) {
        parent::__construct();
    }

    /**
     * @inheritDoc
     */
    public function topic(): string
    {
        return 'notes.note';
    }

    /**
     * @inheritDoc
     */
    public function type(): string
    {
        return 'notes.note.updated';
    }

    /**
     * @inheritDoc
     */
    public function subject(): string
    {
        return (string)$this->note->getId();
    }

    /**
     * @inheritDoc
     */
    public function data(): array
    {
        return [
            'userId' => $this->note->getUserId(),
            ...$this->note->getDirtyAttributes()
        ];
    }
}
