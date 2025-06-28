<?php

declare(strict_types=1);

namespace App\Domains\Note\Presentation\Api\Requests\Space;

use App\Core\Presentation\Http\Requests\ApiRequest;
use App\Domains\Note\Application\Validation\SpaceRules;
use App\Domains\Note\Application\Queries\Space\ListSpaces;

final class ListSpacesRequest extends ApiRequest
{
    public const QUERY = ListSpaces::class;

    public function rules(): array
    {
        return SpaceRules::list();
    }
}
