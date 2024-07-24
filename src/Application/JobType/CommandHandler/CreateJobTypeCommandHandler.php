<?php

declare(strict_types=1);

namespace App\Application\JobType\CommandHandler;

use App\Application\JobType\Command\CreateJobTypeCommand;
use App\Domain\JobType\JobTypeEntity;
use App\Domain\JobType\JobTypeRepositoryInterface;
use App\Domain\JobType\ValueObject\JobTypeId;

readonly class CreateJobTypeCommandHandler
{
    public function __construct(
        private JobTypeRepositoryInterface $jobTypeRepositoryInterface,
    ) {}

    public function handle(CreateJobTypeCommand $command): JobTypeId
    {
        $entity = new JobTypeEntity(
            id: $this->jobTypeRepositoryInterface->generateId(),
            name: $command->name,
            description: $command->description ?? '',
            colour: $command->colour
        );

        return $this->jobTypeRepositoryInterface->store($entity);
    }
}
