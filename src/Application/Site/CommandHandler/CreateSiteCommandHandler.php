<?php

declare(strict_types=1);

namespace App\Application\Site\CommandHandler;

use App\Application\Address\CommandHandler\StoreAddressCommandHandler;
use App\Application\Common\CommandHandlerInterface;
use App\Application\Common\Exception\CommandHandlerException;
use App\Application\Site\Command\CreateSiteCommand;
use App\Application\Site\SiteModel;
use App\Domain\Site\SiteEntity;
use App\Domain\Site\SiteRepositoryException;
use App\Domain\Site\SiteRepositoryInterface;
use App\Domain\Site\ValueObject\SiteId;
use InvalidArgumentException;

/**
 * @implements CommandHandlerInterface<CreateSiteCommand>
 */
readonly class CreateSiteCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private SiteRepositoryInterface $siteRepositoryInterface,
        private StoreAddressCommandHandler $storeAddressCommandHandler
    ) {}

    /**
     * @throws SiteRepositoryException
     * @throws InvalidArgumentException
     * @throws CommandHandlerException
     */
    public function handle(mixed $command, mixed ...$args): SiteId
    {
        if (is_null($command->company)) {
            throw new CommandHandlerException('No company ID');
        }
        $id = $this->siteRepositoryInterface->generateId();
        $entity = new SiteEntity(
            id: $id,
            name: $command->name,
            description: $command->description,
            companyId: $command->company->id
        );

        if (!is_null($command->address)) {
            $this->storeAddressCommandHandler->handle($command->address, $id, SiteModel::class);
        }

        return $this->siteRepositoryInterface->store($entity);
    }
}
