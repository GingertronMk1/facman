<?php

declare(strict_types=1);

namespace App\Application\Building;

use App\Application\Common\Exception\AbstractFinderException;
use App\Domain\Building\ValueObject\BuildingId;
use App\Domain\Site\ValueObject\SiteId;

interface BuildingFinderInterface
{
    /**
     * @throws AbstractFinderException
     */
    public function findById(BuildingId $id): BuildingModel;

    /**
     * @return array<BuildingModel>
     *
     * @throws AbstractFinderException
     */
    public function all(): array;

    /**
     * @return array<BuildingModel>
     *
     * @throws AbstractFinderException
     */
    public function allForSite(SiteId $siteId): array;
}
