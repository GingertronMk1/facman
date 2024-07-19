<?php

declare(strict_types=1);

namespace App\Application\Site;

readonly class SiteModel
{
    public function __construct(
        public \App\Domain\Site\ValueObject\SiteId $siteId,
    ) {
    }
}
