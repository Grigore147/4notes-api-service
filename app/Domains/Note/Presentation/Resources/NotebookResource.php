<?php

declare(strict_types=1);

namespace App\Domains\Note\Presentation\Resources;

use Illuminate\Http\Request;
use App\Core\Presentation\Resources\JsonResource;
use App\Domains\Note\Domain\Repositories\SpacesRepository;
use App\Domains\Note\Domain\Repositories\StacksRepository;
use App\Domains\Note\Presentation\Resources\SpaceResource;
use App\Domains\Note\Presentation\Resources\StackResource;

/**
 * NotebookResource
 *
 * @method static fromEntity(?NotebookEntityContract $notebook, array $requestedFields = ['*']): ?static
 * @method static function make(...$parameters): static
 */
final class NotebookResource extends JsonResource
{
    /**
     * The resource name.
     *
     * @var string
     */
    public const NAME = 'notebook';

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
            'space' => $this->whenHasResource(
                SpaceResource::make(
                    app(SpacesRepository::class)->toEntity($this->space),
                    $request
                )
            ),
            'stackId' => $this->stackId,
            'stack' => $this->whenHasResource(
                StackResource::make(
                    app(StacksRepository::class)->toEntity($this->stack),
                    $request
                )
            ),
            'userId' => $this->userId,
            'name' => $this->name,
            'createdAt' => $this->createdAt,
            'updatedAt' => $this->updatedAt
        ]);
    }
}
