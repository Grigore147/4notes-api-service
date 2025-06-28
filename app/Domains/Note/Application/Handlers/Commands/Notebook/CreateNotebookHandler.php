<?php

declare(strict_types=1);

namespace App\Domains\Note\Application\Handlers\Commands\Notebook;

use App\Domains\Note\Domain\Entities\Notebook;
use App\Core\Application\CommandBus\CommandHandler;
use App\Core\Application\CommandBus\Contracts\CommandContract;
use App\Domains\Note\Domain\Services\Contracts\NotebookService;
use App\Domains\Note\Application\Commands\Notebook\CreateNotebook;
use App\Domains\Note\Domain\Entities\Contracts\NotebookEntityContract;

/**
 * Create Notebook Command Handler
 */
final class CreateNotebookHandler extends CommandHandler
{
    /**
     * CreateNotebookHandler constructor.
     *
     * @param NotebookService $notebookService
     */
    public function __construct(
        protected NotebookService $notebookService
    ) {}

    /**
     * Handle the command
     *
     * @param CreateNotebook $command
     * @return NotebookEntityContract
     */
    public function handle(CommandContract $command): NotebookEntityContract
    {
        return $this->notebookService->create(new Notebook(
            userId: $command->user->id,
            spaceId: $command->spaceId,
            stackId: $command->stackId,
            name: $command->name,
            createdAt: $command->createdAt,
            updatedAt: $command->updatedAt
        ));
    }
}
