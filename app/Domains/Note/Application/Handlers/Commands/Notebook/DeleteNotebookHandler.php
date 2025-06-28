<?php

declare(strict_types=1);

namespace App\Domains\Note\Application\Handlers\Commands\Notebook;

use App\Domains\Note\Domain\Entities\Notebook;
use App\Core\Application\CommandBus\CommandHandler;
use App\Core\Application\CommandBus\Contracts\CommandContract;
use App\Domains\Note\Domain\Services\Contracts\NotebookService;
use App\Domains\Note\Application\Commands\Notebook\DeleteNotebook;

/**
 * Delete Notebook Command Handler
 */
final class DeleteNotebookHandler extends CommandHandler
{
    /**
     * DeleteNotebookHandler constructor.
     *
     * @param NotebookService $notebookService
     */
    public function __construct(
        private NotebookService $notebookService
    ) {}

    /**
     * Handle the command
     *
     * @param DeleteNotebook $command
     * @return bool
     */
    public function handle(CommandContract $command): bool
    {
        return $this->notebookService->delete(new Notebook(
            id: $command->id
        ));
    }
}
