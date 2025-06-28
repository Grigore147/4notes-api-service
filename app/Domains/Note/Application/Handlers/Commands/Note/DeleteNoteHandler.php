<?php

declare(strict_types=1);

namespace App\Domains\Note\Application\Handlers\Commands\Note;

use App\Domains\Note\Domain\Entities\Note;
use App\Core\Application\CommandBus\CommandHandler;
use App\Core\Application\CommandBus\Contracts\CommandContract;
use App\Domains\Note\Application\Commands\Note\DeleteNote;
use App\Domains\Note\Domain\Services\Contracts\NoteService;

/**
 * Delete Note Command Handler
 */
final class DeleteNoteHandler extends CommandHandler
{
    /**
     * DeleteNoteHandler constructor.
     *
     * @param NoteService $noteService
     */
    public function __construct(
        private NoteService $noteService
    ) {}

    /**
     * Handle the command
     *
     * @param DeleteNote $command
     * @return bool
     */
    public function handle(CommandContract $command): bool
    {
        return $this->noteService->delete(new Note(
            id: $command->id
        ));
    }
}
