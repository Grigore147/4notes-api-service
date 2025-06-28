<?php

declare(strict_types=1);

namespace App\Domains\Note\Domain\ValueObjects;

use Ramsey\Uuid\Rfc4122\UuidInterface;
use Ramsey\Uuid\Uuid;

final class NoteId {
    private UuidInterface $id;

    public function __construct(string|UuidInterface $id)
    {
        $this->id = is_string($id) ? Uuid::fromString($id) : $id;
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function __toString(): string
    {
        return $this->id->toString();
    }

    public static function generate(): self
    {
        return new self(Uuid::uuid4());
    }

    public static function fromString(string $id): self
    {
        return new self(Uuid::fromString($id));
    }
}
