<?php

declare(strict_types=1);

namespace App\Application\User\Command;

use App\Application\Common\Command;
use App\Domain\User\ValueObject\UserId;

class CreateUserCommand 
{
    public function __construct(
        public string $name = '',
        public string $email = '',
        public string $password = '',
        public ?UserId $id = null,
    ) {}
}
