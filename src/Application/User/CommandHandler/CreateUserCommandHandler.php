<?php

declare(strict_types=1);

namespace App\Application\User\CommandHandler;

use App\Domain\User\ValueObject\UserId;

class CreateUserCommandHandler
{
    public function __construct(
        public UserId $id
    ) {
    }
}
