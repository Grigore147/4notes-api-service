<?php

declare(strict_types=1);

namespace App\Domains\Note\Application\Handlers\Commands\Note;

use App\Domains\Note\Domain\Entities\Note;
use App\Core\Application\CommandBus\CommandHandler;
use App\Core\Application\CommandBus\Contracts\CommandContract;
use App\Domains\Note\Application\Commands\Note\UpdateNote;
use App\Domains\Note\Domain\Services\Contracts\NoteService;
use App\Domains\Note\Domain\Entities\Contracts\NoteEntityContract;

/**
 * Update Note Command Handler
 */
final class UpdateNoteHandler extends CommandHandler
{
    /**
     * UpdateNoteHandler constructor.
     *
     * @param NoteService $noteService
     */
    public function __construct(
        private NoteService $noteService
    ) {}

    /**
     * Handle the command
     *
     * @param UpdateNote $command
     * @return NoteEntityContract
     */
    public function handle(CommandContract $command): NoteEntityContract
    {
        return $this->noteService->update($command->note);
    }
}
