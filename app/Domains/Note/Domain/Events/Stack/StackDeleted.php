<?php

declare(strict_types=1);

namespace App\Domains\Note\Domain\Events\Stack;

use App\Core\Application\EventBus\DomainEvent;
use App\Domains\Note\Domain\Entities\Contracts\StackEntityContract;

final class StackDeleted extends DomainEvent
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
        return 'notes.stack.deleted';
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
        return $this->stack->toArray();
    }
}
