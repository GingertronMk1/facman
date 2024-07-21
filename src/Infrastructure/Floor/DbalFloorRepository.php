<?php

declare(strict_types=1);

namespace App\Infrastructure\Floor;

use App\Application\Common\ClockInterface;
use App\Domain\Common\Exception\AbstractRepositoryException;
use App\Domain\Floor\FloorEntity;
use App\Domain\Floor\FloorRepositoryException;
use App\Domain\Floor\FloorRepositoryInterface;
use App\Domain\Floor\ValueObject\FloorId;
use App\Infrastructure\Common\AbstractDbalRepository;
use Doctrine\DBAL\Connection;
use InvalidArgumentException;

readonly class DbalFloorRepository extends AbstractDbalRepository implements FloorRepositoryInterface
{
    private const TABLE = 'floors';

    public function __construct(
        private Connection $connection,
        private ClockInterface $clockInterface,
    ) {}

    public function generateId(): FloorId
    {
        return FloorId::generate();
    }

    /**
     * @throws AbstractRepositoryException
     * @throws InvalidArgumentException
     */
    public function store(FloorEntity $entity): FloorId
    {
        $qb = $this->connection->createQueryBuilder();
        $qb->insert(self::TABLE)
            ->values([
                'id' => ':id',
                'name' => ':name',
                'description' => ':description',
                'building_id' => ':building_id',
                'created_at' => ':now',
                'updated_at' => ':now',
            ])
            ->setParameters([
                'id' => (string) $entity->id,
                'name' => $entity->name,
                'description' => $entity->description,
                'building_id' => (string) $entity->buildingId,
                'now' => (string) $this->clockInterface->getTime(),
            ])
        ;

        $this->executeAndCheck($qb, FloorRepositoryException::class);

        return $entity->id;
    }

    /**
     * @throws AbstractRepositoryException
     * @throws InvalidArgumentException
     */
    public function update(FloorEntity $entity): FloorId
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

        $this->executeAndCheck($qb, FloorRepositoryException::class);

        return $entity->id;
    }
}
