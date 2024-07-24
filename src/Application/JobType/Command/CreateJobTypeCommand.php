<?php

declare(strict_types=1);

namespace App\Application\JobType\Command;

use App\Application\Common\CommandInterface;

class CreateJobTypeCommand implements CommandInterface
{
    public function __construct(
        public string $name = '',
        public ?string $description = null,
        public string $colour = '#000000',
    ) {}
}
