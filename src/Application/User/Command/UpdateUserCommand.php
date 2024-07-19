<?php

declare(strict_types=1);

namespace App\Application\User\Command;

use App\Domain\User\ValueObject\UserId;

class UpdateUserCommand
{
    public function __construct(
        public UserId $id
    ) {
    }
}
