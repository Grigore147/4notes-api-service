<?php

declare(strict_types=1);

namespace App\Domains\Note\Application\Validation;

use App\Core\Application\Validation\Rules;
use App\Domains\Note\Application\Validation\Rules\NotebookId;

final class NoteRules extends Rules
{
    public static function create(): array
    {
        return [
            'notebookId' => ['required', new NotebookId],
            'title' => 'required|string|max:255',
            'content' => 'string'
        ];
    }

    public static function update(): array
    {
        return [
            'notebookId' => ['sometimes', 'required', new NotebookId],
            'title' => 'sometimes|required|string|max:255',
            'content' => 'sometimes|string'
        ];
    }
}
