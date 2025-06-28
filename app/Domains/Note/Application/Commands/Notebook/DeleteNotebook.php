<?php

declare(strict_types=1);

namespace App\Domains\Note\Application\Commands\Notebook;

use Ramsey\Uuid\UuidInterface;
use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\Authenticatable;
use App\Core\Application\CommandBus\Command;

final class DeleteNotebook extends Command
{
    public function __construct(
        /**
         * User
         *
         * @var Authenticatable $user
         */
        public ?Authenticatable $user,
        
        /**
         * Notebook ID
         *
         * @var UuidInterface $id
         */
        public UuidInterface $id
    ) {}

    public static function fromRequest(Request $request): static
    {
        return new static(
            user: $request->user(),
            id: $request->notebook->getId()
        );
    }
}
