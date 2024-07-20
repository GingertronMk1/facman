<?php

declare(strict_types=1);

namespace App\Application\User\Command;

use App\Application\Common\AbstractCommand;
use App\Application\User\UserModel;
use App\Domain\User\ValueObject\UserId;

class UpdateUserAbstractCommand extends AbstractCommand
{
    private function __construct(
        public UserId $id,
        public string $name,
        public string $email,
        public string $password,
    ) {}

    public static function fromModel(UserModel $userModel): self
    {
        return new self(
            id: $userModel->id,
            name: $userModel->name,
            email: $userModel->email,
            password: $userModel->password,
        );
    }
}
