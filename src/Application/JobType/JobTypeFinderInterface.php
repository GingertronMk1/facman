<?php

declare(strict_types=1);

namespace App\Application\JobType;

use App\Domain\JobType\ValueObject\JobTypeId;

interface JobTypeFinderInterface
{
    public function findById(JobTypeId $id): JobTypeModel;

    /**
     * @return array<JobTypeModel>
     */
    public function all(): array;
}
