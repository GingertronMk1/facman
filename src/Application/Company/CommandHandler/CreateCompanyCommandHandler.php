<?php

declare(strict_types=1);

namespace App\Application\Company\CommandHandler;

use App\Application\Company\Command\CreateCompanyCommand;
use App\Domain\Company\CompanyEntity;
use App\Domain\Company\CompanyRepositoryException;
use App\Domain\Company\CompanyRepositoryInterface;
use App\Domain\Company\ValueObject\CompanyId;

readonly class CreateCompanyCommandHandler
{
    public function __construct(
        private CompanyRepositoryInterface $companyRepositoryInterface,
    ) {}

    /**
     * @throws CompanyRepositoryException
     */
    public function handle(CreateCompanyCommand $command): CompanyId
    {
        $entity = new CompanyEntity(
            id: $this->companyRepositoryInterface->generateId(),
            name: $command->name,
            description: $command->description,
        );

        return $this->companyRepositoryInterface->store($entity);
    }
}
