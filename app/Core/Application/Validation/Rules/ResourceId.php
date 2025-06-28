<?php

declare(strict_types=1);

namespace App\Core\Application\Validation\Rules;

use Closure;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Domains\Auth\Infrastructure\Models\User;

class ResourceId implements ValidationRule // @pest-arch-ignore-line
{
    public const TYPE = 'uuid';
    public const REPOSITORY = null;
    public const ID = 'id';
    public const USER_ID = 'userId';

    /**
     * Run the validation rule.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  Closure $fail
     * @return void
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        /** @var User $user */
        $user = Auth::user();

        if (static::TYPE === 'uuid' && !Str::isUuid($value)) {
            $fail('Invalid UUID');
        }

        $filters = [static::ID => $value];

        if (!$user?->isAdmin()) {
            $filters[static::USER_ID] = $user?->id;
        }

        if (!app(static::REPOSITORY)->exists($filters)) {
            $fail('Resource not found');
        }
    }
}
