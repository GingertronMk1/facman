<?php

declare(strict_types=1);

namespace App\Application\User;

use App\Domain\Common\ValueObject\DateTime;
use App\Domain\User\ValueObject\UserId;

readonly class UserModel
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
}
