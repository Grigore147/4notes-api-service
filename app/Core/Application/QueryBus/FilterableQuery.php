<?php

declare(strict_types=1);

namespace App\Core\Application\QueryBus;

use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\Authenticatable;

trait FilterableQuery
{
    public function __construct(
        public ?Authenticatable $user = null,
        public ?array $filters = []
    ) {}

    public static function fromRequest(Request $request): static
    {
        return new static(
            user: $request->user(),
            filters: $request->query()
        );
    }
}
