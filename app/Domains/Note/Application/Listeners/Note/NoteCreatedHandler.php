<?php

declare(strict_types=1);

namespace App\Domains\Note\Application\Listeners\Note;

use Illuminate\Support\Facades\Log;
use App\Domains\Note\Domain\Events\Note\NoteCreated;
use App\Domains\Note\Domain\Events\Note\NoteUpdated;
use App\Core\Application\EventBus\OutboxEventHandler;

final class NoteCreatedHandler extends OutboxEventHandler
{
    /**
     * Handle the event.
     *
     * @param NoteCreated|NoteUpdated $event
     */
    public function handle(NoteCreated|NoteUpdated $event): void
    {
        Log::debug('EVENT ID: '. $event->id);
        Log::debug($event->name(), $event->note->getAttributes());
    }
}
