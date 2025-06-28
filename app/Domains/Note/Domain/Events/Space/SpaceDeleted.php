<?php

declare(strict_types=1);

namespace App\Domains\Note\Domain\Events\Space;

use App\Core\Application\EventBus\DomainEvent;
use App\Domains\Note\Domain\Entities\Contracts\SpaceEntityContract;

final class SpaceDeleted extends DomainEvent
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
        return 'notes.space.deleted';
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
        return $this->space->toArray();
    }
}
