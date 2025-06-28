<?php

declare(strict_types=1);

namespace App\Domains\Note\Domain\Events\Space;

use App\Core\Application\EventBus\DomainEvent;
use App\Domains\Note\Domain\Entities\Contracts\SpaceEntityContract;

final class SpaceUpdated extends DomainEvent
{
    public function __construct(
        public SpaceEntityContract $space
    ) {
        parent::__construct();
    }

    /**
     * @inheritDoc
     */
    public function topic(): string
    {
        return 'notes.space';
    }

    /**
     * @inheritDoc
     */
    public function type(): string
    {
        return 'notes.space.updated';
    }

    /**
     * @inheritDoc
     */
    public function subject(): string
    {
        return (string)$this->space->getId();
    }

    /**
     * @inheritDoc
     */
    public function data(): array
    {
        return [
            'userId' => $this->space->getUserId(),
            ...$this->space->getDirtyAttributes()
        ];
    }
}
