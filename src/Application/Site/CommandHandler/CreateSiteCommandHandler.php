<?php

declare(strict_types=1);

namespace App\Application\Site\CommandHandler;

use App\Application\Address\CommandHandler\StoreAddressCommandHandler;
use App\Application\Site\Command\CreateSiteCommand;
use App\Application\Site\SiteModel;
use App\Domain\Site\SiteEntity;
use App\Domain\Site\SiteRepositoryException;
use App\Domain\Site\SiteRepositoryInterface;
use App\Domain\Site\ValueObject\SiteId;
use InvalidArgumentException;

readonly class CreateSiteCommandHandler
{
    public function __construct(
        private SiteRepositoryInterface $siteRepositoryInterface,
        private StoreAddressCommandHandler $storeAddressCommandHandler
    ) {}

    /**
     * @throws SiteRepositoryException
     * @throws InvalidArgumentException
     */
    public function handle(CreateSiteCommand $command): SiteId
    {
        if (is_null($command->company)) {
            throw new InvalidArgumentException('No company ID');
        }
        $id = $this->siteRepositoryInterface->generateId();
        $entity = new SiteEntity(
            id: $id,
            name: $command->name,
            description: $command->description,
            companyId: $command->company->id
        );

        if ($command->address) {
            $this->storeAddressCommandHandler->handle($command->address, $id, SiteModel::class);
        }

        return $this->siteRepositoryInterface->store($entity);
    }
}
