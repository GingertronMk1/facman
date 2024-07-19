<?php

declare(strict_types=1);

namespace App\Domain\User;

use App\Domain\User\ValueObject\UserId;

interface UserRepositoryInterface
{
    public function generateId(): UserId;

    public function store(UserEntity $entity): UserId;

    public function update(UserEntity $entity): UserId;
}
