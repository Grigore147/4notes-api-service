<?php

declare(strict_types=1);

namespace App\Domains\Note\Presentation\Api\Requests\Notebook;

use App\Core\Presentation\Http\Requests\ApiRequest;
use App\Domains\Note\Application\Queries\Notebook\GetNotebookById;
use App\Domains\Note\Application\Validation\NotebookRules;

final class GetNotebookRequest extends ApiRequest
{
    public const QUERY = GetNotebookById::class;

    public function rules(): array
    {
        return NotebookRules::get();
    }
}
