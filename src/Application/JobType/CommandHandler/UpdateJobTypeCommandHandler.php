<?php

declare(strict_types=1);

namespace App\Application\JobType\CommandHandler;

use App\Application\Common\CommandHandlerInterface;
use App\Application\Common\CommandInterface;
use App\Application\Common\Exception\CommandHandlerException;
use App\Application\JobType\Command\UpdateJobTypeCommand;
use App\Domain\Common\ValueObject\AbstractId;
use App\Domain\JobType\JobTypeRepositoryInterface;

readonly class UpdateJobTypeCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private JobTypeRepositoryInterface $jobTypeRepositoryInterface,
    ) {}

    public function handle(CommandInterface $command, mixed ...$args): ?AbstractId
    {
        if (!$command instanceof UpdateJobTypeCommand) {
            throw CommandHandlerException::invalidCommandPassed($command);
        }

        return null;
    }
}
