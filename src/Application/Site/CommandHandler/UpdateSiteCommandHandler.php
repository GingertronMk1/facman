<?php

declare(strict_types=1);

namespace App\Application\Site\CommandHandler;

use App\Application\Site\Command\UpdateSiteCommand;
use App\Domain\Site\SiteEntity;
use App\Domain\Site\SiteRepositoryInterface;
use App\Domain\Site\ValueObject\SiteId;

readonly class UpdateSiteCommandHandler
{
    public function __construct(
        private SiteRepositoryInterface $siteRepositoryInterface,
    ) {}

    public function handle(UpdateSiteCommand $command): SiteId
    {
        $entity = new SiteEntity(
            id: $command->id,
            name: $command->name,
            description: $command->description,
            companyId: $command->companyId,
        );

        return $this->siteRepositoryInterface->update($entity);
    }
}
