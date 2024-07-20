<?php

declare(strict_types=1);

namespace App\Infrastructure\Site;

use App\Application\Common\ClockInterface;
use App\Domain\Site\SiteEntity;
use App\Domain\Site\SiteRepositoryInterface;
use App\Domain\Site\ValueObject\SiteId;
use App\Domain\User\UserRepositoryException;
use Doctrine\DBAL\Connection;

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
        $rowsAffected = $qb->executeStatement();
        if (1 !== $rowsAffected) {
            throw new UserRepositoryException('Wrong number of rows');
        }

        return $entity->id;
    }

    public function update(SiteEntity $entity): SiteId
    {
        // TODO: Implement update() method.
    }
}
