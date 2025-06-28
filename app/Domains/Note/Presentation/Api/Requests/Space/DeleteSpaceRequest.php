<?php

declare(strict_types=1);

namespace App\Domains\Note\Presentation\Api\Requests\Space;

use App\Core\Presentation\Http\Requests\ApiRequest;
use App\Domains\Note\Application\Validation\SpaceRules;
use App\Domains\Note\Application\Commands\Space\DeleteSpace;

final class DeleteSpaceRequest extends ApiRequest
{
    public const COMMAND = DeleteSpace::class;

    public function rules(): array
    {
        return SpaceRules::delete();
    }
}
