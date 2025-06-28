<?php

declare(strict_types=1);

namespace App\Domains\Note\Presentation\Api\Requests\Note;

use App\Core\Presentation\Http\Requests\ApiRequest;
use App\Domains\Note\Application\Validation\NoteRules;
use App\Domains\Note\Application\Queries\Note\ListNotes;

final class ListNotesRequest extends ApiRequest
{
    public const QUERY = ListNotes::class;

    public function rules(): array
    {
        return NoteRules::list();
    }
}
