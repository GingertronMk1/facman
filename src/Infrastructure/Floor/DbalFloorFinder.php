<?php

declare(strict_types=1);

namespace App\Infrastructure\Floor;

use App\Application\Building\BuildingFinderException;
use App\Application\Building\BuildingFinderInterface;
use App\Application\Floor\FloorFinderException;
use App\Application\Floor\FloorFinderInterface;
use App\Application\Floor\FloorModel;
use App\Domain\Building\ValueObject\BuildingId;
use App\Domain\Common\ValueObject\DateTime;
use App\Domain\Floor\ValueObject\FloorId;
use App\Infrastructure\Common\AbstractDbalRepository;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Throwable;

readonly class DbalFloorFinder extends AbstractDbalRepository implements FloorFinderInterface
{
    public function __construct(
        private Connection $connection,
        private BuildingFinderInterface $buildingFinder
    ) {}

    public function findById(FloorId $id): FloorModel
    {
        $qb = $this->getBaseQuery();
        $qb->where('id = :id')
            ->setParameter('id', (string) $id)
        ;

        try {
            $result = $qb->fetchAssociative();
        } catch (Throwable $e) {
            throw BuildingFinderException::errorGettingRows($e);
        }

        return $this->createFromRow($result);
    }

    public function all(): array
    {
        $qb = $this->getBaseQuery();

        try {
            $rows = $qb->fetchAllAssociative();
        } catch (Throwable $e) {
            throw BuildingFinderException::errorGettingRows($e);
        }

        return array_map(fn ($row) => $this->createFromRow($row), $rows);
    }

    /**
     * @param array<string, mixed>|false $row
     *
     * @throws FloorFinderException
     */
    private function createFromRow(array|false $row): FloorModel
    {
        if (!$row) {
            throw new FloorFinderException('No rows found');
        }

        try {
            $id = FloorId::fromString($row['id']);
            $createdAt = DateTime::fromString($row['created_at']);
            $updatedAt = DateTime::fromString($row['updated_at']);
            $deletedAt = null;
            if (is_string($row['deleted_at'])) {
                $deletedAt = DateTime::fromString($row['deleted_at']);
            }

            $building = $this->buildingFinder->findById(BuildingId::fromString($row['building_id']));
        } catch (Throwable $e) {
            throw FloorFinderException::errorCreatingModel($e);
        }

        return new FloorModel(
            id: $id,
            name: $row['name'],
            description: $row['description'],
            building: $building,
            createdAt: $createdAt,
            updatedAt: $updatedAt,
            deletedAt: $deletedAt
        );
    }

    private function getBaseQuery(): QueryBuilder
    {
        $qb = $this->connection->createQueryBuilder();
        $qb->select('*')->from('floors');

        return $qb;
    }
}
