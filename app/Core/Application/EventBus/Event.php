<?php

declare(strict_types=1);

namespace App\Core\Application\EventBus;

use ReflectionClass;
use Ramsey\Uuid\Uuid;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use App\Core\Application\EventBus\Contracts\Event as DomainEventContract;

/**
 * CloudEvent
 *
 * @package App\Core\Application\EventBus
 */
abstract class Event implements DomainEventContract
{
    use Dispatchable, SerializesModels;

    public function __construct(
        /**
         * Unique identifier for the event (used for idempotency checks for example).
         *
         * @var ?string Event ID (UUID v4)
         */
        public ?string $id = null
    ) {
        $this->id ??= Uuid::uuid4()->toString();
    }

    public function id(): string
    {
        return $this->id;
    }

    public function name(): string
    {
        return static::getName();
    }

    public static function getName(): string
    {
        return (new ReflectionClass(static::class))->getShortName();
    }
}
