<?php

declare(strict_types=1);

namespace App\Application\Site\Command;

use App\Application\Company\CompanyModel;
use App\Application\Site\SiteModel;
use App\Domain\Company\ValueObject\CompanyId;
use App\Domain\Site\ValueObject\SiteId;

class UpdateSiteCommand
{
    private function __construct(
        public SiteId $id,
        public string $name,
        public string $description,
        public CompanyModel $company,
    ) {}

    public static function fromModel(SiteModel $model): self
    {
        return new self(
            id: $model->id,
            name: $model->name,
            description: $model->description,
            company: $model->company
        );
    }
}
