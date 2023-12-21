<?php

declare(strict_types=1);

namespace App\Infrastructure\User;

use App\Domain\User\UserEntity;
use App\Domain\User\UserFinderInterface;
use Doctrine\DBAL\Connection;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Uid\Uuid;

final readonly class DbalUserFinder implements UserFinderInterface, UserProviderInterface, PasswordUpgraderInterface
{
    public function __construct(
        private Connection $connection
    ) {
    }

    public function refreshUser(UserInterface $user): UserInterface
    {
        return $user;
    }

    public function supportsClass(string $class): bool
    {
        return UserEntity::class === $class;
    }

    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        $row = $this
            ->connection
            ->createQueryBuilder()
            ->select('*')
            ->from('users', 'u')
            ->where('email = :email')
            ->setParameter('email', $identifier)
            ->executeQuery()
            ->fetchAssociative();

        return new UserEntity($row['id'], $row['email'], [], $row['password']);
    }

    public function upgradePassword(PasswordAuthenticatedUserInterface $user, $newHashedPassword): void
    {
    }

    public function findAll(): array
    {
        $qb = $this->connection->createQueryBuilder();
        $rows = $qb
            ->select('*')
            ->from('users', 'u')
            ->executeQuery()
            ->fetchAllAssociative();
        return array_map(fn ($row) => $this->createUserFromRow($row), $rows);
    }

    public function findById(Uuid $id): UserEntity
    {
        $qb = $this->connection->createQueryBuilder();
        $row = $qb
            ->select('*')
            ->from('users', 'u')
            ->where('id = :id')
            ->setParameter('id', (string) $id)
            ->executeQuery()
            ->fetchAssociative();
        return $this->createUserFromRow($row);
    }

    private function createUserFromRow(array $row): UserEntity
    {
        return new UserEntity(
            $row['id'],
            $row['email'],
            [],
            $row['password'],
        );
    }
}
