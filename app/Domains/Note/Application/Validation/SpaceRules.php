<?php

declare(strict_types=1);

namespace App\Domains\Note\Application\Validation;

use App\Core\Application\Validation\Rules;

final class SpaceRules extends Rules
{
    public static function create(): array
    {
        return [
            'name' => 'required|string|max:64',
            'description' => 'string|max:1000'
        ];
    }

    public static function update(): array
    {
        return [
            'name' => 'sometimes|required|string|max:64',
            'description' => 'sometimes|string|max:1000'
        ];
    }
}
