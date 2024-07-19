<?php

declare(strict_types=1);

namespace App\Application\User\CommandHandler;

use App\Domain\User\ValueObject\UserId;

class UpdateUserCommandHandler
{
    public function __construct(
        public UserId $id
    ) {
    }

    public static function fromModel(UserModel $model): self
    {
        return new self(
            id: $model->id,
        );
    }
}
