<?php

declare(strict_types=1);

namespace App\Core\Application\Services\DomainEventsPublisher;

use App\Core\Application\EventBus\Contracts\DomainEvent;

interface EventPublisher
{
    /**
     * Publish the Domain Event.
     *
     * @param DomainEvent $event
     */
    public function publish(DomainEvent $event): void;
}
