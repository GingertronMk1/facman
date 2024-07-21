<?php

declare(strict_types=1);

namespace App\Infrastructure\Site;

use App\Application\Common\ClockInterface;
use App\Domain\Site\SiteEntity;
use App\Domain\Site\SiteRepositoryException;
use App\Domain\Site\SiteRepositoryInterface;
use App\Domain\Site\ValueObject\SiteId;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Throwable;

readonly class DbalSiteRepository implements SiteRepositoryInterface
{
    private const TABLE = 'sites';

    public function __construct(
        private Connection $connection,
        private ClockInterface $clockInterface,
    ) {}

    public function generateId(): SiteId
    {
        return SiteId::generate();
    }

    public function store(SiteEntity $entity): SiteId
    {
        $qb = $this->connection->createQueryBuilder();
        $qb->insert(self::TABLE)
            ->values([
                'id' => ':id',
                'name' => ':name',
                'description' => ':description',
                'company_id' => ':company_id',
                'created_at' => ':now',
                'updated_at' => ':now',
            ])
            ->setParameters([
                'id' => (string) $entity->id,
                'name' => $entity->name,
                'description' => $entity->description,
                'company_id' => (string) $entity->companyId,
                'now' => (string) $this->clockInterface->getTime(),
            ])
        ;
        $this->executeAndCheck($qb);

        return $entity->id;
    }

    public function update(SiteEntity $entity): SiteId
    {
        $qb = $this->connection->createQueryBuilder();
        $qb
            ->update(self::TABLE)
            ->where('id = :id')
            ->values([
                'name' => ':name',
                'description' => ':description',
                'company_id' => ':company_id',
                'updated_at' => ':now',
            ])
            ->setParameters([
                'id' => (string) $entity->id,
                'name' => $entity->name,
                'description' => $entity->description,
                'company_id' => (string) $entity->companyId,
                'now' => (string) $this->clockInterface->getTime(),
            ])
        ;

        $this->executeAndCheck($qb);

        return $entity->id;
    }

    /**
     * @throws SiteRepositoryException
     */
    private function executeAndCheck(QueryBuilder $qb): void
    {
        try {
            $rowsAffected = $qb->executeStatement();
        } catch (Throwable $e) {
            throw SiteRepositoryException::errorUpdatingRows(previous: $e);
        }

        if (1 !== $rowsAffected) {
            throw SiteRepositoryException::wrongNumberOfRows($rowsAffected);
        }
    }
}
