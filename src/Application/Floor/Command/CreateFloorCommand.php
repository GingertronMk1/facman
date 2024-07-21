<?php

declare(strict_types=1);

namespace App\Application\Floor\Command;

use App\Application\Building\BuildingModel;

class CreateFloorCommand
{
    public function __construct(
        public string $name = '',
        public string $description = '',
        public ?BuildingModel $building = null
    ) {}
}
