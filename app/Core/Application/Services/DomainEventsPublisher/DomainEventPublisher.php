<?php

declare(strict_types=1);

namespace App\Core\Application\Services\DomainEventsPublisher;

use App\Core\Application\EventBus\OutboxEventHandler;
use App\Core\Application\EventBus\Contracts\DomainEvent;

final class DomainEventPublisher extends OutboxEventHandler
{
    public function __construct(
        private readonly EventPublisher $eventPublisher
    ) {}

    /**
     * @inheritDoc
     */
    public function handle(DomainEvent $event): void
    {
        $this->eventPublisher->publish($event);
    }
}
