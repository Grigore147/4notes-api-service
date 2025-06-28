<?php

declare(strict_types=1);

namespace App\Domains\Note\Presentation\Resources;

use Illuminate\Http\Request;
use App\Core\Presentation\Resources\JsonResource;
use App\Domains\Note\Domain\Repositories\SpacesRepository;
use App\Domains\Note\Presentation\Resources\SpaceResource;

/**
 * StackResource
 *
 * @method static fromEntity(?StackEntityContract $notebook, array $requestedFields = ['*']): ?static
 */
final class StackResource extends JsonResource
{
    /**
     * The resource name.
     *
     * @var string
     */
    public const NAME = 'stack';

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
            'spaceId' => $this->spaceId,
            'space' => $this->whenNotNull(
                SpaceResource::make(
                    app(SpacesRepository::class)->toEntity($this->space)
                )
            ),
            'userId' => $this->userId,
            'name' => $this->name,
            'createdAt' => $this->createdAt,
            'updatedAt' => $this->updatedAt
        ]);
    }
}
