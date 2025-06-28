<?php

declare(strict_types=1);

namespace App\Core\Application\EventBus;

use stdClass;

use App\Core\Application\EventBus\Event;
use App\Core\Application\EventBus\Contracts\DomainEvent as DomainEventContract;

abstract class DomainEvent extends Event implements DomainEventContract
{
    /**
     * CloudEvent spec version.
     */
    public const SPEC_VERSION = '1.0';
    
    /**
     * @inheritDoc
     */
    public function source(): string
    {
        // <domain>.<tenant>.<service>
        return 'app.4notes.api';
    }

    /**
     * @inheritDoc
     *
     * @note Even though the Event is published on the Domain Entity topic level,
     *       it will also be published on the Event Type topic level for more granular flexibility (using Kafka Streams or KSQL).
     *       This is useful for cases when we want more granular permissions control over who can consume the events by type,
     *       while the Domain Entity topic is Event Source like and allows reading events history for the entity.
     */
    public function topic(): string
    {
        return 'domain.entity.event';
    }

    /**
     * @inheritDoc
     */
    public function type(): string
    {
        return 'domain.entity.event';
    }

    /**
     * @inheritDoc
     */
    public function subject(): string
    {
        return $this->type();
    }

    /**
     * @inheritDoc
     */
    public function key(): string
    {
        return $this->subject();
    }

    /**
     * @inheritDoc
     */
    public function time(): string
    {
        return now()->toIso8601String();
    }

    /**
     * @inheritDoc
     */
    public function metadata(): array
    {
        return [
            'event' => $this->name()
        ];
    }

    /**
     * @inheritDoc
     */
    public function data(): array
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function payload(): array
    {
        $data = $this->data();

        return [
            'specversion' => self::SPEC_VERSION,
            'id' => $this->id(),
            'source' => $this->source(),
            'type' => $this->type(),
            'subject' => $this->subject(),
            'time' => $this->time(),
            'data' => !empty($data) ? $data : new stdClass()
        ];
    }
}
