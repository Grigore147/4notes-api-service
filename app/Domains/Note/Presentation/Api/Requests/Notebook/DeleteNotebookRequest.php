<?php

declare(strict_types=1);

namespace App\Domains\Note\Presentation\Api\Requests\Notebook;

use App\Core\Presentation\Http\Requests\ApiRequest;
use App\Domains\Note\Application\Validation\NotebookRules;
use App\Domains\Note\Application\Commands\Notebook\DeleteNotebook;

final class DeleteNotebookRequest extends ApiRequest
{
    public const COMMAND = DeleteNotebook::class;

    public function rules(): array
    {
        return NotebookRules::delete();
    }
}
