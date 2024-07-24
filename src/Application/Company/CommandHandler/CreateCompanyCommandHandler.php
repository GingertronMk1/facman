<?php

declare(strict_types=1);

namespace App\Application\Company\CommandHandler;

use App\Application\Common\CommandHandlerInterface;
use App\Application\Common\CommandInterface;
use App\Domain\Company\CompanyEntity;
use App\Domain\Company\CompanyRepositoryException;
use App\Domain\Company\CompanyRepositoryInterface;
use App\Domain\Company\ValueObject\CompanyId;

readonly class CreateCompanyCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private CompanyRepositoryInterface $companyRepositoryInterface,
    ) {}

    /**
     * @throws CompanyRepositoryException
     */
    public function handle(CommandInterface $command, mixed ...$args): CompanyId
    {
        $prefix = $command->prefix;
        if (empty($prefix)) {
            $prefix = $this->companyRepositoryInterface->generatePrefix($command->name);
        }
        $entity = new CompanyEntity(
            id: $this->companyRepositoryInterface->generateId(),
            name: $command->name,
            description: $command->description,
            prefix: $prefix
        );

        return $this->companyRepositoryInterface->store($entity);
    }
}
