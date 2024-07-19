<?php

declare(strict_types=1);

namespace App\Application\Site\Command;

class CreateSiteCommand
{
    public function __construct(
        public \App\Domain\Site\ValueObject\SiteId $siteId,
    ) {
    }
}
