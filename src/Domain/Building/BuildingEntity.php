<?php

declare(strict_types=1);

namespace App\Domain\Building;

use App\Domain\Building\ValueObject\BuildingId;
use App\Domain\Common\AbstractMappedEntity;
use App\Domain\Site\ValueObject\SiteId;

class BuildingEntity extends AbstractMappedEntity
{
    public function __construct(
        public BuildingId $id,
        public string $name,
        public string $description,
        public SiteId $siteId,
    ) {}

    public function getIdentifierColumns(): array
    {
        return ['id' => (string) $this->id];
    }
}
