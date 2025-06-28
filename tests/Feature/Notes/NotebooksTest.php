<?php

use Carbon\Carbon;
use Tests\FeatureTestCase;
use Illuminate\Testing\TestResponse;
use App\Domains\Auth\Infrastructure\Models\User;
use App\Domains\Note\Infrastructure\Database\Eloquent\Models\Space;
use App\Domains\Note\Infrastructure\Database\Eloquent\Models\Stack;
use App\Domains\Note\Infrastructure\Database\Eloquent\Models\Notebook;

describe('Notes: API - Notebooks', function () {
    it('doesn\'t have any notebook at start', function () {
        /** @var FeatureTestCase $this */

        $this->auth();

        /** @var TestResponse $response */
        $response = $this->getJson('api/notebooks');

        $this->expectOk($response);

        $response->assertJson([
            'meta' => [
                'type' => 'NotebookCollection',
                'pagination' => [
                    'total' => 0
                ]
            ],
            'data' => []
        ]);
    });

    it('lists all notebooks', function () {
        /** @var FeatureTestCase $this */

        /** @var User $user */
        $user = $this->auth();

        $notebook = Notebook::factory()->create(['user_id' => $user->id]);

        /** @var TestResponse $response */
        $response = $this->getJson('api/notebooks');

        $this->expectOk($response);
        $this->assertCount(1, $response->json('data'));

        $response->assertJson([
            'meta' => [
                'type' => 'NotebookCollection',
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
                    'id' => $notebook->id,
                    'userId' => $notebook->user_id,
                    'spaceId' => $notebook->space_id,
                    'stackId' => $notebook->stack_id,
                    'name' => $notebook->name
                ]
            ]
        ]);
    });

    it('gets a notebook by id', function () {
        /** @var FeatureTestCase $this */

        /** @var User $user */
        $user = $this->auth();

        $notebook = Notebook::factory()->create(['user_id' => $user->id]);

        /** @var TestResponse $response */
        $response = $this->getJson('api/notebooks/'.$notebook->id);

        $this->expectOk($response);

        $response->assertJson([
            'meta' => [
                'type' => 'NotebookResource'
            ],
            'data' => [
                'userId' => $user->id,
                'spaceId' => $notebook->space_id,
                'stackId' => $notebook->stack_id,
                'name' => $notebook->name,
                'createdAt' => $notebook->created_at->toIsoString(),
                'updatedAt' => $notebook->updated_at->toIsoString()
            ]
        ]);
    });

    it('cannot get a foreign notebook', function () {
        /** @var FeatureTestCase $this */

        $this->auth();

        $notebook = Notebook::factory()->create();

        /** @var TestResponse $response */
        $response = $this->getJson('api/notebooks/'.$notebook->id);

        $this->expectNotFound($response);
    });

    it('creates a new notebook', function () {
        /** @var FeatureTestCase $this */

        /** @var User $user */
        $user = $this->auth();

        $space = Space::factory()->create(['user_id' => $user->id]);
        $stack = Stack::factory()->create([
            'user_id' => $user->id,
            'space_id' => $space->id
        ]);

        $data = [
            'spaceId' => $space->id,
            'stackId' => $stack->id,
            'name' => 'Notebook Name'
        ];

        /** @var TestResponse $response */
        $response = $this->postJson('api/notebooks', $data);

        $this->expectCreated($response);

        $response->assertJson([
            'meta' => [
                'type' => 'NotebookResource'
            ],
            'data' => [
                'userId' => $user->id,
                'spaceId' => $data['spaceId'],
                'stackId' => $data['stackId'],
                'name' => $data['name'],
                'createdAt' => Carbon::createFromTimeString($response->json('data.createdAt'))->toIsoString(),
                'updatedAt' => Carbon::createFromTimeString($response->json('data.updatedAt'))->toIsoString()
            ]
        ]);
    });

    it('cannot create a notebook within a foreign stack or space', function () {
        /** @var FeatureTestCase $this */

        $this->auth();

        $space = Space::factory()->create();
        $stack = Stack::factory()->create([
            'space_id' => $space->id
        ]);

        $data = [
            'spaceId' => $space->id,
            'stackId' => $stack->id,
            'name' => 'Notebook Name'
        ];

        /** @var TestResponse $response */
        $response = $this->postJson('api/notebooks', $data);

        $this->expectUnprocessableEntity($response);

        $response->assertJsonValidationErrors([
            'spaceId' => 'Resource not found',
            'stackId' => 'Resource not found'
        ]);
    });

    it('updates a notebook', function () {
        /** @var FeatureTestCase $this */

        /** @var User $user */
        $user = $this->auth();

        $notebook = Notebook::factory()->create([
            'user_id' => $user->id,
            'name' => 'Notebook Name'
        ]);
        $data = [
            'name' => 'Updated Notebook Name'
        ];
        
        /** @var TestResponse $response */
        $response = $this->putJson('api/notebooks/'.$notebook->id, $data);

        $this->expectOk($response);

        $response->assertJson([
            'meta' => [
                'type' => 'NotebookResource'
            ],
            'data' => [
                'id' => $notebook->id,
                'userId' => $notebook->user_id,
                'spaceId' => $notebook->space_id,
                'stackId' => $notebook->stack_id,
                'name' => $data['name']
            ]
        ]);
    });

    it('cannot update a foreign notebook', function () {
        /** @var FeatureTestCase $this */

        $this->auth();

        $notebook = Notebook::factory()->create();

        $data = [
            'name' => 'Updated Notebook Name'
        ];

        /** @var TestResponse $response */
        $response = $this->putJson('api/notebooks/'.$notebook->id, $data);

        $this->expectNotFound($response);
    });

    it('deletes a notebook', function () {
        /** @var FeatureTestCase $this */

        /** @var User $user */
        $user = $this->auth();

        $notebook = Notebook::factory()->create(['user_id' => $user->id]);

        /** @var TestResponse $response */
        $response = $this->deleteJson('api/notebooks/'.$notebook->id);

        $this->expectNoContent($response);

        /** @var TestResponse $response */
        $response = $this->getJson('api/notebooks/'.$notebook->id);

        $this->expectNotFound($response);
    });

    it('cannot delete a foreign notebook', function () {
        /** @var FeatureTestCase $this */

        $this->auth();

        $notebook = Notebook::factory()->create();

        /** @var TestResponse $response */
        $response = $this->deleteJson('api/notebooks/'.$notebook->id);

        $this->expectNotFound($response);
    });
});
