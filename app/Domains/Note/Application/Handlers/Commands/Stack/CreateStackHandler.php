<?php

declare(strict_types=1);

namespace App\Domains\Note\Application\Handlers\Commands\Stack;

use App\Domains\Note\Domain\Entities\Stack;
use App\Core\Application\CommandBus\CommandHandler;
use App\Domains\Note\Application\Services\StackService;
use App\Domains\Note\Application\Commands\Stack\CreateStack;
use App\Core\Application\CommandBus\Contracts\CommandContract;
use App\Domains\Note\Domain\Entities\Contracts\StackEntityContract;

/**
 * Create Stack Command Handler
 */
final class CreateStackHandler extends CommandHandler
{
    /**
     * CreateStackHandler constructor.
     *
     * @param StackService $spaceService
     */
    public function __construct(
        protected StackService $spaceService
    ) {}

    /**
     * Handle the command
     *
     * @param CreateStack $command
     * @return StackEntityContract
     */
    public function handle(CommandContract $command): StackEntityContract
    {
        return $this->spaceService->create(new Stack(
            userId: $command->user->id,
            spaceId: $command->spaceId,
            name: $command->name
        ));
    }
}
