<?php

declare(strict_types=1);

namespace App\Application\User\CommandHandler;

use App\Application\Common\CommandHandlerInterface;
use App\Application\User\Command\CreateUserCommand;
use App\Domain\User\UserEntity;
use App\Domain\User\UserRepositoryException;
use App\Domain\User\UserRepositoryInterface;
use App\Domain\User\ValueObject\UserId;

/**
 * @implements CommandHandlerInterface<CreateUserCommand>
 */
readonly class CreateUserCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {}

    /**
     * @throws UserRepositoryException
     */
    public function handle(mixed $command, mixed ...$args): UserId
    {
        $userEntity = new UserEntity(
            id: $command->id ?? $this->userRepository->generateId(),
            name: $command->name,
            email: $command->email,
            password: $command->password,
        );

        return $this->userRepository->store($userEntity);
    }
}
