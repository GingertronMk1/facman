<?php

declare(strict_types=1);

namespace App\Application\JobType\CommandHandler;

use App\Domain\JobType\JobTypeRepositoryInterface;

readonly class UpdateJobTypeCommandHandler
{
    public function __construct(
        private JobTypeRepositoryInterface $jobTypeRepositoryInterface,
    ) {}
}
