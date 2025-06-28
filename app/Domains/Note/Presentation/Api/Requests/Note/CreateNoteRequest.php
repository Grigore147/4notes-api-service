<?php

declare(strict_types=1);

namespace App\Domains\Note\Presentation\Api\Requests\Note;

use App\Core\Presentation\Http\Requests\ApiRequest;
use App\Domains\Note\Application\Validation\NoteRules;
use App\Domains\Note\Application\Commands\Note\CreateNote;

final class CreateNoteRequest extends ApiRequest
{
    public const COMMAND = CreateNote::class;

    public function rules(): array
    {
        return NoteRules::create();
    }
}
