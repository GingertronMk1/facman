<?php

declare(strict_types=1);

namespace App\Application\Company\CommandHandler;

use App\Application\Common\CommandHandlerInterface;
use App\Application\Company\Command\CreateCompanyCommand;
use App\Domain\Company\CompanyEntity;
use App\Domain\Company\CompanyRepositoryException;
use App\Domain\Company\CompanyRepositoryInterface;
use App\Domain\Company\ValueObject\CompanyId;

/**
 * @implements CommandHandlerInterface<CreateCompanyCommand>
 */
readonly class CreateCompanyCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private CompanyRepositoryInterface $companyRepositoryInterface,
    ) {}

    /**
     * @throws CompanyRepositoryException
     */
    public function handle(mixed $command, mixed ...$args): CompanyId
    {
        $prefix = $command->prefix;
        if (!is_string($prefix) || strlen($prefix) < 1) {
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
