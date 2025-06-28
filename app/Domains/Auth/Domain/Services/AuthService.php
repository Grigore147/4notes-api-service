<?php

declare(strict_types=1);

namespace App\Domains\Auth\Domain\Services;

use App\Domains\Auth\Infrastructure\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Contracts\Auth\Authenticatable;

final class AuthService
{
    /**
     * Auth service login URL
     */
    public string $loginUrl = 'https://auth.4notes.app/auth/login';

    /**
     * API endpoint
     */
    public string $apiUrl = 'https://auth.4notes.app/api';

    /**
     * Constructor
     *
     * @param array $config
     */
    public function __construct(array $config) {
        $this->loginUrl = $config['loginUrl'];
        $this->apiUrl = $config['apiUrl'];
    }

    public function getUserByToken(string $token): ?Authenticatable {
        $response = Http::acceptJson()
            ->withToken($token)
            ->get($this->apiUrl.'/user');

        if ($response->successful()) {
            return new User($response->json('data'));
        }

        return null;
    }

    public function getLoginUrl(): string {
        return $this->loginUrl;
    }
}
