<?php

declare(strict_types=1);

namespace App\Application\User\CommandHandler;

use App\Application\Common\CommandHandlerInterface;
use App\Application\User\Command\UpdateUserCommand;
use App\Domain\User\UserEntity;
use App\Domain\User\UserRepositoryException;
use App\Domain\User\UserRepositoryInterface;
use App\Domain\User\ValueObject\UserId;

/**
 * @implements CommandHandlerInterface<UpdateUserCommand>
 */
readonly class UpdateUserCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {}

    /**
     * @throws UserRepositoryException
     */
    public function handle(mixed $command, mixed ...$args): UserId
    {
        $entity = new UserEntity(
            id: $command->id,
            name: $command->name,
            email: $command->email,
            password: $command->password,
        );

        return $this->userRepository->update($entity);
    }
}
