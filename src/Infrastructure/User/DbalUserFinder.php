<?php

declare(strict_types=1);

namespace App\Infrastructure\User;

use App\Application\User\UserFinderInterface;
use App\Application\User\UserModel;
use App\Domain\User\ValueObject\UserId;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Symfony\Component\Security\Core\User\UserInterface;

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
        return array_map(fn ($row) => $this->createFromRow($row), $this->getBaseQuery()->fetchAllAssociative());
    }

    private function getBaseQuery(): QueryBuilder
    {
        $qb = $this->connection->createQueryBuilder();
        $qb->select('*')->from('users');

        return $qb;
    }

    /**
     * @param array<string, mixed> $row
     */
    private function createFromRow(array $row): UserModel
    {
        return new UserModel(
            id: UserId::fromString($row['id']),
            email: $row['email'],
            password: $row['password']
        );
    }

    public function refreshUser(UserInterface $user): UserInterface
    {
        // TODO: Implement refreshUser() method.
    }

    public function supportsClass(string $class): bool
    {
        /*
         * Tells Symfony to use this provider for this User class.
         */
        return UserModel::class === $class || is_subclass_of($class, UserModel::class);
    }

    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        // TODO: Implement loadUserByIdentifier() method.
    }
}
