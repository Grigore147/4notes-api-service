<?php

namespace Tests\Traits;

use Illuminate\Contracts\Auth\Authenticatable;
use App\Domains\Auth\Infrastructure\Models\User;

trait Authenticates
{
    /**
     * Set the currently logged in user for the application.
     *
     * @param  Authenticatable|array  $user
     * @param  string|null  $guard
     * @return $this
     */
    public function auth(User|array $user = [], ?string $guard = 'api')
    {
        if (is_array($user)) {
            $user = User::factory()->create($user);
        }

        $this->actingAs($user, $guard);
        
        return $user;
    }

    /**
     * Get the currently logged in user for the application.
     *
     * @param  string|null  $guard
     * @return mixed
     */
    public function user($guard = null)
    {
        return $this->app->make('auth')->guard($guard)->user();
    }
}
