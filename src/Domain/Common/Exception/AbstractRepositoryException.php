<?php

namespace App\Domain\Common\Exception;

use Exception;
use Throwable;

class AbstractRepositoryException extends Exception
{
    final public function __construct(string $message = '', int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public static function wrongNumberOfRows(int $rows): static
    {
        return new static(
            "Incorrect number of rows affected. Expected 1, got {$rows}.",
        );
    }

    public static function errorUpdatingRows(?Throwable $previous = null): static
    {
        return new static(
            'There was an error updating the database.',
            previous: $previous
        );
    }
}
