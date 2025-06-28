<?php

declare(strict_types=1);

namespace App\Domains\Note\Application\Commands\Space;

use Carbon\Carbon;
use Illuminate\Contracts\Auth\Authenticatable;
use App\Core\Application\CommandBus\Command;

final class CreateSpace extends Command
{
    public function __construct(
        /**
         * User
         *
         * @var Authenticatable $user
         */
        public ?Authenticatable $user,

        /**
         * Space name
         *
         * @var string $name
         */
        public string $name = '',

        /**
         * Space description
         *
         * @var string $description
         */
        public string $description = '',

        /**
         * Space created at
         *
         * @var Carbon $createdAt
         */
        public ?Carbon $createdAt = null,
        
        /**
         * Space updated at
         *
         * @var Carbon $updatedAt
         */
        public ?Carbon $updatedAt = null
    ) {}
}
