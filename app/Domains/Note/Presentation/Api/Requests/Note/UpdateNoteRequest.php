<?php

declare(strict_types=1);

namespace App\Domains\Note\Presentation\Api\Requests\Note;

use App\Core\Presentation\Http\Requests\ApiRequest;
use App\Domains\Note\Application\Validation\NoteRules;
use App\Domains\Note\Application\Commands\Note\UpdateNote;

final class UpdateNoteRequest extends ApiRequest
{
    public const COMMAND = UpdateNote::class;

    public function rules(): array
    {
        return NoteRules::update();
    }
}
