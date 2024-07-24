<?php

declare(strict_types=1);

namespace App\Application\JobStatus\CommandHandler;

use App\Application\Common\CommandHandlerInterface;
use App\Application\Common\CommandInterface;
use App\Domain\JobStatus\JobStatusEntity;
use App\Domain\JobStatus\JobStatusRepositoryInterface;
use App\Domain\JobStatus\ValueObject\JobStatusId;

readonly class UpdateJobStatusCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private JobStatusRepositoryInterface $jobStatusRepositoryInterface,
    ) {}

    public function handle(CommandInterface $command, mixed ...$args): JobStatusId
    {
        $entity = new JobStatusEntity(
            id: $command->id,
            name: $command->name,
            description: $command->description,
            colour: $command->colour,
        );

        return $this->jobStatusRepositoryInterface->update($entity);
    }
}
