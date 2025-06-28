<?php

declare(strict_types=1);

namespace App\Domains\Note\Presentation\Api\Requests\Notebook;

use App\Core\Presentation\Http\Requests\ApiRequest;
use App\Domains\Note\Application\Validation\NotebookRules;
use App\Domains\Note\Application\Commands\Notebook\UpdateNotebook;

final class UpdateNotebookRequest extends ApiRequest
{
    public const COMMAND = UpdateNotebook::class;

    public function rules(): array
    {
        return NotebookRules::update();
    }
}
