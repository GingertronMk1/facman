<?php

declare(strict_types=1);

namespace App\Domain\Floor;

use App\Domain\Building\ValueObject\BuildingId;
use App\Domain\Floor\ValueObject\FloorId;

class FloorEntity
{
    public function __construct(
        public FloorId $id,
        public string $name,
        public string $description,
        public BuildingId $buildingId
    ) {}
}
