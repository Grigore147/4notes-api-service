<?php

declare(strict_types=1);

namespace App\Domains\Auth\Infrastructure\Models;

use JsonException;
use JsonSerializable;
use RuntimeException;
use Ramsey\Uuid\UuidInterface;
use Illuminate\Auth\GenericUser;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Domains\Auth\Infrastructure\Database\Factories\UserFactory;

final class User extends GenericUser implements Arrayable, Jsonable, JsonSerializable
{
    use HasFactory;

    public static $factory = UserFactory::class;

    /**
     * Indicates that the object's string representation should be escaped when __toString is invoked.
     *
     * @var bool
     */
    protected $escapeWhenCastingToString = false;

    /**
     * Get the primary key name for the model.
     *
     * @var string
     */
    public function getKeyName(): string
    {
        return 'id';
    }

    /**
     * Get the value of the model's primary key.
     *
     * @return UuidInterface|string
     */
    public function getKey(): UuidInterface|string
    {
        return $this->id;
    }

    /**
     * Determine if the user is an administrator.
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return false;
    }

    /**
     * Get all user attributes.
     *
     * @var array
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * Convert the model instance to an array.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->getAttributes();
    }

    /**
     * Convert the user instance to JSON.
     *
     * @param  int  $options
     * @return string
     *
     * @throws RuntimeException;
     */
    public function toJson($options = 0)
    {
        try {
            $json = json_encode($this->jsonSerialize(), $options | JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            throw new RuntimeException('Error encoding resource ['.get_class($this).'] with ID ['.$this->id.'] to JSON: '.$e->getMessage());
        }

        return $json;
    }

    /**
     * Convert the object into something JSON serializable.
     *
     * @return mixed
     */
    public function jsonSerialize(): mixed
    {
        return $this->toArray();
    }

    /**
     * Indicate that the object's string representation should be escaped when __toString is invoked.
     *
     * @param  bool  $escape
     * @return $this
     */
    public function escapeWhenCastingToString($escape = true)
    {
        $this->escapeWhenCastingToString = $escape;

        return $this;
    }

    /**
     * Convert the model to its string representation.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->escapeWhenCastingToString
            ? e($this->toJson())
            : $this->toJson();
    }
}
