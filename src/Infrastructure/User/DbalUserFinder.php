<?php

declare(strict_types=1);

namespace App\Infrastructure\User;

use App\Application\User\UserFinderInterface;
use App\Application\User\UserModel;
use App\Domain\User\ValueObject\UserId;
use Doctrine\Dbal\Connection;

readonly class DbalUserFinder implements UserFinderInterface
{
    public function __construct(
        private Connection $connection
    ) {
    }

    public function findById(UserId $id): UserModel
    {
    }

    /**
     * @return array<UserModel>
     */
    public function all(): array
    {
        return [];
    }

    /**
     * @param array<string, mixed> $row
     */
    private function createFromRow(array $row): UserModel
    {
        return new UserModel(
            id: UserId::fromString($row['id'])
        );
    }
}
