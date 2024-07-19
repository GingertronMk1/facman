<?php

declare(strict_types=1);

namespace App\Infrastructure\Site;

readonly class DbalSiteFinder implements \App\Application\Site\SiteFinderInterface
{
    public function __construct(
        private \Doctrine\DBAL\Connection $connection,
    ) {
    }
}
