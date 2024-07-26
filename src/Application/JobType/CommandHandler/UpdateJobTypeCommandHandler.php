<?php

declare(strict_types=1);

namespace App\Application\JobType\CommandHandler;

use App\Application\Common\CommandHandlerInterface;
use App\Application\JobType\Command\UpdateJobTypeCommand;
use App\Domain\Common\ValueObject\AbstractId;
use App\Domain\JobType\JobTypeEntity;
use App\Domain\JobType\JobTypeRepositoryInterface;

/**
 * @implements CommandHandlerInterface<UpdateJobTypeCommand>
 */
readonly class UpdateJobTypeCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private JobTypeRepositoryInterface $jobTypeRepositoryInterface,
    ) {}

    public function handle(mixed $command, mixed ...$args): ?AbstractId
    {
        $entity = new JobTypeEntity(
            id: $command->id,
            name: $command->name,
            description: $command->description ?? '',
            colour: $command->colour,
        );

        return $this->jobTypeRepositoryInterface->update($entity);
    }
}
