<?php

declare(strict_types=1);

namespace App\Application\Floor\CommandHandler;

use App\Application\Common\CommandHandlerInterface;
use App\Application\Common\CommandInterface;
use App\Application\Common\Exception\CommandHandlerException;
use App\Application\Floor\Command\CreateFloorCommand;
use App\Domain\Floor\FloorEntity;
use App\Domain\Floor\FloorRepositoryException;
use App\Domain\Floor\FloorRepositoryInterface;
use App\Domain\Floor\ValueObject\FloorId;
use InvalidArgumentException;

readonly class CreateFloorCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private FloorRepositoryInterface $floorRepositoryInterface,
    ) {}

    /**
     * @throws FloorRepositoryException
     * @throws InvalidArgumentException
     * @throws CommandHandlerException
     * @throws CommandHandlerException
     */
    public function handle(CommandInterface $command, mixed ...$args): FloorId
    {
        if (!$command instanceof CreateFloorCommand) {
            throw CommandHandlerException::invalidCommandPassed($command);
        }
        if (!$command->building) {
            throw new CommandHandlerException('No building ID given');
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
