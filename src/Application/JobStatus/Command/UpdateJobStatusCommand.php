<?php

declare(strict_types=1);

namespace App\Application\JobStatus\Command;

use App\Application\Common\CommandInterface;
use App\Application\JobStatus\JobStatusModel;
use App\Domain\JobStatus\ValueObject\JobStatusId;

class UpdateJobStatusCommand implements CommandInterface
{
    private function __construct(
        public JobStatusId $id,
        public string $name,
        public string $description,
        public string $colour
    ) {}

    public static function fromModel(JobStatusModel $model): self
    {
        return new self(
            id: $model->id,
            name: $model->name,
            description: $model->description,
            colour: $model->colour,
        );
    }
}
