<?php

declare(strict_types=1);

namespace App\Domains\Note\Application\Queries\Stack;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Illuminate\Contracts\Auth\Authenticatable;
use App\Core\Application\QueryBus\Query;

final class FindStackById extends Query
{
    public function __construct(
        /**
         * User
         *
         * @var Authenticatable $user
         */
        public ?Authenticatable $user,
        
        /**
         * Stack ID
         *
         * @var string|UuidInterface $id
         */
        public string|UuidInterface $id
    ) {
        if (is_string($this->id)) {
            $this->id = Uuid::fromString($this->id);
        }
    }
}
