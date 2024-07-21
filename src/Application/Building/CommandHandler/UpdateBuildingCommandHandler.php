<?php

declare(strict_types=1);

namespace App\Application\Building\CommandHandler;

use App\Domain\Building\BuildingRepositoryInterface;

readonly class UpdateBuildingCommandHandler
{
    public function __construct(
        private BuildingRepositoryInterface $buildingRepositoryInterface,
    ) {}
}
