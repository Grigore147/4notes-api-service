<?php

use Tests\FeatureTestCase;
use App\Domains\Auth\Infrastructure\Models\User;
use App\Domains\Note\Domain\Entities\Space as SpaceEntity;
use App\Domains\Note\Domain\Repositories\SpacesRepository;
use App\Domains\Note\Infrastructure\Database\Eloquent\Models\Space;

describe('Notes: Spaces Repository', function () {
    it('creates a new space', function () {
        /** @var FeatureTestCase $this */

        $user = User::factory()->create();
        $data = [
            'userId' => $user->id,
            'name' => 'Space Name',
            'description' => 'Space Description'
        ];

        $spaceRepository = app(SpacesRepository::class);

        $space = $spaceRepository->create(SpaceEntity::fromArray($data));

        expect($space)->toBeValidSpace($data);

        $this->assertDatabaseHas('spaces', [
            'id' => $space->getId(),
            'user_id' => $user->id,
            'name' => $data['name'],
            'description' => $data['description']
        ]);
    });

    it('updates a space', function () {
        /** @var FeatureTestCase $this */

        $user = User::factory()->create();
        $data = [
            'userId' => $user->id,
            'name' => 'Space Name',
            'description' => 'Space Description'
        ];
        $updatedData = [
            'name' => 'Updated Space Name',
            'description' => 'Updated Space Description'
        ];

        $spaceRepository = app(SpacesRepository::class);

        $space = $spaceRepository->create(SpaceEntity::fromArray($data));

        $updatedSpace = $spaceRepository->update($space, $updatedData);

        expect($updatedSpace)->toBeValidSpace([
            ...$data,
            ...$updatedData
        ]);
        expect($updatedSpace->getUserId())->toEqual($user->id);
        expect($updatedSpace->getName())->toBe($updatedData['name']);
        expect($updatedSpace->getDescription())->toBe($updatedData['description']);

        $this->assertDatabaseHas('spaces', [
            'id' => $space->getId(),
            'user_id' => $user->id,
            'name' => $updatedData['name'],
            'description' => $updatedData['description']
        ]);
    });

    it('updates a space from entity', function () {
        /** @var FeatureTestCase $this */

        $user = User::factory()->create();
        $data = [
            'userId' => $user->id,
            'name' => 'Space Name',
            'description' => 'Space Description'
        ];
        $updateData = [
            'name' => 'Updated Space Name',
            'description' => 'Updated Space Description'
        ];

        $spaceRepository = app(SpacesRepository::class);

        /** @var SpaceEntity $space */
        $space = $spaceRepository->create(SpaceEntity::fromArray($data));

        $space->set(
            name: $updateData['name'],
            description: $updateData['description']
        );

        $updatedSpace = $spaceRepository->update($space);

        expect($updatedSpace)->toBeValidSpace([
            ...$data,
            ...$updateData
        ]);
        expect($updatedSpace->getUserId())->toEqual($user->id);
        expect($updatedSpace->getName())->toBe($updateData['name']);
        expect($updatedSpace->getDescription())->toBe($updateData['description']);

        $this->assertDatabaseHas('spaces', [
            'id' => $space->getId(),
            'user_id' => $user->id,
            'name' => $updateData['name'],
            'description' => $updateData['description']
        ]);
    });

    it('deletes a space', function () {
        /** @var FeatureTestCase $this */

        $user = User::factory()->create();
        $space = Space::factory()->create(['user_id' => $user->id]);

        $spaceRepository = app(SpacesRepository::class);

        $spaceDeleted = $spaceRepository->delete($space->id);

        expect($spaceDeleted)->toBeTrue();

        $this->assertDatabaseMissing('spaces', [
            'id' => $space->id
        ]);
    });

    it('finds a space by ID', function () {
        /** @var FeatureTestCase $this */

        $user = User::factory()->create();
        $data = [
            'userId' => $user->id,
            'name' => 'Space Name',
            'description' => 'Space Description'
        ];

        $spaceRepository = app(SpacesRepository::class);

        $space = $spaceRepository->create(SpaceEntity::fromArray($data));

        $foundSpace = $spaceRepository->findById($space->getId());

        expect($space)->toMatchArray($foundSpace->toArray());
    });

    it('gets all spaces', function () {
        /** @var FeatureTestCase $this */

        $user = User::factory()->create();

        Space::factory()->count(3)->create(['user_id' => $user->id]);

        $spaceRepository = app(SpacesRepository::class);

        $spaces = $spaceRepository->all();

        expect($spaces)->toHaveCount(3);
    });

    it('gets all spaces by user ID', function () {
        /** @var FeatureTestCase $this */

        $user = User::factory()->create();
        $user2 = User::factory()->create();

        Space::factory()->count(3)->create(['user_id' => $user->id]);
        Space::factory()->count(2)->create(['user_id' => $user2->id]);

        $spaceRepository = app(SpacesRepository::class);

        $spaces = $spaceRepository->find(['userId' => $user->id]);

        expect($spaces)->toHaveCount(3);
    });
});
