<?php

declare(strict_types=1);

namespace App\Infrastructure\Company;

use App\Application\Common\ClockInterface;
use App\Domain\Company\CompanyEntity;
use App\Domain\Company\CompanyRepositoryException;
use App\Domain\Company\CompanyRepositoryInterface;
use App\Domain\Company\ValueObject\CompanyId;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Throwable;

readonly class DbalCompanyRepository implements CompanyRepositoryInterface
{
    private const TABLE = 'companies';

    public function __construct(
        private Connection $connection,
        private ClockInterface $clockInterface,
    ) {}

    public function generateId(): CompanyId
    {
        return CompanyId::generate();
    }

    public function store(CompanyEntity $entity): CompanyId
    {
        $qb = $this->connection->createQueryBuilder();
        $qb->insert(self::TABLE)
            ->values([
                'id' => ':id',
                'name' => ':name',
                'description' => ':description',
                'created_at' => ':now',
                'updated_at' => ':now',
            ])
            ->setParameters([
                'id' => (string) $entity->id,
                'name' => $entity->name,
                'description' => $entity->description,
                'now' => (string) $this->clockInterface->getTime(),
            ])
        ;

        $this->executeAndCheck($qb);

        return $entity->id;
    }

    public function update(CompanyEntity $entity): CompanyId
    {
        $qb = $this->connection->createQueryBuilder();
        $qb
            ->update(self::TABLE)
            ->where('id = :id')
            ->values([
                'name' => ':name',
                'description' => ':description',
                'updated_at' => ':now',
            ])
            ->setParameters([
                'id' => (string) $entity->id,
                'name' => $entity->name,
                'description' => $entity->description,
                'now' => (string) $this->clockInterface->getTime(),
            ])
        ;

        $this->executeAndCheck($qb);

        return $entity->id;
    }

    /**
     * @throws CompanyRepositoryException
     */
    private function executeAndCheck(QueryBuilder $qb): void
    {
        try {
            $rowsAffected = $qb->executeStatement();
        } catch (Throwable $e) {
            throw CompanyRepositoryException::errorUpdatingRows(previous: $e);
        }

        if (1 !== $rowsAffected) {
            throw CompanyRepositoryException::wrongNumberOfRows($rowsAffected);
        }
    }
}