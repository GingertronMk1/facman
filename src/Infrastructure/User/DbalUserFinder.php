<?php

declare(strict_types=1);

namespace App\Infrastructure\User;

use App\Application\User\UserFinderException;
use App\Application\User\UserFinderInterface;
use App\Application\User\UserModel;
use App\Domain\Common\ValueObject\DateTime;
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
        $qb = $this->getBaseQuery();
        $qb
            ->andWhere('id = :id')
            ->setParameter('id', (string) $id)
        ;
        $result = $qb->fetchAssociative();

        return $this->createFromRow($result);
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
    private function createFromRow(array|false $row): UserModel
    {
        if (!$row) {
            throw new UserFinderException();
        }

        return new UserModel(
            id: UserId::fromString($row['id']),
            name: $row['name'],
            email: $row['email'],
            password: $row['password'],
            createdAt: DateTime::fromString($row['created_at']),
            updatedAt: DateTime::fromString($row['updated_at']),
        );
    }

    public function refreshUser(UserInterface $user): UserInterface
    {
        return $this->loadUserByIdentifier($user->getUserIdentifier());
    }

    public function supportsClass(string $class): bool
    {
        /*
         * Tells Symfony to use this provider for this User class.
         */
        return UserModel::class === $class || is_subclass_of($class, UserModel::class);
    }

    public function loadUserByIdentifier(string $identifier): UserModel
    {
        $qb = $this->getBaseQuery();
        $qb
            ->andWhere('email = :email')
            ->setParameter('email', $identifier)
        ;
        $result = $qb->fetchAssociative();

        return $this->createFromRow($result);
    }
}
