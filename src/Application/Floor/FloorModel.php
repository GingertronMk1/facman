<?php

declare(strict_types=1);

namespace App\Application\Floor;

use App\Application\Building\BuildingModel;
use App\Domain\Common\ValueObject\DateTime;
use App\Domain\Floor\ValueObject\FloorId;

readonly class FloorModel
{
    public function __construct(
        public FloorId $id,
        public string $name,
        public string $description,
        public BuildingModel $building,
        public DateTime $createdAt,
        public DateTime $updatedAt,
        public ?DateTime $deletedAt,
    ) {}
}
