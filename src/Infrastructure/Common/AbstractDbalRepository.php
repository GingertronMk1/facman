<?php

namespace App\Infrastructure\Common;

use App\Application\Common\ClockInterface;
use App\Domain\Common\AbstractMappedEntity;
use App\Domain\Common\Exception\AbstractRepositoryException;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use InvalidArgumentException;
use LogicException;
use Throwable;

abstract class AbstractDbalRepository
{
    protected string $tableName;

    protected string $exceptionClass;

    public function __construct(
        protected readonly Connection $connection,
        protected readonly ClockInterface $clock
    ) {}

    protected function getQueryBuilder(): QueryBuilder
    {
        return $this->connection->createQueryBuilder();
    }

    /**
     * @throws InvalidArgumentException
     * @throws AbstractRepositoryException
     * @throws LogicException
     */
    protected function storeMappedEntity(AbstractMappedEntity $entity): bool
    {
        $qb = $this->getQueryBuilder();
        $qb->insert($this->tableName);

        foreach ($entity->getMappedData() as $key => $value) {
            $qb->setValue($key, ":{$key}")
                ->setParameter($key, $value)
            ;
        }

        if (!is_null($entity->createdAt)) {
            $qb->setValue($entity->createdAt, ':now');
        }
        if (!is_null($entity->updatedAt)) {
            $qb->setValue($entity->updatedAt, ':now');
        }

        $qb->setParameter('now', (string) $this->clock->getTime());

        $this->executeAndCheck($qb, $this->exceptionClass);

        return true;
    }

    /**
     * @throws InvalidArgumentException
     * @throws AbstractRepositoryException
     * @throws LogicException
     */
    protected function updateMappedEntity(AbstractMappedEntity $entity): bool
    {
        $qb = $this->getQueryBuilder();
        $qb->update($this->tableName);

        foreach ($entity->getIdentifierColumns() as $idColKey => $idColVal) {
            $qb->andWhere("{$idColKey} = :{$idColKey}")
                ->setParameter("{$idColKey}", $idColVal)
            ;
        }

        foreach ($entity->getMappedData() as $key => $value) {
            $qb->set($key, ":{$key}")
                ->setParameter($key, $value)
            ;
        }

        if (!is_null($entity->updatedAt)) {
            $qb->setValue($entity->updatedAt, ':now');
        }

        $qb->setParameter('now', (string) $this->clock->getTime());

        $this->executeAndCheck($qb, $this->exceptionClass);

        return true;
    }

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
