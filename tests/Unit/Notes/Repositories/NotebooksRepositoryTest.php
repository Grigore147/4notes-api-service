<?php

use Tests\FeatureTestCase;
use App\Domains\Auth\Infrastructure\Models\User;
use App\Domains\Note\Domain\Repositories\NotebooksRepository;
use App\Domains\Note\Domain\Entities\Notebook as NotebookEntity;
use App\Domains\Note\Infrastructure\Database\Eloquent\Models\Stack;
use App\Domains\Note\Infrastructure\Database\Eloquent\Models\Notebook;

describe('Notes: Notebooks Repository', function () {
    it('creates a new notebook', function () {
        /** @var FeatureTestCase $this */

        $user = User::factory()->create();
        $stack = Stack::factory()->create(['user_id' => $user->id]);
        $data = [
            'userId' => $user->id,
            'spaceId' => $stack->space_id,
            'stackId' => $stack->id,
            'name' => 'Notebook Name'
        ];

        $notebookRepository = app(NotebooksRepository::class);

        $notebook = $notebookRepository->create(NotebookEntity::fromArray($data));

        expect($notebook)->toBeValidNotebook($data);

        $this->assertDatabaseHas('notebooks', [
            'id' => $notebook->getId(),
            'user_id' => $user->id,
            'space_id' => $stack->space_id,
            'stack_id' => $stack->id,
            'name' => $data['name']
        ]);
    });

    it('updates a notebook', function () {
        /** @var FeatureTestCase $this */

        $user = User::factory()->create();
        $stack = Stack::factory()->create(['user_id' => $user->id]);
        $data = [
            'userId' => $user->id,
            'spaceId' => $stack->space_id,
            'stackId' => $stack->id,
            'name' => 'Notebook Name'
        ];
        $updatedData = [
            'name' => 'Updated Notebook Name'
        ];

        $notebookRepository = app(NotebooksRepository::class);

        $notebook = $notebookRepository->create(NotebookEntity::fromArray($data));

        $updatedNotebook = $notebookRepository->update($notebook, $updatedData);

        expect($updatedNotebook)->toBeValidNotebook([
            ...$data,
            ...$updatedData
        ]);
        expect($updatedNotebook->getUserId())->toEqual($user->id);
        expect($updatedNotebook->getName())->toBe($updatedData['name']);

        $this->assertDatabaseHas('notebooks', [
            'id' => $notebook->getId(),
            'user_id' => $user->id,
            'space_id' => $stack->space_id,
            'stack_id' => $stack->id,
            'name' => $updatedData['name']
        ]);
    });

    it('updates a notebook from entity', function () {
        /** @var FeatureTestCase $this */

        $user = User::factory()->create();
        $stack = Stack::factory()->create(['user_id' => $user->id]);
        $data = [
            'userId' => $user->id,
            'spaceId' => $stack->space_id,
            'stackId' => $stack->id,
            'name' => 'Notebook Name'
        ];
        $updateData = [
            'name' => 'Updated Notebook Name'
        ];

        $notebookRepository = app(NotebooksRepository::class);

        /** @var NotebookEntity $notebook */
        $notebook = $notebookRepository->create(NotebookEntity::fromArray($data));

        $notebook->set(
            name: $updateData['name']
        );

        $updatedNotebook = $notebookRepository->update($notebook);

        expect($updatedNotebook)->toBeValidNotebook([
            ...$data,
            ...$updateData
        ]);
        expect($updatedNotebook->getUserId())->toEqual($user->id);
        expect($updatedNotebook->getName())->toBe($updateData['name']);

        $this->assertDatabaseHas('notebooks', [
            'id' => $notebook->getId(),
            'user_id' => $user->id,
            'space_id' => $stack->space_id,
            'stack_id' => $stack->id,
            'name' => $updateData['name']
        ]);
    });

    it('deletes a notebook', function () {
        /** @var FeatureTestCase $this */

        $user = User::factory()->create();
        $notebook = Notebook::factory()->create(['user_id' => $user->id]);

        $notebookRepository = app(NotebooksRepository::class);

        $notebookDeleted = $notebookRepository->delete($notebook->id);

        expect($notebookDeleted)->toBeTrue();

        $this->assertDatabaseMissing('notebooks', [
            'id' => $notebook->id
        ]);
    });

    it('finds a notebook by ID', function () {
        /** @var FeatureTestCase $this */

        $user = User::factory()->create();
        $stack = Stack::factory()->create(['user_id' => $user->id]);
        $data = [
            'userId' => $user->id,
            'spaceId' => $stack->space_id,
            'stackId' => $stack->id,
            'name' => 'Notebook Name'
        ];

        $notebookRepository = app(NotebooksRepository::class);

        $notebook = $notebookRepository->create(NotebookEntity::fromArray($data));

        $foundNotebook = $notebookRepository->findById($notebook->getId());

        expect($notebook)->toMatchArray($foundNotebook->toArray());
    });

    it('gets all notebooks', function () {
        /** @var FeatureTestCase $this */

        $user = User::factory()->create();

        Notebook::factory()->count(3)->create(['user_id' => $user->id]);

        $notebookRepository = app(NotebooksRepository::class);

        $notebooks = $notebookRepository->all();

        expect($notebooks)->toHaveCount(3);
    });

    it('gets all notebooks by user ID', function () {
        /** @var FeatureTestCase $this */

        $user = User::factory()->create();
        $user2 = User::factory()->create();

        Notebook::factory()->count(3)->create(['user_id' => $user->id]);
        Notebook::factory()->count(2)->create(['user_id' => $user2->id]);

        $notebookRepository = app(NotebooksRepository::class);

        $notebooks = $notebookRepository->find(['userId' => $user->id]);

        expect($notebooks)->toHaveCount(3);
    });
});
