<?php

declare(strict_types=1);

namespace App\Application\Site\Command;

class CreateSiteCommand
{
    public function __construct(
        public string $name = '',
        public string $description = '',
    ) {}
}
