<?php

declare(strict_types=1);

namespace App\Domains\Note\Presentation\Api\Requests\Stack;

use App\Core\Presentation\Http\Requests\ApiRequest;
use App\Domains\Note\Application\Validation\StackRules;
use App\Domains\Note\Application\Commands\Stack\UpdateStack;

final class UpdateStackRequest extends ApiRequest
{
    public const COMMAND = UpdateStack::class;

    public function rules(): array
    {
        return StackRules::update();
    }
}
