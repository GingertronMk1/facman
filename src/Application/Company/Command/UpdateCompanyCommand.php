<?php

declare(strict_types=1);

namespace App\Application\Company\Command;

use App\Domain\Company\ValueObject\CompanyId;

class UpdateCompanyCommand
{
    public function __construct(
        public CompanyId $companyId,
    ) {}
}
