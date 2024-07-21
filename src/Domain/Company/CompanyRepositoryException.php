<?php

declare(strict_types=1);

namespace App\Domain\Company;

use App\Domain\Common\Exception\AbstractRepositoryException;
use Throwable;

final class CompanyRepositoryException extends AbstractRepositoryException
{
    public static function errorGettingPrefixes(Throwable $e): self
    {
        return new self(
            message: 'Error getting company prefixes',
            previous: $e
        );
    }

    public static function errorGeneratingPrefix(): self
    {
        return new self(
            message: 'Could not generate prefix'
        );
    }
}
