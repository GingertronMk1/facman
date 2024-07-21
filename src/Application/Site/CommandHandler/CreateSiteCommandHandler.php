<?php

declare(strict_types=1);

namespace App\Application\Site\CommandHandler;

use App\Application\Site\Command\CreateSiteCommand;
use App\Domain\Site\SiteEntity;
use App\Domain\Site\SiteRepositoryException;
use App\Domain\Site\SiteRepositoryInterface;
use App\Domain\Site\ValueObject\SiteId;
use InvalidArgumentException;

readonly class CreateSiteCommandHandler
{
    public function __construct(
        private SiteRepositoryInterface $siteRepositoryInterface,
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
        $entity = new SiteEntity(
            id: $this->siteRepositoryInterface->generateId(),
            name: $command->name,
            description: $command->description,
            companyId: $command->company->id
        );

        return $this->siteRepositoryInterface->store($entity);
    }
}
