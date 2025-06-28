<?php

use App\Domains\Note\Domain\Events\Notebook\NotebookDeleted;
use Illuminate\Support\Facades\Event;
use App\Domains\Auth\Infrastructure\Models\User;
use App\Domains\Note\Application\Services\NotebookService;
use App\Domains\Note\Domain\Entities\Notebook as NotebookEntity;
use App\Domains\Note\Domain\Events\Notebook\NotebookCreated;
use App\Domains\Note\Domain\Events\Notebook\NotebookUpdated;
use App\Domains\Note\Infrastructure\Database\Eloquent\Models\Stack;
use App\Domains\Note\Infrastructure\Database\Eloquent\Models\Notebook;

describe('Notes: Notebooks Service', function () {
    it('creates a new notebook', function () {
        Event::fake();

        $user = User::factory()->create();
        $stack = Stack::factory()->create(['user_id' => $user->id]);
        $data = [
            'userId' => $user->id,
            'spaceId' => $stack->space_id,
            'stackId' => $stack->id,
            'name' => 'Notebook Name'
        ];

        $notebookService = app(NotebookService::class);

        $notebook = $notebookService->create(NotebookEntity::fromArray($data));

        expect($notebook)->toBeValidNotebook($data);

        Event::assertDispatchedTimes(NotebookCreated::class, 1);
        Event::assertDispatched(NotebookCreated::class, function ($event) use ($notebook, $data) {
            return expect($event->notebook)->toMatchArray([
                'id' => $notebook->getId(),
                ...$data
            ]);
        });
    });

    it('updates a notebook', function () {
        Event::fake();

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

        $notebookService = app(NotebookService::class);

        $notebook = $notebookService->create(NotebookEntity::fromArray($data));

        $updatedNotebook = $notebookService->update($notebook, $updatedData);

        expect($updatedNotebook)->toBeValidNotebook([
            ...$data,
            ...$updatedData
        ]);

        Event::assertDispatchedTimes(NotebookUpdated::class, 1);
        Event::assertDispatched(NotebookUpdated::class, function ($event) use ($notebook, $data, $updatedData) {
            return expect($event->notebook)->toMatchArray([
                'id' => $notebook->getId(),
                ...$data,
                ...$updatedData
            ]);
        });
    });

    it('deletes a notebook', function () {
        Event::fake();

        $user = User::factory()->create();
        $notebook = Notebook::factory()->create(['user_id' => $user->id]);

        $notebookService = app(NotebookService::class);

        $notebookDeleted = $notebookService->delete($notebook->id);

        expect($notebookDeleted)->toBeTrue();

        Event::assertDispatchedTimes(NotebookDeleted::class, 1);
        Event::assertDispatched(NotebookDeleted::class, function ($event) use ($notebook) {
            return (string)$event->notebook->getId() === (string)$notebook->id;
        });
    });
});
