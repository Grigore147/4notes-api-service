<?php

declare(strict_types=1);

namespace App\Domains\Note\Presentation\Api\Requests\Stack;

use App\Core\Presentation\Http\Requests\ApiRequest;
use App\Domains\Note\Application\Validation\StackRules;
use App\Domains\Note\Application\Queries\Stack\ListStacks;

final class ListStacksRequest extends ApiRequest
{
    public const QUERY = ListStacks::class;

    public function rules(): array
    {
        return StackRules::list();
    }
}
