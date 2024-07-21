<?php

namespace App\Application\Common\Exception;

use Exception;
use InvalidArgumentException;
use Throwable;

abstract class AbstractFinderException extends Exception
{
    final public function __construct(string $message = '', int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public static function errorGettingRows(?Throwable $e = null): static
    {
        return new static('Error getting rows', previous: $e);
    }

    public static function notFound(): static
    {
        return new static('Not found', 404);
    }

    public static function invalidId(?InvalidArgumentException $e = null): static
    {
        return new static(
            'Invalid string passed to ID',
            previous: $e
        );
    }

    public static function errorCreatingModel(?Throwable $e = null): static
    {
        return new static(
            message: 'Error creating site',
            previous: $e
        );
    }
}
