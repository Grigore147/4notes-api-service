<?php

declare(strict_types=1);

namespace App\Domains\Note\Application\Queries\Notebook;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\Authenticatable;
use App\Core\Application\QueryBus\Query;

final class GetNotebookById extends Query
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
         * @var string|UuidInterface $id
         */
        public string|UuidInterface $id
    ) {
        if (is_string($this->id)) {
            $this->id = Uuid::fromString($this->id);
        }
    }

    /**
     * Create a new command instance from a request.
     *
     * @param  Request  $request
     * @return static
     */
    public static function fromRequest(Request $request): static
    {
        return new static(
            user: $request->user(),
            id: $request->notebook->getId()
        );
    }
}
