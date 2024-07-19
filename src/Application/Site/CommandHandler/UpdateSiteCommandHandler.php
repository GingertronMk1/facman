<?php

declare(strict_types=1);

namespace App\Application\Site\CommandHandler;

use App\Domain\Site\SiteRepositoryInterface;

class UpdateSiteCommandHandler
{
    public function __construct(
        private SiteRepositoryInterface $siteRepositoryInterface,
    ) {}
}
