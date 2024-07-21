<?php

namespace App\Infrastructure\Common;

use App\Domain\Common\Exception\AbstractRepositoryException;
use Doctrine\DBAL\Query\QueryBuilder;
use InvalidArgumentException;
use Throwable;

abstract readonly class AbstractDbalRepository
{
    /**
     * @throws AbstractRepositoryException
     * @throws InvalidArgumentException
     */
    protected function executeAndCheck(QueryBuilder $qb, string $exceptionClass): void
    {
        if (!is_a($exceptionClass, AbstractRepositoryException::class, true)) {
            throw new InvalidArgumentException("Must use AbstractRepositoryException. Used {$exceptionClass}.");
        }

        try {
            $rowsAffected = $qb->executeStatement();
        } catch (Throwable $e) {
            throw $exceptionClass::errorUpdatingRows(previous: $e);
        }

        if (1 !== $rowsAffected) {
            throw $exceptionClass::wrongNumberOfRows($rowsAffected);
        }
    }
}
