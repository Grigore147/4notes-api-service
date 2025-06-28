<?php

declare(strict_types=1);

namespace App\Core\Application\CommandBus;

use App\Core\Application\CommandBus\Contracts\CommandContract;
use App\Core\Application\CommandBus\Contracts\CommandHandlerContract;
use App\Core\Application\CommandBus\Contracts\CommandBus as CommandBusContract;

/**
 * CommandBus
 *
 * @note Laravel's Illuminate\Bus\Dispatcher could be used as the core of the CommandBus
 *
 * @package App\Core\Application\CommandBus
 */
final class CommandBus implements CommandBusContract
{
    /**
     * @var array <class-string<CommandContract>, class-string<CommandHandler>>
     */
    protected array $map = [];

    /**
     * Map the command to the handler
     *
     * @param array <class-string<CommandContract>, class-string<CommandHandler>> $map
     * @return self
     */
    public function map(array $map): self
    {
        $this->map = $map;

        return $this;
    }

    /**
     * Handle the command
     *
     * @param CommandContract $command
     * @return mixed
     */
    public function handle(CommandContract $command): mixed
    {
        /** @var CommandHandlerContract $handler */
        $handler = app($this->map[$command::class]);

        return $handler->handle($command);
    }

    /**
     * Dispatch the command
     *
     * @param CommandContract $command
     * @return mixed
     */
    public static function dispatch(CommandContract $command): mixed
    {
        return app(CommandBusContract::class)->dispatch($command);
    }
}
