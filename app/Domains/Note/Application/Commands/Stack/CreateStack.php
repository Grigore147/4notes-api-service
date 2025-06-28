<?php

declare(strict_types=1);

namespace App\Domains\Note\Application\Commands\Stack;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Ramsey\Uuid\UuidInterface;
use App\Core\Application\CommandBus\Command;
use Illuminate\Contracts\Auth\Authenticatable;

final class CreateStack extends Command
{
    public function __construct(
        /**
         * User
         *
         * @var Authenticatable $user
         */
        public ?Authenticatable $user,

        /**
         * Space ID
         *
         * @var UuidInterface|string $spaceId
         */
        public UuidInterface|string $spaceId,

        /**
         * Stack name
         *
         * @var string $name
         */
        public string $name = '',

        /**
         * Stack created at
         *
         * @var Carbon $createdAt
         */
        public ?Carbon $createdAt = null,
        
        /**
         * Stack updated at
         *
         * @var Carbon $updatedAt
         */
        public ?Carbon $updatedAt = null
    ) {}
}
