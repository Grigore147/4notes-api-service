<?php

declare(strict_types=1);

namespace App\Core\Application\EventBus\Contracts;

interface DomainEvent
{
    /**
     * The event name.
     */
    public function name(): string;

    /**
     * The event id.
     */
    public function id(): string;
    
    /**
     * CloudEvent source.
     */
    public function source(): string;

    /**
     * The event topic (used for partitioning).
     *
     * @note This is basically the Aggregate Type / Domain Entity / Domain Entity Event name.
     */
    public function topic(): string;

    /**
     * Event type / name.
     */
    public function type(): string;

    /**
     * The event subject identifier (for example, the Aggregate / Entity ID).
     */
    public function subject(): string;

    /**
     * The event key (used for partitioning).
     *
     * @note This is basically Aggregate ID or Entity ID.
     */
    public function key(): string;

    /**
     * The event time.
     */
    public function time(): string;

    /**
     * The event metadata.
     */
    public function metadata(): array;

    /**
     * The event data (see Event-Carried State Transfer).
     *
     * @note For a balanced case, we may want to add only the relevant data to the payload.
     *       This is useful for cases when we want to avoid redundant data in the payload,
     *       but also without requiring the consumer to make request to the source of truth by ID,
     *       while still being able to reconstruct the aggregate from the payload.
     */
    public function data(): array;

    /**
     * The event payload.
     */
    public function payload(): array;
}
