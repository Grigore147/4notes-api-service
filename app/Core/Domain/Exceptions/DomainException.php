<?php

declare(strict_types=1);

namespace App\Core\Domain\Exceptions;

use Exception;

class DomainException extends Exception // @pest-arch-ignore-line
{
    public function __construct(string $message = 'Domain Exception', int $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
