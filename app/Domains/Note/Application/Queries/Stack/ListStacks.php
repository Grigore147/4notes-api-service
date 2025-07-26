<?php

declare(strict_types=1);

namespace App\Domains\Note\Application\Queries\Stack;

use Illuminate\Contracts\Auth\Authenticatable;
use App\Core\Application\QueryBus\FilterableQuery;
use App\Core\Application\QueryBus\Query;
use App\Domains\Auth\Infrastructure\Models\User;

final class ListStacks extends Query
{
    use FilterableQuery;

    public function __construct(
        public ?Authenticatable $user = null,
        public ?array $filters = []
    ) {
        if ($this->user) {
            /** @var User $user */
            $this->filters['userId'] = $user->id;
        }
    }
}
