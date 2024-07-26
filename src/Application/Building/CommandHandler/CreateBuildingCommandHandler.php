<?php

declare(strict_types=1);

namespace App\Application\Building\CommandHandler;

use App\Application\Address\CommandHandler\StoreAddressCommandHandler;
use App\Application\Building\BuildingModel;
use App\Application\Building\Command\CreateBuildingCommand;
use App\Application\Common\CommandHandlerInterface;
use App\Application\Common\Exception\CommandHandlerException;
use App\Domain\Building\BuildingEntity;
use App\Domain\Building\BuildingRepositoryInterface;
use App\Domain\Building\ValueObject\BuildingId;
use App\Domain\Common\Exception\AbstractRepositoryException;
use InvalidArgumentException;

/**
 * @implements CommandHandlerInterface<CreateBuildingCommand>
 */
readonly class CreateBuildingCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private BuildingRepositoryInterface $buildingRepositoryInterface,
        private StoreAddressCommandHandler $createAddressCommandHandler,
    ) {}

    /**
     * @throws InvalidArgumentException
     * @throws AbstractRepositoryException
     * @throws CommandHandlerException
     */
    public function handle(mixed $command, mixed ...$args): BuildingId
    {
        if (is_null($command->site?->id)) {
            throw new CommandHandlerException('No site ID passed in');
        }
        $id = $this->buildingRepositoryInterface->generateId();
        $entity = new BuildingEntity(
            id: $id,
            name: $command->name,
            description: $command->description,
            siteId: $command->site->id,
        );

        if (!is_null($command->address)) {
            $this->createAddressCommandHandler->handle($command->address, $id, BuildingModel::class);
        }

        return $this->buildingRepositoryInterface->store($entity);
    }
}
