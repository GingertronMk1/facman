<?php

declare(strict_types=1);

namespace App\Application\Site;

use App\Domain\Site\ValueObject\SiteId;

readonly class SiteModel
{
    public function __construct(
        public SiteId $siteId,
    ) {}
}
