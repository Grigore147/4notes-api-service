<?php

declare(strict_types=1);

namespace App\Domains\Note\Domain\Events\Notebook;

use App\Core\Application\EventBus\DomainEvent;
use App\Domains\Note\Domain\Entities\Contracts\NotebookEntityContract;

final class NotebookDeleted extends DomainEvent
{
    public function __construct(
        public NotebookEntityContract $notebook
    ) {
        parent::__construct();
    }

    /**
     * @inheritDoc
     */
    public function topic(): string
    {
        return 'notes.notebook';
    }

    /**
     * @inheritDoc
     */
    public function type(): string
    {
        return 'notes.notebook.deleted';
    }

    /**
     * @inheritDoc
     */
    public function subject(): string
    {
        return (string)$this->notebook->getId();
    }

    /**
     * @inheritDoc
     */
    public function data(): array
    {
        return $this->notebook->toArray();
    }
}
