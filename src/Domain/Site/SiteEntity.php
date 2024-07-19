<?php

declare(strict_types=1);

namespace App\Domain\Site;

use App\Domain\Site\ValueObject\SiteId;

class SiteEntity
{
    public function __construct(
        public SiteId $id,
        public string $name,
        public string $description,
    ) {}
}
