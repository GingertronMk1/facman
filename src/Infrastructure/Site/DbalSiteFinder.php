<?php

declare(strict_types=1);

namespace App\Infrastructure\Site;

use App\Application\Site\SiteFinderInterface;
use Doctrine\DBAL\Connection;

readonly class DbalSiteFinder implements SiteFinderInterface
{
    public function __construct(
        private Connection $connection,
    ) {}
}
