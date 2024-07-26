<?php

declare(strict_types=1);

namespace App\Application\Site\CommandHandler;

use App\Application\Common\CommandHandlerInterface;
use App\Application\Site\Command\UpdateSiteCommand;
use App\Domain\Site\SiteEntity;
use App\Domain\Site\SiteRepositoryException;
use App\Domain\Site\SiteRepositoryInterface;
use App\Domain\Site\ValueObject\SiteId;

/**
 * @implements CommandHandlerInterface<UpdateSiteCommand>
 */
readonly class UpdateSiteCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private SiteRepositoryInterface $siteRepositoryInterface,
    ) {}

    /**
     * @throws SiteRepositoryException
     */
    public function handle(mixed $command, mixed ...$args): SiteId
    {
        $entity = new SiteEntity(
            id: $command->id,
            name: $command->name,
            description: $command->description,
            companyId: $command->company->id,
        );

        return $this->siteRepositoryInterface->update($entity);
    }
}
