<?php

declare(strict_types=1);

namespace App\Application\Building\CommandHandler;

use App\Application\Building\Command\CreateBuildingCommand;
use App\Domain\Building\BuildingEntity;
use App\Domain\Building\BuildingRepositoryInterface;
use App\Domain\Building\ValueObject\BuildingId;
use App\Domain\Common\Exception\AbstractRepositoryException;
use InvalidArgumentException;

readonly class CreateBuildingCommandHandler
{
    public function __construct(
        private BuildingRepositoryInterface $buildingRepositoryInterface,
    ) {}

    /**
     * @throws InvalidArgumentException
     * @throws AbstractRepositoryException
     */
    public function handle(CreateBuildingCommand $command): BuildingId
    {
        if (!$command->site?->id) {
            throw new InvalidArgumentException('No site ID passed in');
        }
        $entity = new BuildingEntity(
            id: $this->buildingRepositoryInterface->generateId(),
            name: $command->name,
            description: $command->description,
            siteId: $command->site->id
        );

        return $this->buildingRepositoryInterface->store($entity);
    }
}
