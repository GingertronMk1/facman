<?php

declare(strict_types=1);

namespace App\Infrastructure\Building;

use App\Application\Building\BuildingFinderInterface;
use Doctrine\DBAL\Connection;

readonly class DbalBuildingFinder implements BuildingFinderInterface
{
    public function __construct(
        private Connection $connection,
    ) {}
}
