<?php

use Carbon\Carbon;
use Tests\FeatureTestCase;
use Illuminate\Testing\TestResponse;
use App\Domains\Auth\Infrastructure\Models\User;
use App\Domains\Note\Infrastructure\Database\Eloquent\Models\Space;
use App\Domains\Note\Infrastructure\Database\Eloquent\Models\Stack;

describe('Notes: API - Stacks', function () {
    it('doesn\'t have any stack at start', function () {
        /** @var FeatureTestCase $this */

        $this->auth();

        /** @var TestResponse $response */
        $response = $this->getJson('api/stacks');

        $this->expectOk($response);

        $response->assertJson([
            'meta' => [
                'type' => 'StackCollection',
                'pagination' => [
                    'total' => 0
                ]
            ],
            'data' => []
        ]);
    });

    it('lists all stacks', function () {
        /** @var FeatureTestCase $this */

        /** @var User $user */
        $user = $this->auth();

        $stack = Stack::factory()->create(['user_id' => $user->id]);

        /** @var TestResponse $response */
        $response = $this->getJson('api/stacks');

        $this->expectOk($response);
        $this->assertCount(1, $response->json('data'));

        $response->assertJson([
            'meta' => [
                'type' => 'StackCollection',
                'pagination' => [
                    'from' => 1,
                    'to' => 1,
                    'total' => 1,
                    'perPage' => 25,
                    'currentPage' => 1,
                    'lastPage' => 1
                ]
            ],
            'data' => [
                [
                    'id' => $stack->id,
                    'userId' => $stack->user_id,
                    'spaceId' => $stack->space_id,
                    'name' => $stack->name
                ]
            ]
        ]);
    });

    it('gets a stack by id', function () {
        /** @var FeatureTestCase $this */

        /** @var User $user */
        $user = $this->auth();

        $stack = Stack::factory()->create(['user_id' => $user->id]);

        /** @var TestResponse $response */
        $response = $this->getJson('api/stacks/'.$stack->id);

        $this->expectOk($response);

        $response->assertJson([
            'meta' => [
                'type' => 'StackResource'
            ],
            'data' => [
                'userId' => $user->id,
                'spaceId' => $stack->space_id,
                'name' => $stack->name,
                'createdAt' => $stack->created_at->toIsoString(),
                'updatedAt' => $stack->updated_at->toIsoString()
            ]
        ]);
    });

    it('cannot get a foreign stack', function () {
        /** @var FeatureTestCase $this */

        $this->auth();

        $stack = Stack::factory()->create();

        /** @var TestResponse $response */
        $response = $this->getJson('api/stacks/'.$stack->id);

        $this->expectNotFound($response);
    });

    it('creates a new stack', function () {
        /** @var FeatureTestCase $this */

        /** @var User $user */
        $user = $this->auth();

        $space = Space::factory()->create(['user_id' => $user->id]);

        $data = [
            'spaceId' => $space->id,
            'name' => 'Stack Name'
        ];

        /** @var TestResponse $response */
        $response = $this->postJson('api/stacks', $data);

        $this->expectCreated($response);

        $response->assertJson([
            'meta' => [
                'type' => 'StackResource'
            ],
            'data' => [
                'userId' => $user->id,
                'spaceId' => $data['spaceId'],
                'name' => $data['name'],
                'createdAt' => Carbon::createFromTimeString($response->json('data.createdAt'))->toIsoString(),
                'updatedAt' => Carbon::createFromTimeString($response->json('data.updatedAt'))->toIsoString()
            ]
        ]);
    });

    it('cannot create a stack in a foreign space', function () {
        /** @var FeatureTestCase $this */

        $this->auth();

        $space = Space::factory()->create();

        $data = [
            'spaceId' => $space->id,
            'name' => 'Stack Name'
        ];

        /** @var TestResponse $response */
        $response = $this->postJson('api/stacks', $data);

        $this->expectUnprocessableEntity($response);
    });

    it('updates a stack', function () {
        /** @var FeatureTestCase $this */

        /** @var User $user */
        $user = $this->auth();

        $stack = Stack::factory()->create([
            'user_id' => $user->id,
            'name' => 'Stack Name'
        ]);
        $data = [
            'name' => 'Updated Stack Name'
        ];
        
        /** @var TestResponse $response */
        $response = $this->putJson('api/stacks/'.$stack->id, $data);

        $this->expectOk($response);

        $response->assertJson([
            'meta' => [
                'type' => 'StackResource'
            ],
            'data' => [
                'id' => $stack->id,
                'userId' => $stack->user_id,
                'spaceId' => $stack->space_id,
                'name' => $data['name']
            ]
        ]);
    });

    it('cannot update a foreign stack', function () {
        /** @var FeatureTestCase $this */

        $this->auth();

        $stack = Stack::factory()->create();

        $data = [
            'name' => 'Updated Stack Name'
        ];

        /** @var TestResponse $response */
        $response = $this->putJson('api/stacks/'.$stack->id, $data);

        $this->expectNotFound($response);
    });

    it('deletes a stack', function () {
        /** @var FeatureTestCase $this */

        /** @var User $user */
        $user = $this->auth();

        $stack = Stack::factory()->create(['user_id' => $user->id]);

        /** @var TestResponse $response */
        $response = $this->deleteJson('api/stacks/'.$stack->id);

        $this->expectNoContent($response);

        /** @var TestResponse $response */
        $response = $this->getJson('api/stacks/'.$stack->id);

        $this->expectNotFound($response);
    });

    it('cannot delete a foreign stack', function () {
        /** @var FeatureTestCase $this */

        $this->auth();

        $stack = Stack::factory()->create();

        /** @var TestResponse $response */
        $response = $this->deleteJson('api/stacks/'.$stack->id);

        $this->expectNotFound($response);
    });
});
