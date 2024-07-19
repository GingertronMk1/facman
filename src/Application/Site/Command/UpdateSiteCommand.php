<?php

declare(strict_types=1);

namespace App\Application\Site\Command;

class UpdateSiteCommand
{
    public function __construct(
        public \App\Domain\Site\ValueObject\SiteId $siteId,
    ) {
    }
}
