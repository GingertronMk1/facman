<?php

declare(strict_types=1);

namespace App\Application\Site\CommandHandler;

class CreateSiteCommandHandler
{
    public function __construct(
        private \App\Domain\Site\SiteRepositoryInterface $siteRepositoryInterface,
    ) {
    }
}
