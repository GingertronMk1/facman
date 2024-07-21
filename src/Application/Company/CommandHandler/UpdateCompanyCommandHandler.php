<?php

declare(strict_types=1);

namespace App\Application\Company\CommandHandler;

use App\Application\Company\Command\UpdateCompanyCommand;
use App\Domain\Company\CompanyEntity;
use App\Domain\Company\CompanyRepositoryInterface;
use App\Domain\Company\ValueObject\CompanyId;

readonly class UpdateCompanyCommandHandler
{
    public function __construct(
        private CompanyRepositoryInterface $companyRepositoryInterface,
    ) {}

    public function handle(UpdateCompanyCommand $command): CompanyId
    {
        $entity = new CompanyEntity(
            id: $command->id,
            name: $command->name,
            description: $command->description,
        );

        return $this->companyRepositoryInterface->update($entity);
    }
}
