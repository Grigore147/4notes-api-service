<?php

declare(strict_types=1);

namespace App\Domains\Note\Domain\Events\Stack;

use App\Core\Application\EventBus\DomainEvent;
use App\Domains\Note\Domain\Entities\Contracts\StackEntityContract;

final class StackUpdated extends DomainEvent
{
    public function __construct(
        public StackEntityContract $stack
    ) {
        parent::__construct();
    }

    /**
     * @inheritDoc
     */
    public function topic(): string
    {
        return 'notes.stack';
    }

    /**
     * @inheritDoc
     */
    public function type(): string
    {
        return 'notes.stack.updated';
    }

    /**
     * @inheritDoc
     */
    public function subject(): string
    {
        return (string)$this->stack->getId();
    }

    /**
     * @inheritDoc
     */
    public function data(): array
    {
        return [
            'userId' => $this->stack->getUserId(),
            ...$this->stack->getDirtyAttributes()
        ];
    }
}
