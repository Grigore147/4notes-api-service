<?php

declare(strict_types=1);

namespace App\Domains\Note\Application\Listeners\Notebook;

use Illuminate\Support\Facades\Log;
use App\Core\Application\EventBus\OutboxEventHandler;
use App\Domains\Note\Domain\Events\Notebook\NotebookCreated;
use App\Domains\Note\Domain\Events\Notebook\NotebookUpdated;

final class NotebookCreatedHandler extends OutboxEventHandler
{
    /**
     * Handle the event.
     *
     * @param NotebookCreated|NotebookUpdated $event
     */
    public function handle(NotebookCreated|NotebookUpdated $event): void
    {
        Log::debug('EVENT ID: '. $event->id);
        Log::debug($event->name(), $event->notebook->getAttributes());
    }
}
