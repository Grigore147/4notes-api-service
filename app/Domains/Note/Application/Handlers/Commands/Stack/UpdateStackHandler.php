<?php

declare(strict_types=1);

namespace App\Domains\Note\Application\Handlers\Commands\Stack;

use App\Domains\Note\Domain\Entities\Stack;
use App\Core\Application\CommandBus\CommandHandler;
use App\Core\Application\CommandBus\Contracts\CommandContract;
use App\Domains\Note\Application\Commands\Stack\UpdateStack;
use App\Domains\Note\Domain\Services\Contracts\StackService;
use App\Domains\Note\Domain\Entities\Contracts\StackEntityContract;

/**
 * Update Stack Command Handler
 */
final class UpdateStackHandler extends CommandHandler
{
    /**
     * UpdateStackHandler constructor.
     *
     * @param StackService $stackService
     */
    public function __construct(
        private StackService $stackService
    ) {}

    /**
     * Handle the command
     *
     * @param UpdateStack $command
     * @return StackEntityContract
     */
    public function handle(CommandContract $command): StackEntityContract
    {
        return $this->stackService->update($command->stack);
    }
}
