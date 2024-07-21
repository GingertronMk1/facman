<?php

declare(strict_types=1);

namespace App\Application\Floor\CommandHandler;

use App\Application\Floor\Command\CreateFloorCommand;
use App\Domain\Floor\FloorEntity;
use App\Domain\Floor\FloorRepositoryException;
use App\Domain\Floor\FloorRepositoryInterface;
use App\Domain\Floor\ValueObject\FloorId;
use InvalidArgumentException;

readonly class CreateFloorCommandHandler
{
    public function __construct(
        private FloorRepositoryInterface $floorRepositoryInterface,
    ) {}

    /**
     * @throws FloorRepositoryException
     * @throws InvalidArgumentException
     */
    public function handle(CreateFloorCommand $command): FloorId
    {
        if (!$command->building) {
            throw new InvalidArgumentException('No building ID given');
        }
        $entity = new FloorEntity(
            id: $this->floorRepositoryInterface->generateId(),
            name: $command->name,
            description: $command->description,
            buildingId: $command->building->id
        );

        return $this->floorRepositoryInterface->store($entity);
    }
}
