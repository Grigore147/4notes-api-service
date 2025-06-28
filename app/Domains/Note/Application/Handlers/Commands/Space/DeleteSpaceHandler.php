<?php

declare(strict_types=1);

namespace App\Domains\Note\Application\Handlers\Commands\Space;

use App\Domains\Note\Domain\Entities\Space;
use App\Core\Application\CommandBus\CommandHandler;
use App\Core\Application\CommandBus\Contracts\CommandContract;
use App\Domains\Note\Application\Commands\Space\DeleteSpace;
use App\Domains\Note\Domain\Services\Contracts\SpaceService;

/**
 * Delete Space Command Handler
 */
final class DeleteSpaceHandler extends CommandHandler
{
    /**
     * DeleteSpaceHandler constructor.
     *
     * @param SpaceService $spaceService
     */
    public function __construct(
        private SpaceService $spaceService
    ) {}

    /**
     * Handle the command
     *
     * @param DeleteSpace $command
     * @return bool
     */
    public function handle(CommandContract $command): bool
    {
        return $this->spaceService->delete(new Space(
            id: $command->id
        ));
    }
}
