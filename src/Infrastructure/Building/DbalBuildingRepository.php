<?php

declare(strict_types=1);

namespace App\Infrastructure\Building;

use App\Domain\Building\BuildingEntity;
use App\Domain\Building\BuildingRepositoryException;
use App\Domain\Building\BuildingRepositoryInterface;
use App\Domain\Building\ValueObject\BuildingId;
use App\Domain\Common\Exception\AbstractRepositoryException;
use App\Infrastructure\Common\AbstractDbalRepository;
use InvalidArgumentException;

class DbalBuildingRepository extends AbstractDbalRepository implements BuildingRepositoryInterface
{
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
        $this->storeMappedEntity($entity);

        return $entity->id;
    }

    /**
     * @throws AbstractRepositoryException
     * @throws InvalidArgumentException
     */
    public function update(BuildingEntity $entity): BuildingId
    {
        $this->updateMappedEntity($entity);

        return $entity->id;
    }

    protected function getTableName(): string
    {
        return 'buildings';
    }

    protected function getExceptionClass(): string
    {
        return BuildingRepositoryException::class;
    }
}
