<?php

declare(strict_types=1);

namespace App\Application\User\CommandHandler;

use App\Application\User\Command\CreateUserCommand;
use App\Domain\User\UserEntity;
use App\Domain\User\UserRepositoryInterface;
use App\Domain\User\ValueObject\UserId;

class CreateUserCommandHandler
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository
    ) {
    }

    public function handle(CreateUserCommand $command): UserId
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
