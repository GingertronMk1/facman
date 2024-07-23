<?php

declare(strict_types=1);

namespace App\Application\JobStatus\Command;

class CreateJobStatusCommand
{
    public function __construct(
        public string $name = '',
        public ?string $description = '',
        public string $colour = ''
    ) {}
}
