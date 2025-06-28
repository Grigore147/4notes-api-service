<?php

declare(strict_types=1);

namespace App\Domains\Note\Presentation\Resources;

use Illuminate\Http\Request;
use App\Core\Presentation\Resources\JsonResource;

/**
 * SpaceResource
 *
 * @method static fromEntity(?SpaceEntityContract $notebook, array $requestedFields = ['*']): ?static
 */
final class SpaceResource extends JsonResource
{
    /**
     * The resource name.
     *
     * @var string
     */
    public const NAME = 'space';

    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        if ($this->resource === null) { return []; }

        return $this->onlySelected([
            'id' => $this->id,
            'userId' => $this->userId,
            'name' => $this->name,
            'description' => $this->description,
            'createdAt' => $this->createdAt,
            'updatedAt' => $this->updatedAt
        ]);
    }
}
