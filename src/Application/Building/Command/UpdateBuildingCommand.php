<?php

declare(strict_types=1);

namespace App\Application\Building\Command;

use App\Application\Building\BuildingModel;
use App\Application\Site\SiteModel;
use App\Domain\Building\ValueObject\BuildingId;

class UpdateBuildingCommand
{
    private function __construct(
        public BuildingId $id,
        public string $name,
        public string $description,
        public SiteModel $site,
    ) {}

    public static function fromModel(BuildingModel $model): self
    {
        return new self(
            id: $model->id,
            name: $model->name,
            description: $model->description,
            site: $model->site,
        );
    }
}
