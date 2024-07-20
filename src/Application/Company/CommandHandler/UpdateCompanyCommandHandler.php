<?php

declare(strict_types=1);

namespace App\Application\Company\CommandHandler;

use App\Domain\Company\CompanyRepositoryInterface;

readonly class UpdateCompanyCommandHandler
{
    public function __construct(
        private CompanyRepositoryInterface $companyRepositoryInterface,
    ) {}
}
