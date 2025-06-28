<?php

declare(strict_types=1);

namespace App\Domains\Note\Application\Handlers\Commands\Note;

use App\Domains\Note\Domain\Entities\Note;
use App\Core\Application\CommandBus\CommandHandler;
use App\Domains\Note\Application\Commands\Note\CreateNote;
use App\Domains\Note\Domain\Services\Contracts\NoteService;
use App\Core\Application\CommandBus\Contracts\CommandContract;
use App\Domains\Note\Domain\Entities\Contracts\NoteEntityContract;

/**
 * Create Note Command Handler
 */
final class CreateNoteHandler extends CommandHandler
{
    /**
     * CreateNoteHandler constructor.
     *
     * @param NoteService $noteService
     */
    public function __construct(
        protected NoteService $noteService
    ) {}

    /**
     * Handle the command
     *
     * @param CreateNote $command
     * @return NoteEntityContract
     */
    public function handle(CommandContract $command): mixed
    {
        return $this->noteService->create(new Note(
            userId: $command->user->id,
            notebookId: $command->notebookId,
            title: $command->title,
            content: $command->content,
            createdAt: $command->createdAt,
            updatedAt: $command->updatedAt
        ));
    }
}
