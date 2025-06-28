<?php

namespace Tests\Traits;

use Illuminate\Testing\TestResponse;
use Symfony\Component\HttpFoundation\Response;
use App\Core\Support\Enums\ResponseStatus;

trait AssertsResponses
{
    /**
     * Test that the response contains an OK status.
     *
     * @param  TestResponse  $response
     */
    public function expectOk(TestResponse $response)
    {
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson([
            'status' => ResponseStatus::SUCCESS->value,
            'code' => Response::HTTP_OK
        ]);
    }

    /**
     * Test that the response contains a Created status.
     *
     * @param  TestResponse  $response
     */
    public function expectCreated(TestResponse $response)
    {
        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJson([
            'status' => ResponseStatus::SUCCESS->value,
            'code' => Response::HTTP_CREATED
        ]);
    }

    /**
     * Test that the response contains an Accepted status.
     *
     * @param  TestResponse  $response
     */
    public function expectAccepted(TestResponse $response)
    {
        $response->assertStatus(Response::HTTP_ACCEPTED);
        $response->assertJson([
            'status' => ResponseStatus::SUCCESS->value,
            'code' => Response::HTTP_ACCEPTED
        ]);
    }

    /**
     * Test that the response contains a No Content status.
     *
     * @param  TestResponse  $response
     */
    public function expectNoContent(TestResponse $response)
    {
        $response->assertStatus(Response::HTTP_NO_CONTENT);
    }

    /**
     * Test that the response contains a No Content status with JSON.
     *
     * @param  TestResponse  $response
     */
    public function expectNoContentWithJson(TestResponse $response)
    {
        $response->assertStatus(Response::HTTP_NO_CONTENT);
        $response->assertJson([
            'status' => ResponseStatus::SUCCESS->value,
            'code' => Response::HTTP_NO_CONTENT
        ]);
    }

    /**
     * Test that the response contains a Permanent Redirect status.
     *
     * @param  TestResponse  $response
     */
    public function expectPermanentRedirect(TestResponse $response)
    {
        $response->assertStatus(Response::HTTP_MOVED_PERMANENTLY);
    }

    /**
     * Test that the response contains a Redirect status.
     *
     * @param  TestResponse  $response
     */
    public function expectRedirect(TestResponse $response)
    {
        $response->assertStatus(Response::HTTP_FOUND);
    }

    /**
     * Test that the response contains a Redirect status to a specific URL.
     *
     * @param  TestResponse  $response
     */
    public function expectRedirectTo(string $url, TestResponse $response)
    {
        $response->assertRedirect($url);
    }

    /**
     * Test that the response contains a Bad Request status.
     *
     * @param  TestResponse  $response
     */
    public function expectBadRequest(TestResponse $response)
    {
        $response->assertStatus(Response::HTTP_BAD_REQUEST);
        $response->assertJson([
            'status' => ResponseStatus::ERROR->value,
            'code' => Response::HTTP_BAD_REQUEST
        ]);
    }

    /**
     * Test that the response contains an Unauthorized status.
     *
     * @param  TestResponse  $response
     */
    public function expectUnauthorized(TestResponse $response)
    {
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
        $response->assertJson([
            'status' => ResponseStatus::ERROR->value,
            'code' => Response::HTTP_UNAUTHORIZED
        ]);
    }

    /**
     * Test that the response contains a Forbidden status.
     *
     * @param  TestResponse  $response
     */
    public function expectForbidden(TestResponse $response)
    {
        $response->assertStatus(Response::HTTP_FORBIDDEN);
        $response->assertJson([
            'status' => ResponseStatus::ERROR->value,
            'code' => Response::HTTP_FORBIDDEN
        ]);
    }

    /**
     * Test that the response contains a Not Found status.
     *
     * @param  TestResponse  $response
     */
    public function expectNotFound(TestResponse $response)
    {
        $response->assertStatus(Response::HTTP_NOT_FOUND);
        $response->assertJson([
            'status' => ResponseStatus::ERROR->value,
            'code' => Response::HTTP_NOT_FOUND
        ]);
    }

    /**
     * Test that the response contains a Method Not Allowed status.
     *
     * @param  TestResponse  $response
     */
    public function expectMethodNotAllowed(TestResponse $response)
    {
        $response->assertStatus(Response::HTTP_METHOD_NOT_ALLOWED);
        $response->assertJson([
            'status' => ResponseStatus::ERROR->value,
            'code' => Response::HTTP_METHOD_NOT_ALLOWED
        ]);
    }

    /**
     * Test that the response contains a Conflict status.
     *
     * @param  TestResponse  $response
     */
    public function expectConflict(TestResponse $response)
    {
        $response->assertStatus(Response::HTTP_CONFLICT);
        $response->assertJson([
            'status' => ResponseStatus::ERROR->value,
            'code' => Response::HTTP_CONFLICT
        ]);
    }

    /**
     * Test that the response contains an Unprocessable Entity status.
     *
     * @param  TestResponse  $response
     */
    public function expectUnprocessableEntity(TestResponse $response)
    {
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJson([
            'status' => ResponseStatus::ERROR->value,
            'code' => Response::HTTP_UNPROCESSABLE_ENTITY
        ]);
    }

    /**
     * Test that the response contains an Internal Server Error status.
     *
     * @param  TestResponse  $response
     */
    public function expectInternalServerError(TestResponse $response)
    {
        $response->assertStatus(Response::HTTP_INTERNAL_SERVER_ERROR);
        $response->assertJson([
            'status' => ResponseStatus::ERROR->value,
            'code' => Response::HTTP_INTERNAL_SERVER_ERROR
        ]);
    }

    /**
     * Test that the response contains a Service Unavailable status.
     *
     * @param  TestResponse  $response
     */
    public function expectServiceUnavailable(TestResponse $response)
    {
        $response->assertStatus(Response::HTTP_SERVICE_UNAVAILABLE);
        $response->assertJson([
            'status' => ResponseStatus::ERROR->value,
            'code' => Response::HTTP_SERVICE_UNAVAILABLE
        ]);
    }
}
