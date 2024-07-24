<?php

declare(strict_types=1);

namespace App\Application\Building\CommandHandler;

use App\Application\Address\CommandHandler\StoreAddressCommandHandler;
use App\Application\Building\BuildingModel;
use App\Application\Common\CommandHandlerInterface;
use App\Application\Common\CommandInterface;
use App\Domain\Building\BuildingEntity;
use App\Domain\Building\BuildingRepositoryInterface;
use App\Domain\Building\ValueObject\BuildingId;
use App\Domain\Common\Exception\AbstractRepositoryException;
use InvalidArgumentException;

readonly class CreateBuildingCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private BuildingRepositoryInterface $buildingRepositoryInterface,
        private StoreAddressCommandHandler $createAddressCommandHandler,
    ) {}

    /**
     * @throws InvalidArgumentException
     * @throws AbstractRepositoryException
     */
    public function handle(CommandInterface $command, mixed ...$args): BuildingId
    {
        if (!$command->site?->id) {
            throw new InvalidArgumentException('No site ID passed in');
        }
        $id = $this->buildingRepositoryInterface->generateId();
        $entity = new BuildingEntity(
            id: $id,
            name: $command->name,
            description: $command->description,
            siteId: $command->site->id,
        );

        if ($command->address) {
            $this->createAddressCommandHandler->handle($command->address, $id, BuildingModel::class);
        }

        return $this->buildingRepositoryInterface->store($entity);
    }
}
