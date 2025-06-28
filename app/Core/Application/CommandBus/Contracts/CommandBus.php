<?php

declare(strict_types=1);

namespace App\Core\Application\CommandBus\Contracts;

use App\Core\Application\CommandBus\Contracts\CommandContract;

interface CommandBus
{
    /**
     * Map the command to the handler
     *
     * @param array <class-string<CommandContract>, class-string<CommandHandler>> $map
     * @return self
     */
    public function map(array $map): self;

    /**
     * Handle the command
     *
     * @param CommandContract $command
     * @return mixed
     */
    public function handle(CommandContract $command): mixed;

    /**
     * Dispatch the command
     *
     * @param CommandContract $command
     * @return mixed
     */
    public static function dispatch(CommandContract $command): mixed;
}
