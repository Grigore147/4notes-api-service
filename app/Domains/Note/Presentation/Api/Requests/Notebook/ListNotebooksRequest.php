<?php

declare(strict_types=1);

namespace App\Domains\Note\Presentation\Api\Requests\Notebook;

use App\Core\Presentation\Http\Requests\ApiRequest;
use App\Domains\Note\Application\Validation\NotebookRules;
use App\Domains\Note\Application\Queries\Notebook\ListNotebooks;

final class ListNotebooksRequest extends ApiRequest
{
    public const QUERY = ListNotebooks::class;

    public function rules(): array
    {
        return NotebookRules::list();
    }
}
