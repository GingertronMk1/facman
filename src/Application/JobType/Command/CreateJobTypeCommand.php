<?php

declare(strict_types=1);

namespace App\Application\JobType\Command;

use App\Domain\JobType\ValueObject\JobTypeId;

class CreateJobTypeCommand
{
    public function __construct(
        public string $name = '',
        public ?string $description = null,
        public string $colour = '#000000',
    ) {}
}
