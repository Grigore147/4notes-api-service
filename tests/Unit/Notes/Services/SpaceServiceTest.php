<?php

use Illuminate\Support\Facades\Event;
use App\Domains\Auth\Infrastructure\Models\User;
use App\Domains\Note\Domain\Events\Space\SpaceCreated;
use App\Domains\Note\Domain\Events\Space\SpaceDeleted;
use App\Domains\Note\Domain\Events\Space\SpaceUpdated;
use App\Domains\Note\Application\Services\SpaceService;
use App\Domains\Note\Domain\Entities\Space as SpaceEntity;
use App\Domains\Note\Infrastructure\Database\Eloquent\Models\Space;

describe('Notes: Space Service', function () {
    it('creates a new space', function () {
        Event::fake();

        $user = User::factory()->create();
        $data = [
            'userId' => $user->id,
            'name' => 'Space Name',
            'description' => 'Space Description'
        ];

        $spaceService = app(SpaceService::class);

        $space = $spaceService->create(SpaceEntity::fromArray($data));

        expect($space)->toBeValidSpace($data);

        Event::assertDispatchedTimes(SpaceCreated::class, 1);
        Event::assertDispatched(SpaceCreated::class, function ($event) use ($space, $data) {
            return expect($event->space)->toMatchArray([
                'id' => $space->getId(),
                ...$data
            ]);
        });
    });

    it('updates a space', function () {
        Event::fake();

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

        $spaceService = app(SpaceService::class);

        $space = $spaceService->create(SpaceEntity::fromArray($data));

        $updatedSpace = $spaceService->update($space, $updatedData);

        expect($updatedSpace)->toBeValidSpace([
            'userId' => $user->id,
            ...$updatedData
        ]);

        Event::assertDispatchedTimes(SpaceUpdated::class, 1);
        Event::assertDispatched(SpaceUpdated::class, function ($event) use ($space, $data, $updatedData) {
            return expect($event->space)->toMatchArray([
                'id' => $space->getId(),
                ...$data,
                ...$updatedData
            ]);
        });
    });

    it('deletes a space', function () {
        Event::fake();

        $space = Space::factory()->create();

        $spaceService = app(SpaceService::class);

        $spaceDeleted = $spaceService->delete($space->id);

        expect($spaceDeleted)->toBeTrue();

        Event::assertDispatchedTimes(SpaceDeleted::class, 1);
        Event::assertDispatched(SpaceDeleted::class, function ($event) use ($space) {
            return (string)$event->space->getId() === (string)$space->id;
        });
    });
});
