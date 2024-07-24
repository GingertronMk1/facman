<?php

declare(strict_types=1);

namespace App\Application\JobStatus\Command;

use App\Application\Common\CommandInterface;

class CreateJobStatusCommand implements CommandInterface
{
    public function __construct(
        public string $name = '',
        public ?string $description = '',
        public string $colour = ''
    ) {}
}
