<?php

declare(strict_types=1);

namespace App\Application\JobType\CommandHandler;

use App\Application\Common\CommandHandlerInterface;
use App\Application\Common\CommandInterface;
use App\Domain\Common\ValueObject\AbstractId;
use App\Domain\JobType\JobTypeRepositoryInterface;

readonly class UpdateJobTypeCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private JobTypeRepositoryInterface $jobTypeRepositoryInterface,
    ) {}

    public function handle(CommandInterface $command, ...$args): ?AbstractId
    {
        return null;
    }
}
