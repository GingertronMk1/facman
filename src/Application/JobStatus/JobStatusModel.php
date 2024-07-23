<?php

declare(strict_types=1);

namespace App\Application\JobStatus;

use App\Domain\Common\ValueObject\DateTime;
use App\Domain\JobStatus\ValueObject\JobStatusId;
use JsonSerializable;

readonly class JobStatusModel implements JsonSerializable
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

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'colour' => $this->colour,
            'createdAt' => $this->createdAt,
            'updatedAt' => $this->updatedAt,
            'deletedAt' => $this->deletedAt,
        ];
    }
}
