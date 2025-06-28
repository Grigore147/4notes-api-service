<?php

declare(strict_types=1);

namespace App\Domains\Auth;

use App\Domains\Auth\Domain\Services\AuthService;

use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Contracts\Auth\Authenticatable;

/**
 * User provider for SSO authentication
 */
final class AuthServiceUserProvider implements UserProvider
{
    public function __construct(
        /**
         * Provider configuration
         */
        protected array $config,
        /**
         * Auth service that will provide the user data
         */
        protected AuthService $authService
    ) {}

    public function retrieveById($identifier): ?Authenticatable
    {
        return null;
    }

    public function retrieveByToken($identifier, $token): ?Authenticatable
    {
        return null;
    }

    public function updateRememberToken(Authenticatable $user, $token): void
    {
        // NOT NEEDED FOR SSO AUTHENTICATION
    }

    public function retrieveByCredentials(array $credentials): ?Authenticatable
    {
        return $this->authService->getUserByToken(request()->bearerToken());
    }

    public function validateCredentials(Authenticatable $user, array $credentials): bool
    {
        return false;
    }

    public function rehashPasswordIfRequired(Authenticatable $user, array $credentials, bool $force = false): void
    {
        // NOT NEEDED FOR SSO AUTHENTICATION
    }
}
