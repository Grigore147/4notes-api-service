<?php

declare(strict_types=1);

namespace App\Domains\Note\Application\Listeners\Stack;

use Illuminate\Support\Facades\Log;
use App\Core\Application\EventBus\OutboxEventHandler;
use App\Domains\Note\Domain\Events\Stack\StackDeleted;

final class StackDeletedHandler extends OutboxEventHandler
{
    /**
     * Handle the event.
     *
     * @param StackDeleted $event
     */
    public function handle(StackDeleted $event): void
    {
        Log::debug('EVENT ID: '. $event->id);
        Log::debug('DELETED Stack ID: '. $event->stack->getId());
    }
}
