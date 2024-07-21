<?php

declare(strict_types=1);

namespace App\Application\Company\Command;

use App\Application\Company\CompanyModel;
use App\Domain\Company\ValueObject\CompanyId;

class UpdateCompanyCommand
{
    private function __construct(
        public CompanyId $id,
        public string $name,
        public string $description,
        public readonly string $prefix
    ) {}

    public static function fromModel(CompanyModel $company): self
    {
        return new self(
            id: $company->id,
            name: $company->name,
            description: $company->description,
            prefix: $company->prefix
        );
    }
}
