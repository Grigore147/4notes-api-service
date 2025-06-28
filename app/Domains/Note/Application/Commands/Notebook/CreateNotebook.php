<?php

declare(strict_types=1);

namespace App\Domains\Note\Application\Commands\Notebook;

use Carbon\Carbon;
use Ramsey\Uuid\UuidInterface;
use App\Core\Application\CommandBus\Command;
use Illuminate\Contracts\Auth\Authenticatable;

final class CreateNotebook extends Command
{
    public function __construct(
        /**
         * User
         *
         * @var Authenticatable $user
         */
        public ?Authenticatable $user,

        /**
         * User ID
         *
         * @var UuidInterface $userId
         */
        public ?UuidInterface $userId = null,

        /**
         * Space ID
         *
         * @var UuidInterface|string|null $spaceId
         */
        public UuidInterface|string|null $spaceId = null,

        /**
         * Stack ID
         *
         * @var UuidInterface|string|null $spaceId
         */
        public UuidInterface|string|null $stackId = null,

        /**
         * Note name
         *
         * @var string $name
         */
        public ?string $name = '',

        /**
         * Note created at
         *
         * @var Carbon $createdAt
         */
        public ?Carbon $createdAt = null,
        
        /**
         * Note updated at
         *
         * @var Carbon $updatedAt
         */
        public ?Carbon $updatedAt = null
    ) {}
}
