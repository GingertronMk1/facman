<?php

declare(strict_types=1);

namespace App\Application\Site\Command;

use App\Application\Common\AbstractCommand;

class CreateSiteAbstractCommand extends AbstractCommand
{
    public function __construct(
        public string $name = '',
        public string $description = '',
    ) {}
}
