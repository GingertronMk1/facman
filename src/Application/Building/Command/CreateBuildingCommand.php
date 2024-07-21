<?php

declare(strict_types=1);

namespace App\Application\Building\Command;

use App\Domain\Site\ValueObject\SiteId;

class CreateBuildingCommand
{
    public function __construct(
        public string $name = '',
        public string $description = '',
        public ?SiteId $siteId = null,
    ) {}
}
