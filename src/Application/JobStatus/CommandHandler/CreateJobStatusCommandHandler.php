<?php

declare(strict_types=1);

namespace App\Application\JobStatus\CommandHandler;

use App\Application\Common\CommandHandlerInterface;
use App\Application\JobStatus\Command\CreateJobStatusCommand;
use App\Domain\JobStatus\JobStatusEntity;
use App\Domain\JobStatus\JobStatusRepositoryInterface;
use App\Domain\JobStatus\ValueObject\JobStatusId;

/**
 * @implements CommandHandlerInterface<CreateJobStatusCommand>
 */
readonly class CreateJobStatusCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private JobStatusRepositoryInterface $jobStatusRepositoryInterface,
    ) {}

    public function handle(mixed $command, mixed ...$args): JobStatusId
    {
        $entity = new JobStatusEntity(
            id: $this->jobStatusRepositoryInterface->generateId(),
            name: $command->name,
            description: $command->description ?? '',
            colour: $command->colour,
        );

        return $this->jobStatusRepositoryInterface->store($entity);
    }
}
