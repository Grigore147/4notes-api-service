<?php

use Tests\FeatureTestCase;
use App\Domains\Auth\Infrastructure\Models\User;
use App\Domains\Note\Domain\Entities\Stack as StackEntity;
use App\Domains\Note\Domain\Repositories\StacksRepository;
use App\Domains\Note\Infrastructure\Database\Eloquent\Models\Space;
use App\Domains\Note\Infrastructure\Database\Eloquent\Models\Stack;

describe('Notes: Stacks Repository', function () {
    it('creates a new stack', function () {
        /** @var FeatureTestCase $this */

        $user = User::factory()->create();
        $space = Space::factory()->create(['user_id' => $user->id]);
        $data = [
            'userId' => $user->id,
            'spaceId' => $space->id,
            'name' => 'Stack Name'
        ];

        $stackRepository = app(StacksRepository::class);

        $stack = $stackRepository->create(StackEntity::fromArray($data));

        expect($stack)->toBeValidStack($data);

        $this->assertDatabaseHas('stacks', [
            'id' => $stack->getId(),
            'user_id' => $user->id,
            'space_id' => $space->id,
            'name' => $data['name']
        ]);
    });

    it('updates a stack', function () {
        /** @var FeatureTestCase $this */

        $user = User::factory()->create();
        $space = Space::factory()->create(['user_id' => $user->id]);
        $data = [
            'userId' => $user->id,
            'spaceId' => $space->id,
            'name' => 'Stack Name'
        ];
        $updatedData = [
            'name' => 'Updated Stack Name'
        ];

        $stackRepository = app(StacksRepository::class);

        $stack = $stackRepository->create(StackEntity::fromArray($data));

        $updatedStack = $stackRepository->update($stack, $updatedData);

        expect($updatedStack)->toBeValidStack([
            ...$data,
            ...$updatedData
        ]);
        expect($updatedStack->getUserId())->toEqual($user->id);
        expect($updatedStack->getSpaceId())->toEqual($space->id);
        expect($updatedStack->getName())->toBe($updatedData['name']);

        $this->assertDatabaseHas('stacks', [
            'id' => $stack->getId(),
            'user_id' => $user->id,
            'space_id' => $space->id,
            'name' => $updatedData['name']
        ]);
    });

    it('updates a stack from entity', function () {
        /** @var FeatureTestCase $this */

        $user = User::factory()->create();
        $space = Space::factory()->create(['user_id' => $user->id]);
        $data = [
            'userId' => $user->id,
            'spaceId' => $space->id,
            'name' => 'Stack Name'
        ];
        $updateData = [
            'name' => 'Updated Stack Name'
        ];

        $stackRepository = app(StacksRepository::class);

        /** @var StackEntity $stack */
        $stack = $stackRepository->create(StackEntity::fromArray($data));

        $stack->set(
            name: $updateData['name']
        );

        $updatedStack = $stackRepository->update($stack);

        expect($updatedStack)->toBeValidStack([
            ...$data,
            ...$updateData
        ]);
        expect($updatedStack->getUserId())->toEqual($user->id);
        expect($updatedStack->getSpaceId())->toEqual($space->id);
        expect($updatedStack->getName())->toBe($updateData['name']);

        $this->assertDatabaseHas('stacks', [
            'id' => $stack->getId(),
            'user_id' => $user->id,
            'space_id' => $space->id,
            'name' => $updateData['name']
        ]);
    });

    it('deletes a stack', function () {
        /** @var FeatureTestCase $this */

        $user = User::factory()->create();
        $space = Space::factory()->create(['user_id' => $user->id]);
        $stack = Stack::factory()->create([
            'user_id' => $user->id,
            'space_id' => $space->id
        ]);

        $stackRepository = app(StacksRepository::class);

        $stackDeleted = $stackRepository->delete($stack->id);

        expect($stackDeleted)->toBeTrue();

        $this->assertDatabaseMissing('stacks', [
            'id' => $stack->id
        ]);
    });

    it('finds a stack by ID', function () {
        /** @var FeatureTestCase $this */

        $user = User::factory()->create();
        $space = Space::factory()->create(['user_id' => $user->id]);
        $data = [
            'userId' => $user->id,
            'spaceId' => $space->id,
            'name' => 'Stack Name'
        ];

        $stackRepository = app(StacksRepository::class);

        $stack = $stackRepository->create(StackEntity::fromArray($data));

        $foundStack = $stackRepository->findById($stack->getId());

        expect($stack)->toMatchArray($foundStack->toArray());
    });

    it('gets all stacks', function () {
        /** @var FeatureTestCase $this */

        $user = User::factory()->create();
        $space = Space::factory()->create(['user_id' => $user->id]);

        Stack::factory()->count(3)->create([
            'user_id' => $user->id,
            'space_id' => $space->id
        ]);

        $stackRepository = app(StacksRepository::class);

        $stacks = $stackRepository->all();

        expect($stacks)->toHaveCount(3);
    });

    it('gets all stacks by user ID', function () {
        /** @var FeatureTestCase $this */

        $user = User::factory()->create();
        $user2 = User::factory()->create();
        $space = Space::factory()->create(['user_id' => $user->id]);

        Stack::factory()->count(3)->create([
            'user_id' => $user->id,
            'space_id' => $space->id
        ]);
        Stack::factory()->count(2)->create([
            'user_id' => $user2->id,
            'space_id' => $space->id
        ]);

        $stackRepository = app(StacksRepository::class);

        $stacks = $stackRepository->find(['userId' => $user->id]);

        expect($stacks)->toHaveCount(3);
    });
});
