<?php

declare(strict_types=1);

namespace App\Domains\Note\Application\Listeners\Space;

use Illuminate\Support\Facades\Log;
use App\Core\Application\EventBus\OutboxEventHandler;
use App\Domains\Note\Domain\Events\Space\SpaceCreated;
use App\Domains\Note\Domain\Events\Space\SpaceUpdated;

final class SpaceCreatedHandler extends OutboxEventHandler
{
    /**
     * Handle the event.
     *
     * @param SpaceCreated|SpaceUpdated $event
     */
    public function handle(SpaceCreated|SpaceUpdated $event): void
    {
        Log::debug('EVENT ID: '. $event->id);
        Log::debug($event->name(), $event->space->getAttributes());
    }
}
