<?php

declare(strict_types=1);

namespace App\Infrastructure\Site;

readonly class DbalSiteRepository implements \App\Domain\Site\SiteRepositoryInterface
{
    public function __construct(
        private \Doctrine\DBAL\Connection $connection,
    ) {
    }
}
