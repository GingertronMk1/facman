<?php

declare(strict_types=1);

namespace App\Domain\Building;

use App\Domain\Building\ValueObject\BuildingId;

interface BuildingRepositoryInterface
{
    public function generateId(): BuildingId;

    /**
     * @throws BuildingRepositoryException
     */
    public function store(BuildingEntity $entity): BuildingId;

    /**
     * @throws BuildingRepositoryException
     */
    public function update(BuildingEntity $entity): BuildingId;
}
