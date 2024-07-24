<?php

declare(strict_types=1);

namespace App\Domain\JobType;

use App\Domain\JobType\ValueObject\JobTypeId;

interface JobTypeRepositoryInterface
{
    public function generateId(): JobTypeId;

    public function store(JobTypeEntity $entity): JobTypeId;

    public function update(JobTypeEntity $entity): JobTypeId;
}
