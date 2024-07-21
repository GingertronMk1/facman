<?php

declare(strict_types=1);

namespace App\Domain\Floor;

use App\Domain\Floor\ValueObject\FloorId;

interface FloorRepositoryInterface
{
    public function generateId(): FloorId;

    /**
     * @throws FloorRepositoryException
     */
    public function store(FloorEntity $entity): FloorId;

    /**
     * @throws FloorRepositoryException
     */
    public function update(FloorEntity $entity): FloorId;
}
