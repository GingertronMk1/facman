<?php

declare(strict_types=1);

namespace App\Infrastructure\Building;

use App\Application\Common\ClockInterface;
use App\Domain\Building\BuildingEntity;
use App\Domain\Building\BuildingRepositoryException;
use App\Domain\Building\BuildingRepositoryInterface;
use App\Domain\Building\ValueObject\BuildingId;
use App\Domain\Common\Exception\AbstractRepositoryException;
use App\Infrastructure\Common\AbstractDbalRepository;
use Doctrine\DBAL\Connection;
use InvalidArgumentException;

readonly class DbalBuildingRepository extends AbstractDbalRepository implements BuildingRepositoryInterface
{
    private const TABLE = 'buildings';

    public function __construct(
        private Connection $connection,
        private ClockInterface $clockInterface,
    ) {}

    public function generateId(): BuildingId
    {
        return BuildingId::generate();
    }

    /**
     * @throws AbstractRepositoryException
     * @throws InvalidArgumentException
     */
    public function store(BuildingEntity $entity): BuildingId
    {
        $qb = $this->connection->createQueryBuilder();
        $qb->insert(self::TABLE)
            ->values([
                'id' => ':id',
                'name' => ':name',
                'description' => ':description',
                'site_id' => ':site_id',
            ])
            ->setParameters([
                'id' => (string) $entity->id,
                'name' => $entity->name,
                'description' => $entity->description,
                'site_id' => (string) $entity->siteId,
            ])
        ;

        $this->executeAndCheck($qb, BuildingRepositoryException::class);

        return $entity->id;
    }

    /**
     * @throws AbstractRepositoryException
     * @throws InvalidArgumentException
     */
    public function update(BuildingEntity $entity): BuildingId
    {
        $qb = $this->connection->createQueryBuilder();
        $qb
            ->update(self::TABLE)
            ->where('id = :id')
            ->set('name', ':name')
            ->set('description', ':description')
            ->set('updated_at', ':now')
            ->setParameters([
                'id' => (string) $entity->id,
                'name' => $entity->name,
                'description' => $entity->description,
                'now' => (string) $this->clockInterface->getTime(),
            ])
        ;

        $this->executeAndCheck($qb, BuildingRepositoryException::class);

        return $entity->id;
    }
}
