<?php

declare(strict_types=1);

namespace App\Domain\User;

use App\Domain\User\ValueObject\UserId;

readonly class UserEntity
{
    public function __construct(
        public UserId $id
    ) {
    }
}
