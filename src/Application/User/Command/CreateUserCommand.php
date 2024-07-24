<?php

declare(strict_types=1);

namespace App\Application\User\Command;

use App\Application\Common\CommandInterface;
use App\Domain\User\ValueObject\UserId;

class CreateUserCommand implements CommandInterface
{
    public function __construct(
        public string $name = '',
        public string $email = '',
        public string $password = '',
        public ?UserId $id = null,
    ) {}
}
