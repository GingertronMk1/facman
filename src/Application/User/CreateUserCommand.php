<?php

declare(strict_types=1);

namespace App\Application\User;

class CreateUserCommand
{
    public function __construct(
        public string $email = '',
        public string $password = '',
    ) {
    }
}
