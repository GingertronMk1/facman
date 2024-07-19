<?php

declare(strict_types=1);

namespace App\Domain\User;

use App\Domain\User\ValueObject\UserId;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

readonly class UserEntity implements PasswordAuthenticatedUserInterface
{
    public function __construct(
        public UserId $id,
        public string $name,
        public string $email,
        public string $password
    ) {
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }
}
