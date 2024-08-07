<?php

declare(strict_types=1);

namespace App\Application\Building\CommandHandler;

use App\Application\Building\Command\UpdateBuildingCommand;
use App\Application\Common\CommandHandlerInterface;
use App\Application\Common\Exception\CommandHandlerException;
use App\Domain\Building\BuildingEntity;
use App\Domain\Building\BuildingRepositoryException;
use App\Domain\Building\BuildingRepositoryInterface;
use App\Domain\Building\ValueObject\BuildingId;
use App\Domain\Common\Exception\AbstractRepositoryException;

/**
 * @implements CommandHandlerInterface<UpdateBuildingCommand>
 */
readonly class UpdateBuildingCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private BuildingRepositoryInterface $buildingRepositoryInterface,
    ) {}

    /**
     * @throws BuildingRepositoryException
     * @throws AbstractRepositoryException
     * @throws CommandHandlerException
     */
    public function handle(mixed $command, mixed ...$args): BuildingId
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
