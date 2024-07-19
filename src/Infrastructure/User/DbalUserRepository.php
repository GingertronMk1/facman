<?php

declare(strict_types=1);

namespace App\Infrastructure\User;

use App\Domain\User\UserEntity;
use App\Domain\User\UserRepositoryInterface;
use App\Domain\User\ValueObject\UserId;
use Doctrine\Dbal\Connection;

readonly class DbalUserRepository implements UserRepositoryInterface
{
    public function __construct(
        private Connection $connection
    ) {
    }

    public function generateId(): UserId
    {
        return UserId::generate();
    }

    public function store(UserEntity $entity): UserId
    {
        return $entity->id;
    }

    public function update(UserEntity $entity): UserId
    {
        return $entity->id;
    }
}
