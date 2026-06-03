<?php

declare(strict_types=1);

namespace App\Domains\Note\Presentation\Resources;

use Illuminate\Http\Request;
use App\Core\Presentation\Resources\JsonResource;
use App\Domains\Note\Domain\Repositories\NotebooksRepository;
use App\Domains\Note\Domain\Repositories\StacksRepository;

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
            'updatedAt' => $this->updatedAt,
            'stacks' => $this->whenHasCollection(
                $this->stacks, fn () => StackCollection::make(app(StacksRepository::class)->toEntities($this->stacks)), $request
            ),
            'notebooks' => $this->whenHasCollection(
                $this->notebooks, fn () => NotebookCollection::make(app(NotebooksRepository::class)->toEntities($this->notebooks)), $request
            )
        ]);
    }
}
