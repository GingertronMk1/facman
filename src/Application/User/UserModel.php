<?php

declare(strict_types=1);

namespace App\Application\User;

use App\Domain\User\ValueObject\UserId;

readonly class UserModel
{
    public function __construct(
        public UserId $id
    ) {
    }
}
