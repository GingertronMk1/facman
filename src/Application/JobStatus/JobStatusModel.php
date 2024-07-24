<?php

declare(strict_types=1);

namespace App\Application\JobStatus;

use App\Domain\Common\ValueObject\DateTime;
use App\Domain\JobStatus\ValueObject\JobStatusId;

readonly class JobStatusModel
{
    public function __construct(
        public JobStatusId $id,
        public string $name,
        public string $description,
        public string $colour,
        public DateTime $createdAt,
        public DateTime $updatedAt,
        public ?DateTime $deletedAt = null,
    ) {}
}
