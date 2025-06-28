<?php

declare(strict_types=1);

namespace App\Core\Application\CommandBus\Contracts;

use App\Core\Application\CommandBus\Contracts\CommandContract;

interface CommandHandlerContract
{
    /**
     * Handle the command
     *
     * @param CommandContract $command
     * @return mixed
     */
    public function handle(CommandContract $command): mixed;
}
