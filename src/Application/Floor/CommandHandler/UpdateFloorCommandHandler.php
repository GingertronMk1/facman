<?php

declare(strict_types=1);

namespace App\Application\Floor\CommandHandler;

use App\Application\Common\CommandHandlerInterface;
use App\Application\Common\CommandInterface;
use App\Application\Common\Exception\CommandHandlerException;
use App\Application\Floor\Command\UpdateFloorCommand;
use App\Domain\Floor\FloorEntity;
use App\Domain\Floor\FloorRepositoryException;
use App\Domain\Floor\FloorRepositoryInterface;
use App\Domain\Floor\ValueObject\FloorId;

readonly class UpdateFloorCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private FloorRepositoryInterface $floorRepositoryInterface,
    ) {}

    /**
     * @throws FloorRepositoryException
     * @throws CommandHandlerException
     */
    public function handle(CommandInterface $command, mixed ...$args): FloorId
    {
        if (!$command instanceof UpdateFloorCommand) {
            throw CommandHandlerException::invalidCommandPassed($command);
        }
        $entity = new FloorEntity(
            id: $command->id,
            name: $command->name,
            description: $command->description,
            buildingId: $command->building->id
        );

        return $this->floorRepositoryInterface->update($entity);
    }
}
