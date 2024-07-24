<?php

declare(strict_types=1);

namespace App\Application\JobStatus\CommandHandler;

use App\Application\Common\CommandHandlerInterface;
use App\Application\Common\CommandInterface;
use App\Application\Common\Exception\CommandHandlerException;
use App\Application\JobStatus\Command\CreateJobStatusCommand;
use App\Domain\JobStatus\JobStatusEntity;
use App\Domain\JobStatus\JobStatusRepositoryInterface;
use App\Domain\JobStatus\ValueObject\JobStatusId;

readonly class CreateJobStatusCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private JobStatusRepositoryInterface $jobStatusRepositoryInterface,
    ) {}

    public function handle(CommandInterface $command, mixed ...$args): JobStatusId
    {
        if (!$command instanceof CreateJobStatusCommand) {
            throw CommandHandlerException::invalidCommandPassed($command);
        }
        $entity = new JobStatusEntity(
            id: $this->jobStatusRepositoryInterface->generateId(),
            name: $command->name,
            description: $command->description ?? '',
            colour: $command->colour,
        );

        return $this->jobStatusRepositoryInterface->store($entity);
    }
}
