<?php

declare(strict_types=1);

namespace App\Domains\Note\Presentation\Api\Requests\Note;

use App\Core\Presentation\Http\Requests\ApiRequest;
use App\Domains\Note\Application\Queries\Note\GetNoteById;
use App\Domains\Note\Application\Validation\NoteRules;

final class GetNoteRequest extends ApiRequest
{
    public const QUERY = GetNoteById::class;

    public function rules(): array
    {
        return NoteRules::get();
    }
}
