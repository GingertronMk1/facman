<?php

declare(strict_types=1);

namespace App\Domain\JobStatus;

use App\Domain\Common\AbstractMappedEntity;
use App\Domain\JobStatus\ValueObject\JobStatusId;

class JobStatusEntity extends AbstractMappedEntity
{
    public function __construct(
        public JobStatusId $id,
        public string $name,
        public string $description,
        public string $colour
    ) {}

    public function getIdentifierColumns(): array
    {
        return ['id' => (string) $this->id];
    }
}
