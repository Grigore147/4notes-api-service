<?php

declare(strict_types=1);

namespace App\Domains\Note\Application\Commands\Note;

use Carbon\Carbon;
use Ramsey\Uuid\UuidInterface;
use Illuminate\Contracts\Auth\Authenticatable;
use App\Core\Application\CommandBus\Command;

final class CreateNote extends Command
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
         * @var UuidInterface|string|null $userId
         */
        public UuidInterface|string|null $userId = null,

        /**
         * Notebook ID
         *
         * @var UuidInterface|string|null $notebookId
         */
        public UuidInterface|string|null $notebookId = null,

        /**
         * Note title
         *
         * @var string $title
         */
        public string $title = '',

        /**
         * Note content
         *
         * @var string $content
         */
        public string $content = '',

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
