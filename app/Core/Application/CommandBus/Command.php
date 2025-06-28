<?php

declare(strict_types=1);

namespace App\Core\Application\CommandBus;

use Illuminate\Http\Request;
use App\Core\Application\CommandBus\Contracts\CommandContract;

abstract class Command implements CommandContract // @pest-arch-ignore-line
{
    /**
     * Create a new command instance from an array.
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
     * Create a new command instance from a request.
     *
     * @param  Request  $request
     * @return static
     */
    public static function fromRequest(Request $request): static
    {
        // NOTE: Appending the user to the data may not be the best approach here, trade-offs have to be considered.
        return static::fromArray([...$request->validated(), 'user' => $request->user()]);
    }
}
