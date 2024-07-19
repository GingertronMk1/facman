<?php

declare(strict_types=1);

namespace App\Application\User;

use App\Domain\Common\ValueObject\DateTime;
use App\Domain\User\ValueObject\UserId;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

readonly class UserModel implements UserInterface, PasswordAuthenticatedUserInterface
{
    public function __construct(
        public UserId $id,
        public string $name,
        public string $email,
        public string $password,
        public DateTime $createdAt,
        public DateTime $updatedAt,
    ) {
    }

    public function getRoles(): array
    {
        return [];
    }

    public function eraseCredentials(): void
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }
}
