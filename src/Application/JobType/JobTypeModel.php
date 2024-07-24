<?php

declare(strict_types=1);

namespace App\Application\JobType;

use App\Domain\Common\ValueObject\DateTime;
use App\Domain\JobType\ValueObject\JobTypeId;

readonly class JobTypeModel
{
    public function __construct(
        public JobTypeId $id,
        public string $name,
        public string $description,
        public string $colour,
        public DateTime $createdAt,
        public DateTime $updatedAt,
        public ?DateTime $deletedAt,
    ) {}
}
