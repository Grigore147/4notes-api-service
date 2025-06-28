<?php

declare(strict_types=1);

namespace App\Domains\Note\Domain\ValueObjects;

final class NoteContent
{
    public function __construct(
        private string $content = ''
    ) {}

    public function getContent(): string
    {
        return $this->content;
    }

    public function __toString(): string
    {
        return $this->content;
    }

    public static function generate(): self
    {
        return new self();
    }

    public static function fromString(string $content): self
    {
        return new self($content);
    }
}
