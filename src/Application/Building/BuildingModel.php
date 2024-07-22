<?php

declare(strict_types=1);

namespace App\Application\Building;

use App\Application\Address\AddressModel;
use App\Application\Site\SiteModel;
use App\Domain\Building\ValueObject\BuildingId;
use App\Domain\Common\ValueObject\DateTime;

readonly class BuildingModel
{
    /**
     * @param array<AddressModel> $addresses
     */
    public function __construct(
        public BuildingId $id,
        public string $name,
        public string $description,
        public SiteModel $site,
        public array $addresses,
        public DateTime $createdAt,
        public DateTime $updatedAt,
        public ?DateTime $deletedAt,
    ) {}
}
