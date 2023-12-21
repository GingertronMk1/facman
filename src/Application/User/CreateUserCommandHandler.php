<?php

declare(strict_types=1);

namespace App\Application\User;

final readonly class CreateUserCommandHandler
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {
    }

    public function handle(CreateUserCommand $command): void
    {
        $this->userRepository->createUser($command->email, $command->password);
    }
}
