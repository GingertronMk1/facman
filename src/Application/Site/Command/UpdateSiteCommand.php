<?php

declare(strict_types=1);

namespace App\Application\Site\Command;

use App\Application\Common\AbstractCommand;
use App\Domain\Site\ValueObject\SiteId;

class UpdateSiteAbstractCommand extends AbstractCommand
{
    public function __construct(
        public SiteId $siteId,
    ) {}
}
