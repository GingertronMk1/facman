<?php

declare(strict_types=1);

namespace App\Application\Site\CommandHandler;

use App\Application\Common\CommandHandlerInterface;
use App\Application\Common\CommandInterface;
use App\Application\Common\Exception\CommandHandlerException;
use App\Application\Site\Command\UpdateSiteCommand;
use App\Domain\Site\SiteEntity;
use App\Domain\Site\SiteRepositoryException;
use App\Domain\Site\SiteRepositoryInterface;
use App\Domain\Site\ValueObject\SiteId;

readonly class UpdateSiteCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private SiteRepositoryInterface $siteRepositoryInterface,
    ) {}

    /**
     * @throws SiteRepositoryException
     * @throws CommandHandlerException
     */
    public function handle(CommandInterface $command, mixed ...$args): SiteId
    {
        if (!$command instanceof UpdateSiteCommand) {
            throw CommandHandlerException::invalidCommandPassed($command);
        }
        $entity = new SiteEntity(
            id: $command->id,
            name: $command->name,
            description: $command->description,
            companyId: $command->company->id,
        );

        return $this->siteRepositoryInterface->update($entity);
    }
}
