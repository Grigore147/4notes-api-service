<?php

declare(strict_types=1);

namespace App\Domains\Note\Application\Handlers\Commands\Space;

use App\Domains\Note\Domain\Entities\Space;
use App\Core\Application\CommandBus\CommandHandler;
use App\Domains\Note\Application\Services\SpaceService;
use App\Domains\Note\Application\Commands\Space\UpdateSpace;
use App\Core\Application\CommandBus\Contracts\CommandContract;
use App\Domains\Note\Domain\Entities\Contracts\SpaceEntityContract;

/**
 * Update Space Command Handler
 */
final class UpdateSpaceHandler extends CommandHandler
{
    /**
     * UpdateSpaceHandler constructor.
     *
     * @param SpaceService $spaceService
     */
    public function __construct(
        private SpaceService $spaceService
    ) {}

    /**
     * Handle the command
     *
     * @param UpdateSpace $command
     * @return SpaceEntityContract
     */
    public function handle(CommandContract $command): SpaceEntityContract
    {
        return $this->spaceService->update(new Space(
            id: $command->id,
            userId: $command->user->id,
            name: $command->name,
            description: $command->description,
            createdAt: $command->createdAt,
            updatedAt: $command->updatedAt
        ));
    }
}
