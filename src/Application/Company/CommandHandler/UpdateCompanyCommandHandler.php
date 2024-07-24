<?php

declare(strict_types=1);

namespace App\Application\Company\CommandHandler;

use App\Application\Common\CommandHandlerInterface;
use App\Application\Common\CommandInterface;
use App\Application\Common\Exception\CommandHandlerException;
use App\Application\Company\Command\UpdateCompanyCommand;
use App\Domain\Company\CompanyEntity;
use App\Domain\Company\CompanyRepositoryException;
use App\Domain\Company\CompanyRepositoryInterface;
use App\Domain\Company\ValueObject\CompanyId;

readonly class UpdateCompanyCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private CompanyRepositoryInterface $companyRepositoryInterface,
    ) {}

    /**
     * @throws CompanyRepositoryException
     */
    public function handle(CommandInterface $command, mixed ...$args): CompanyId
    {
        if (!$command instanceof UpdateCompanyCommand) {
            throw CommandHandlerException::invalidCommandPassed($command);
        }
        $entity = new CompanyEntity(
            id: $command->id,
            name: $command->name,
            description: $command->description,
            prefix: $command->prefix,
        );

        return $this->companyRepositoryInterface->update($entity);
    }
}
