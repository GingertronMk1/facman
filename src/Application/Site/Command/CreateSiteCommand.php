<?php

declare(strict_types=1);

namespace App\Application\Site\Command;

use App\Domain\Company\ValueObject\CompanyId;

class CreateSiteCommand
{
    public function __construct(
        public string $name = '',
        public string $description = '',
        public ?CompanyId $companyId = null
    ) {}
}
