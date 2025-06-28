<?php

use Tests\FeatureTestCase;
use Illuminate\Support\Facades\Event;
use App\Domains\Auth\Infrastructure\Models\User;
use App\Domains\Note\Domain\Events\Stack\StackCreated;
use App\Domains\Note\Domain\Events\Stack\StackDeleted;
use App\Domains\Note\Domain\Events\Stack\StackUpdated;
use App\Domains\Note\Application\Services\StackService;
use App\Domains\Note\Domain\Entities\Stack as StackEntity;
use App\Domains\Note\Infrastructure\Database\Eloquent\Models\Space;
use App\Domains\Note\Infrastructure\Database\Eloquent\Models\Stack;

describe('Notes: Stacks Service', function () {
    it('creates a new stack', function () {
        /** @var FeatureTestCase $this */

        Event::fake();

        $user = User::factory()->create();
        $space = Space::factory()->create(['user_id' => $user->id]);
        $data = [
            'userId' => $user->id,
            'spaceId' => $space->id,
            'name' => 'Stack Name'
        ];

        $stackService = app(StackService::class);

        $stack = $stackService->create(StackEntity::fromArray($data));

        expect($stack)->toBeValidStack($data);

        Event::assertDispatchedTimes(StackCreated::class, 1);
        Event::assertDispatched(StackCreated::class, function ($event) use ($stack, $data) {
            return expect($event->stack)->toMatchArray([
                'id' => $stack->getId(),
                ...$data
            ]);
        });
    });

    it('updates a stack', function () {
        /** @var FeatureTestCase $this */

        Event::fake();

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

        $stackService = app(StackService::class);

        $stack = $stackService->create(StackEntity::fromArray($data));

        $updatedStack = $stackService->update($stack, $updatedData);

        expect($updatedStack)->toBeValidStack([
            ...$data,
            ...$updatedData
        ]);

        Event::assertDispatchedTimes(StackUpdated::class, 1);
        Event::assertDispatched(StackUpdated::class, function ($event) use ($stack, $data, $updatedData) {
            return expect($event->stack)->toMatchArray([
                'id' => $stack->getId(),
                ...$data,
                ...$updatedData
            ]);
        });
    });

    it('deletes a stack', function () {
        Event::fake();

        $stack = Stack::factory()->create();

        $stackService = app(StackService::class);

        $stackDeleted = $stackService->delete($stack->id);

        expect($stackDeleted)->toBeTrue();

        Event::assertDispatchedTimes(StackDeleted::class, 1);
        Event::assertDispatched(StackDeleted::class, function ($event) use ($stack) {
            return (string)$event->stack->getId() === (string)$stack->id;
        });
    });
});
