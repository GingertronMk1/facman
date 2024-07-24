<?php

declare(strict_types=1);

namespace App\Application\Company\Command;

use App\Application\Common\CommandInterface;

class CreateCompanyCommand implements CommandInterface
{
    public function __construct(
        public string $name = '',
        public string $description = '',
        public ?string $prefix = '',
    ) {}
}
