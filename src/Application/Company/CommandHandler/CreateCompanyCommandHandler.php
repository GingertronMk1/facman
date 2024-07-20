<?php

declare(strict_types=1);

namespace App\Application\Company\CommandHandler;

readonly class CreateCompanyCommandHandler
{
public function __construct(
private \App\Domain\Company\CompanyRepositoryInterface $companyRepositoryInterface,
) {}
}
