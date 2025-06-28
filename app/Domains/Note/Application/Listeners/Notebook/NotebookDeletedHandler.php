<?php

declare(strict_types=1);

namespace App\Domains\Note\Application\Listeners\Notebook;

use Illuminate\Support\Facades\Log;
use App\Core\Application\EventBus\OutboxEventHandler;
use App\Domains\Note\Domain\Events\Notebook\NotebookDeleted;

final class NotebookDeletedHandler extends OutboxEventHandler
{
    /**
     * Handle the event.
     *
     * @param NotebookDeleted $event
     */
    public function handle(NotebookDeleted $event): void
    {
        Log::debug('EVENT ID: '. $event->id);
        Log::debug('DELETED Notebook ID: '. $event->notebook->getId());
    }
}
