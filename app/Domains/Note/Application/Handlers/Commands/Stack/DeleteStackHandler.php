<?php

declare(strict_types=1);

namespace App\Domains\Note\Application\Handlers\Commands\Stack;

use App\Domains\Note\Domain\Entities\Stack;
use App\Core\Application\CommandBus\CommandHandler;
use App\Core\Application\CommandBus\Contracts\CommandContract;
use App\Domains\Note\Application\Commands\Stack\DeleteStack;
use App\Domains\Note\Domain\Services\Contracts\StackService;

/**
 * Delete Stack Command Handler
 */
final class DeleteStackHandler extends CommandHandler
{
    /**
     * DeleteStackHandler constructor.
     *
     * @param StackService $stackService
     */
    public function __construct(
        private StackService $stackService
    ) {}

    /**
     * Handle the command
     *
     * @param DeleteStack $command
     * @return bool
     */
    public function handle(CommandContract $command): bool
    {
        return $this->stackService->delete(new Stack(
            id: $command->id
        ));
    }
}
