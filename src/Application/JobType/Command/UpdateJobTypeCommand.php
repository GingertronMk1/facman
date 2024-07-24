<?php

declare(strict_types=1);

namespace App\Application\JobType\Command;

use App\Application\Common\CommandInterface;
use App\Domain\JobType\ValueObject\JobTypeId;

class UpdateJobTypeCommand implements CommandInterface
{
    public function __construct(
        public JobTypeId $jobTypeId,
    ) {}
}
