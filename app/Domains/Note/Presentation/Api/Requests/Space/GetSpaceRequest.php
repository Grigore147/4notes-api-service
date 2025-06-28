<?php

declare(strict_types=1);

namespace App\Domains\Note\Presentation\Api\Requests\Space;

use App\Core\Presentation\Http\Requests\ApiRequest;
use App\Domains\Note\Application\Queries\Space\GetSpaceById;
use App\Domains\Note\Application\Validation\SpaceRules;

final class GetSpaceRequest extends ApiRequest
{
    public const QUERY = GetSpaceById::class;

    public function rules(): array
    {
        return SpaceRules::get();
    }
}
