<?php

declare(strict_types=1);

namespace App\Core\Application\QueryBus;

use Illuminate\Http\Request;
use App\Core\Application\QueryBus\Contracts\QueryContract;

abstract class Query implements QueryContract // @pest-arch-ignore-line
{
    /**
     * Create a new query instance from an array.
     *
     * @param  array  $data
     * @return static
     */
    public static function fromArray(array $data): static
    {
        $classReflection = new \ReflectionClass(static::class);

        return $classReflection->newInstanceArgs($data);
    }

    /**
     * Create a new query instance from a request.
     *
     * @param  Request  $request
     * @return static
     */
    public static function fromRequest(Request $request): static
    {
        return static::fromArray([...$request->validated(), 'user' => $request->user()]);
    }
}
