<?php

declare(strict_types=1);

namespace App\Domain\Company;

use App\Domain\Common\AbstractMappedEntity;
use App\Domain\Company\ValueObject\CompanyId;

class CompanyEntity extends AbstractMappedEntity
{
    public function __construct(
        public CompanyId $id,
        public string $name,
        public string $description,
        public string $prefix,
    ) {}

    public function getIdentifierColumns(): array
    {
        return ['id' => (string) $this->id];
    }
}
