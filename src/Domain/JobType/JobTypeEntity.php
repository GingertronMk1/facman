<?php

declare(strict_types=1);

namespace App\Domain\JobType;

use App\Domain\Common\AbstractMappedEntity;
use App\Domain\JobType\ValueObject\JobTypeId;

class JobTypeEntity extends AbstractMappedEntity
{
    public function __construct(
        public JobTypeId $id,
        public string $name,
        public string $description,
        public string $colour
    ) {}

    public function getIdentifierColumns(): array
    {
        return ['id' => (string) $this->id];
    }
}
