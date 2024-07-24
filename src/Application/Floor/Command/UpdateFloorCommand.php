<?php

declare(strict_types=1);

namespace App\Application\Floor\Command;

use App\Application\Building\BuildingModel;
use App\Application\Common\CommandInterface;
use App\Application\Floor\FloorModel;
use App\Domain\Floor\ValueObject\FloorId;

class UpdateFloorCommand implements CommandInterface
{
    private function __construct(
        public FloorId $id,
        public string $name,
        public string $description,
        public BuildingModel $building
    ) {}

    public static function fromModel(FloorModel $model): self
    {
        return new self(
            id: $model->id,
            name: $model->name,
            description: $model->description,
            building: $model->building
        );
    }
}
