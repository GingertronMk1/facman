<?php

declare(strict_types=1);

namespace App\Application\Site\Command;

use App\Application\Company\CompanyModel;

class CreateSiteCommand
{
    public function __construct(
        public string $name = '',
        public string $description = '',
        public ?CompanyModel $company = null
    ) {}
}
