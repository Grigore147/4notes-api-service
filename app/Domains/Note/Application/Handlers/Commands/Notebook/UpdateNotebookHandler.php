<?php

declare(strict_types=1);

namespace App\Domains\Note\Application\Handlers\Commands\Notebook;

use App\Core\Application\CommandBus\CommandHandler;
use App\Core\Application\CommandBus\Contracts\CommandContract;
use App\Domains\Note\Domain\Services\Contracts\NotebookService;
use App\Domains\Note\Application\Commands\Notebook\UpdateNotebook;
use App\Domains\Note\Domain\Entities\Contracts\NotebookEntityContract;

/**
 * Update Notebook Command Handler
 */
final class UpdateNotebookHandler extends CommandHandler
{
    /**
     * UpdateNotebookHandler constructor.
     *
     * @param NotebookService $notebookService
     */
    public function __construct(
        private NotebookService $notebookService
    ) {}

    /**
     * Handle the command
     *
     * @param UpdateNotebook $command
     * @return NotebookEntityContract
     */
    public function handle(CommandContract $command): NotebookEntityContract
    {
        return $this->notebookService->update($command->notebook);
    }
}
