<?php

declare(strict_types=1);

namespace App\Domain\Site;

class SiteEntity
{
    public function __construct(
        public ValueObject\SiteId $siteId,
    ) {
    }
}
