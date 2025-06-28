<?php

declare(strict_types=1);

namespace App\Core\Application\CommandBus\Contracts;

use Illuminate\Http\Request;

interface CommandContract
{
    /**
     * Create a command from an array of data.
     *
     * @param array $data
     * @return static
     */
    public static function fromArray(array $data): static;

    /**
     * Create a command from a request.
     *
     * @param Request $request
     * @return static
     */
    public static function fromRequest(Request $request): static;
}
