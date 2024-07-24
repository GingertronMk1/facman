<?php

declare(strict_types=1);

namespace App\Domain\JobStatus;

use App\Domain\JobStatus\ValueObject\JobStatusId;

interface JobStatusRepositoryInterface
{
    public function generateId(): JobStatusId;

    public function store(JobStatusEntity $entity): JobStatusId;

    public function update(JobStatusEntity $entity): JobStatusId;
}
