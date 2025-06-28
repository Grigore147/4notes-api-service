<?php

declare(strict_types=1);

namespace App\Domains\Note\Presentation\Api\Requests\Note;

use App\Core\Presentation\Http\Requests\ApiRequest;
use App\Domains\Note\Application\Validation\NoteRules;
use App\Domains\Note\Application\Commands\Note\DeleteNote;

final class DeleteNoteRequest extends ApiRequest
{
    public const COMMAND = DeleteNote::class;

    public function rules(): array
    {
        return NoteRules::delete();
    }
}
