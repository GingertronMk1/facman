<?php

declare(strict_types=1);

namespace App\Domain\User;

use App\Domain\User\ValueObject\UserId;

interface UserRepositoryInterface
{
    public function generateId(): UserId;

    /**
     * @throws UserRepositoryException
     */
    public function store(UserEntity $entity): UserId;

    /**
     * @throws UserRepositoryException
     */
    public function update(UserEntity $entity): UserId;
}
