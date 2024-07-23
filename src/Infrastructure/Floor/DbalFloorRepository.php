<?php

declare(strict_types=1);

namespace App\Infrastructure\Floor;

use App\Domain\Common\Exception\AbstractRepositoryException;
use App\Domain\Floor\FloorEntity;
use App\Domain\Floor\FloorRepositoryInterface;
use App\Domain\Floor\ValueObject\FloorId;
use App\Infrastructure\Common\AbstractDbalRepository;
use InvalidArgumentException;

class DbalFloorRepository extends AbstractDbalRepository implements FloorRepositoryInterface
{
    protected string $tableName = 'floors';

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
        $this->storeMappedEntity($entity);

        return $entity->id;
    }

    /**
     * @throws AbstractRepositoryException
     * @throws InvalidArgumentException
     */
    public function update(FloorEntity $entity): FloorId
    {
        $this->updateMappedEntity($entity);

        return $entity->id;
    }
}
