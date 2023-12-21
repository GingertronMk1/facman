<?php

declare(strict_types=1);

namespace App\Application\User;

use Symfony\Component\Uid\Uuid;

interface UserRepositoryInterface
{
    public function getNextId(): Uuid;

    public function createUser(string $email, string $password): void;
}
