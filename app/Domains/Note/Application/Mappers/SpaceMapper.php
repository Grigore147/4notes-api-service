<?php

declare(strict_types=1);

namespace App\Domains\Note\Application\Mappers;

use App\Domains\Note\Domain\Entities\Space;
use App\Domains\Note\Infrastructure\Database\Eloquent\Models\Space as SpaceModel;

final class SpaceMapper
{
    public static function arrayToEntity(array $data): Space
    {
        return Space::fromArray($data);
    }

    public static function entityToArray(Space $space): array
    {
        return $space->toArray();
    }

    public function modelToArray(SpaceModel $space): array
    {
        return [
            'id' => $space->id,
            'userId' => $space->user_id,
            'name' => $space->name,
            'description' => $space->description,
            'createdAt' => $space->created_at,
            'updatedAt' => $space->updated_at
        ];
    }

    public function arrayToModel(array $data): SpaceModel
    {
        return new SpaceModel([
            'id' => $data['id'],
            'user_id' => $data['userId'],
            'name' => $data['name'],
            'description' => $data['description'],
            'created_at' => $data['createdAt'],
            'updated_at' => $data['updatedAt']
        ]);
    }

    public static function entityToModel(Space $space): SpaceModel
    {
        return new SpaceModel([
            'id' => $space->getId(),
            'user_id' => $space->getUserId(),
            'name' => $space->getName(),
            'description' => $space->getDescription(),
            'created_at' => $space->getCreatedAt(),
            'updated_at' => $space->getUpdatedAt()
        ]);
    }

    public static function modelToEntity(SpaceModel $space): Space
    {
        return new Space(
            id: $space->id,
            userId: $space->user_id,
            name: $space->name,
            description: $space->description,
            createdAt: $space->created_at,
            updatedAt: $space->updated_at
        );
    }
}
