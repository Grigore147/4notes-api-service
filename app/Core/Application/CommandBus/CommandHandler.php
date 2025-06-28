<?php

declare(strict_types=1);

namespace App\Core\Application\CommandBus;

use App\Core\Application\CommandBus\Contracts\CommandHandlerContract;

/**
 * CommandHandler
 *
 * @package App\Core\Application\CommandBus
 */
abstract class CommandHandler implements CommandHandlerContract // @pest-arch-ignore-line
{
}
