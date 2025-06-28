<?php

declare(strict_types=1);

namespace App\Domains\Note\Application\Validation;

use App\Core\Application\Validation\Rules;
use App\Domains\Note\Application\Validation\Rules\SpaceId;

final class StackRules extends Rules
{
    public static function create(): array
    {
        return [
            'spaceId' => ['required', new SpaceId],
            'name' => 'required|string|max:64'
        ];
    }

    public static function update(): array
    {
        return [
            'spaceId' => ['sometimes', 'required', new SpaceId],
            'name' => 'sometimes|required|string|max:64'
        ];
    }
}
