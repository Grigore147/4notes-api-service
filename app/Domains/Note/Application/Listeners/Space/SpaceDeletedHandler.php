<?php

declare(strict_types=1);

namespace App\Domains\Note\Application\Listeners\Space;

use Illuminate\Support\Facades\Log;
use App\Core\Application\EventBus\OutboxEventHandler;
use App\Domains\Note\Domain\Events\Space\SpaceDeleted;

final class SpaceDeletedHandler extends OutboxEventHandler
{
    /**
     * Handle the event.
     *
     * @param SpaceDeleted $event
     */
    public function handle(SpaceDeleted $event): void
    {
        Log::debug('EVENT ID: '. $event->id);
        Log::debug('DELETED Space ID: '. $event->space->getId());
    }
}
