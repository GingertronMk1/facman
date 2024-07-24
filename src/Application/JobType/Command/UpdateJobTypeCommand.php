<?php

declare(strict_types=1);

namespace App\Application\JobType\Command;

use App\Application\Common\CommandInterface;
use App\Application\JobType\JobTypeModel;
use App\Domain\JobType\ValueObject\JobTypeId;

class UpdateJobTypeCommand implements CommandInterface
{
    private function __construct(
        public JobTypeId $id,
        public string $name,
        public ?string $description,
        public string $colour,
    ) {}

    public static function fromModel(JobTypeModel $model): self
    {
        return new self(
            id: $model->id,
            name: $model->name,
            description: $model->description,
            colour: $model->colour,
        );
    }
}
