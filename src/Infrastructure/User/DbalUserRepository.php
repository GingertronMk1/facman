<?php

declare(strict_types=1);

namespace App\Infrastructure\User;

use App\Domain\User\UserRepositoryInterface;
use Doctrine\Dbal\Connection;

readonly class DbalUserRepository implements UserRepositoryInterface
{
    public function __construct(
        private Connection $connection
    ) {
    }
}
