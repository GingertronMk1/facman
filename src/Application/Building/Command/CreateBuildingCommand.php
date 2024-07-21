<?php

declare(strict_types=1);

namespace App\Application\Building\Command;

use App\Application\Site\SiteModel;

class CreateBuildingCommand
{
    public function __construct(
        public string $name = '',
        public string $description = '',
        public ?SiteModel $site = null,
    ) {}
}
