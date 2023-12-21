<?php

declare(strict_types=1);

namespace App\Domain\User;

use Symfony\Component\Uid\Uuid;

interface UserFinderInterface
{
    public function findAll(): array;
    public function findById(Uuid $id): UserEntity;
}
