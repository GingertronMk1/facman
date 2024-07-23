<?php

declare(strict_types=1);

namespace App\Application\JobStatus\CommandHandler;

use App\Application\JobStatus\Command\CreateJobStatusCommand;
use App\Domain\JobStatus\JobStatusEntity;
use App\Domain\JobStatus\JobStatusRepositoryInterface;
use App\Domain\JobStatus\ValueObject\JobStatusId;

readonly class CreateJobStatusCommandHandler
{
    public function __construct(
        private JobStatusRepositoryInterface $jobStatusRepositoryInterface,
    ) {}

    public function handle(CreateJobStatusCommand $command): JobStatusId
    {
        $entity = new JobStatusEntity(
            id: $this->jobStatusRepositoryInterface->generateId(),
            name: $command->name,
            description: $command->description,
            colour: $command->colour,
        );

        return $this->jobStatusRepositoryInterface->store($entity);
    }
}
