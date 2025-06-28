<?php

declare(strict_types=1);

namespace App\Domains\Note\Application\Handlers\Commands\Space;

use App\Domains\Note\Domain\Entities\Space;
use App\Core\Application\CommandBus\CommandHandler;
use App\Core\Application\CommandBus\Contracts\CommandContract;
use App\Domains\Note\Application\Services\SpaceService;
use App\Domains\Note\Application\Commands\Space\CreateSpace;
use App\Domains\Note\Domain\Entities\Contracts\SpaceEntityContract;

/**
 * Create Space Command Handler
 */
final class CreateSpaceHandler extends CommandHandler
{
    /**
     * CreateSpaceHandler constructor.
     *
     * @param SpaceService $spaceService
     */
    public function __construct(
        protected SpaceService $spaceService
    ) {}

    /**
     * Handle the command
     *
     * @param CreateSpace $command
     * @return SpaceEntityContract
     */
    public function handle(CommandContract $command): SpaceEntityContract
    {
        return $this->spaceService->create(new Space(
            userId: $command->user->id,
            name: $command->name,
            description: $command->description
        ));
    }
}
