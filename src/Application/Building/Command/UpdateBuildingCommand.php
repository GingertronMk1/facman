<?php

declare(strict_types=1);

namespace App\Application\Building\Command;

use App\Domain\Building\ValueObject\BuildingId;

class UpdateBuildingCommand
{
    public function __construct(
        public BuildingId $buildingId,
    ) {}
}
