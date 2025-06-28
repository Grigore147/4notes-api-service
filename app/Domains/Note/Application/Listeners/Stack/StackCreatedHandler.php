<?php

declare(strict_types=1);

namespace App\Domains\Note\Application\Listeners\Stack;

use Illuminate\Support\Facades\Log;
use App\Core\Application\EventBus\OutboxEventHandler;
use App\Domains\Note\Domain\Events\Stack\StackCreated;
use App\Domains\Note\Domain\Events\Stack\StackUpdated;

final class StackCreatedHandler extends OutboxEventHandler
{
    /**
     * Handle the event.
     *
     * @param StackCreated|StackUpdated $event
     */
    public function handle(StackCreated|StackUpdated $event): void
    {
        Log::debug('EVENT ID: '. $event->id);
        Log::debug($event->name(), $event->stack->getAttributes());
    }
}
