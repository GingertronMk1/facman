<?php

declare(strict_types=1);

namespace App\Application\Site\Command;

use App\Application\Site\SiteModel;
use App\Domain\Company\ValueObject\CompanyId;
use App\Domain\Site\ValueObject\SiteId;

class UpdateSiteCommand
{
    private function __construct(
        public SiteId $id,
        public string $name,
        public string $description,
        public CompanyId $companyId,
    ) {}

    public function fromModel(SiteModel $model): self
    {
        return new self(
            id: $model->id,
            name: $model->name,
            description: $model->description,
            companyId: $model->company->id
        );
    }
}
