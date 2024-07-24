<?php

declare(strict_types=1);

namespace App\Application\User\CommandHandler;

use App\Application\Common\CommandHandlerInterface;
use App\Application\Common\CommandInterface;
use App\Application\Common\Exception\CommandHandlerException;
use App\Application\User\Command\UpdateUserCommand;
use App\Domain\User\UserEntity;
use App\Domain\User\UserRepositoryException;
use App\Domain\User\UserRepositoryInterface;
use App\Domain\User\ValueObject\UserId;

readonly class UpdateUserCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {}

    /**
     * @throws UserRepositoryException
     * @throws CommandHandlerException
     */
    public function handle(CommandInterface $command, mixed ...$args): UserId
    {
        if (!$command instanceof UpdateUserCommand) {
            throw CommandHandlerException::invalidCommandPassed($command);
        }
        $entity = new UserEntity(
            id: $command->id,
            name: $command->name,
            email: $command->email,
            password: $command->password,
        );

        return $this->userRepository->update($entity);
    }
}
