<?php

declare(strict_types=1);

namespace App\Core\Application\Services\DomainEventsPublisher;

use Illuminate\Support\Str;
use Junges\Kafka\Facades\Kafka;
use Junges\Kafka\Message\Message as KafkaMessage;
use App\Core\Application\EventBus\Contracts\DomainEvent;

final class KafkaEventPublisher implements EventPublisher
{
    /**
     * @inheritDoc
     */
    public function publish(DomainEvent $event): void
    {
        $headers = array_merge([
            'environment' => config('app.env'),
            'tenant' => '4Notes',
            'source' => config('app.name'),
            'request-id' => request()->header('X-Request-Id', Str::uuid()),
            'correlation-id' => request()->header('X-Correlation-Id', Str::uuid())
        ], $event->metadata());

        $message = KafkaMessage::create()
            ->withHeaders($headers)
            ->withKey($event->key())
            ->withBody($event->payload());

        // Publish on Domain Entity topic (e.g. notes.space)
        Kafka::publish()
            ->withMessage($message)
            ->onTopic($event->topic())
            ->send();
        
        // Publish on Domain Entity Event topic (e.g. notes.space.created)
        Kafka::publish()
            ->withMessage($message)
            ->onTopic($event->type())
            ->send();
    }
}
