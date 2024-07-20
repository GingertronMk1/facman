<?php

declare(strict_types=1);

namespace App\Application\Site\CommandHandler;

use App\Application\Site\Command\CreateSiteCommand;
use App\Domain\Site\SiteEntity;
use App\Domain\Site\SiteRepositoryInterface;
use App\Domain\Site\ValueObject\SiteId;

readonly class CreateSiteCommandHandler
{
    public function __construct(
        private SiteRepositoryInterface $siteRepositoryInterface,
    ) {}

    public function handle(CreateSiteCommand $command): SiteId
    {
        $entity = new SiteEntity(
            id: $this->siteRepositoryInterface->generateId(),
            name: $command->name,
            description: $command->description,
        );

        return $this->siteRepositoryInterface->store($entity);
    }
}
