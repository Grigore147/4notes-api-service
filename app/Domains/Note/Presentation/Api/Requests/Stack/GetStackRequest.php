<?php

declare(strict_types=1);

namespace App\Domains\Note\Presentation\Api\Requests\Stack;

use App\Core\Presentation\Http\Requests\ApiRequest;
use App\Domains\Note\Application\Queries\Stack\GetStackById;
use App\Domains\Note\Application\Validation\StackRules;

final class GetStackRequest extends ApiRequest
{
    public const QUERY = GetStackById::class;

    public function rules(): array
    {
        return StackRules::get();
    }
}
