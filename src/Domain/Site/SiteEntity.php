<?php

declare(strict_types=1);

namespace App\Domain\Site;

use App\Domain\Common\AbstractMappedEntity;
use App\Domain\Company\ValueObject\CompanyId;
use App\Domain\Site\ValueObject\SiteId;

class SiteEntity extends AbstractMappedEntity
{
    public function __construct(
        public SiteId $id,
        public string $name,
        public string $description,
        public CompanyId $companyId,
    ) {}

    public function getIdentifierColumns(): array
    {
        return ['id' => (string) $this->id];
    }
}
