<?php

declare(strict_types=1);

namespace App\Domains\Note\Presentation\Resources;

use Illuminate\Http\Request;
use App\Core\Presentation\Resources\JsonResource;
use App\Domains\Note\Domain\Repositories\NotebooksRepository;
use App\Domains\Note\Presentation\Resources\NotebookResource;

final class NoteResource extends JsonResource
{
    /**
     * The resource name.
     *
     * @var string
     */
    public const NAME = 'note';

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
            'notebookId' => $this->notebookId,
            'notebook' => $this->whenNotNull(
                NotebookResource::make(
                    app(NotebooksRepository::class)->toEntity($this->notebook)
                )
            ),
            'title' => $this->title,
            'content' => $this->content,
            'createdAt' => $this->createdAt,
            'updatedAt' => $this->updatedAt
        ]);
    }
}
