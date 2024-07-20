<?php

declare(strict_types=1);

namespace App\Domain\Company;

use App\Domain\Company\ValueObject\CompanyId;

class CompanyEntity
{
    public function __construct(
        public CompanyId $id,
        public string $name,
        public string $description,
    ) {}
}
