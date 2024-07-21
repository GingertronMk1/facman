<?php

declare(strict_types=1);

namespace App\Application\Building\CommandHandler;

use App\Application\Building\Command\UpdateBuildingCommand;
use App\Domain\Building\BuildingEntity;
use App\Domain\Building\BuildingRepositoryException;
use App\Domain\Building\BuildingRepositoryInterface;
use App\Domain\Building\ValueObject\BuildingId;
use App\Domain\Common\Exception\AbstractRepositoryException;

readonly class UpdateBuildingCommandHandler
{
    public function __construct(
        private BuildingRepositoryInterface $buildingRepositoryInterface,
    ) {}

    /**
     * @throws BuildingRepositoryException
     * @throws AbstractRepositoryException
     */
    public function handle(UpdateBuildingCommand $command): BuildingId
    {
        $entity = new BuildingEntity(
            id: $command->id,
            name: $command->name,
            description: $command->description,
            siteId: $command->site->id,
        );

        return $this->buildingRepositoryInterface->update($entity);
    }
}
