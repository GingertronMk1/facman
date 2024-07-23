<?php

declare(strict_types=1);

namespace App\Application\JobStatus;

use App\Domain\JobStatus\ValueObject\JobStatusId;

interface JobStatusFinderInterface
{
    public function findById(JobStatusId $id): JobStatusModel;

    /**
     * @return array<JobStatusModel>
     */
    public function all(): array;
}
