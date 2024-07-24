<?php

declare(strict_types=1);

namespace App\Application\JobType\Command;

use App\Domain\JobType\ValueObject\JobTypeId;

class UpdateJobTypeCommand
{
    public function __construct(
        public JobTypeId $jobTypeId,
    ) {}
}
