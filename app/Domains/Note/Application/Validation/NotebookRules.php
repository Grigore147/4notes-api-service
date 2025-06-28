<?php

declare(strict_types=1);

namespace App\Domains\Note\Application\Validation;

use App\Core\Application\Validation\Rules;
use App\Domains\Note\Application\Validation\Rules\SpaceId;
use App\Domains\Note\Application\Validation\Rules\StackId;

final class NotebookRules extends Rules
{
    public static function create(): array
    {
        return [
            'spaceId' => ['required', new SpaceId],
            'stackId' => ['sometimes', 'nullable', new StackId],
            'name' => 'required|string|max:64'
        ];
    }

    public static function update(): array
    {
        return [
            'spaceId' => ['sometimes', new SpaceId],
            'stackId' => ['sometimes', 'nullable', new StackId],
            'name' => 'sometimes|required|string|max:64'
        ];
    }
}
