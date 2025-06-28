<?php

declare(strict_types=1);

namespace App\Domains\Note\Application\Listeners\Note;

use Illuminate\Support\Facades\Log;
use App\Domains\Note\Domain\Events\Note\NoteDeleted;
use App\Core\Application\EventBus\OutboxEventHandler;

final class NoteDeletedHandler extends OutboxEventHandler
{
    /**
     * Handle the event.
     *
     * @param NoteDeleted $event
     */
    public function handle(NoteDeleted $event): void
    {
        Log::debug('EVENT ID: '. $event->id);
        Log::debug('DELETED Note ID: '. $event->note->getId());
    }
}
