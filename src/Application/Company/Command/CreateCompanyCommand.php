<?php

declare(strict_types=1);

namespace App\Application\Company\Command;

class CreateCompanyCommand
{
    public function __construct(
        public string $name = '',
        public string $description = '',
    ) {}
}
