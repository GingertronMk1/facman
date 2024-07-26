<?php

declare(strict_types=1);

namespace App\Application\Company\CommandHandler;

use App\Application\Common\CommandHandlerInterface;
use App\Application\Company\Command\UpdateCompanyCommand;
use App\Domain\Company\CompanyEntity;
use App\Domain\Company\CompanyRepositoryException;
use App\Domain\Company\CompanyRepositoryInterface;
use App\Domain\Company\ValueObject\CompanyId;

/**
 * @implements CommandHandlerInterface<UpdateCompanyCommand>
 */
readonly class UpdateCompanyCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private CompanyRepositoryInterface $companyRepositoryInterface,
    ) {}

    /**
     * @throws CompanyRepositoryException
     */
    public function handle(mixed $command, mixed ...$args): CompanyId
    {
        $entity = new CompanyEntity(
            id: $command->id,
            name: $command->name,
            description: $command->description,
            prefix: $command->prefix,
        );

        return $this->companyRepositoryInterface->update($entity);
    }
}
