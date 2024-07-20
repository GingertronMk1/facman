<?php

declare(strict_types=1);

namespace App\Application\User\Command;

use App\Application\Common\AbstractCommand;
use App\Domain\User\ValueObject\UserId;

class CreateUserAbstractCommand extends AbstractCommand
{
    public function __construct(
        public string $name = '',
        public string $email = '',
        public string $password = '',
        public ?UserId $id = null,
    ) {}
}
