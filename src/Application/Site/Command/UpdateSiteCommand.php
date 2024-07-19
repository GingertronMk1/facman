<?php

declare(strict_types=1);

namespace App\Application\Site\Command;

use App\Domain\Site\ValueObject\SiteId;

class UpdateSiteCommand
{
    public function __construct(
        public SiteId $siteId,
    ) {}
}
