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
use InvalidArgumentException;
use Symfony\Component\Security\Core\User\UserInterface;
use Throwable;

readonly class DbalUserFinder implements UserFinderInterface
{
    public function __construct(
        private Connection $connection
    ) {}

    public function findById(UserId $id): UserModel
    {
        $qb = $this->getBaseQuery();
        $qb
            ->andWhere('id = :id')
            ->setParameter('id', (string) $id)
        ;

        try {
            $result = $qb->fetchAssociative();
        } catch (Throwable $e) {
            throw UserFinderException::errorGettingRows($e);
        }

        return $this->createFromRow($result);
    }

    /**
     * @return array<UserModel>
     *
     * @throws UserFinderException
     */
    public function all(): array
    {
        try {
            $rows = $this->getBaseQuery()->fetchAllAssociative();
        } catch (Throwable $e) {
            throw UserFinderException::errorGettingRows($e);
        }

        return array_map(fn ($row) => $this->createFromRow($row), $rows);
    }

    /**
     * @throws UserFinderException
     */
    public function refreshUser(UserInterface $user): UserInterface
    {
        return $this->loadUserByIdentifier($user->getUserIdentifier());
    }

    public function supportsClass(string $class): bool
    {
        // Tells Symfony to use this provider for this User class.
        return UserModel::class === $class || is_subclass_of($class, UserModel::class);
    }

    /**
     * @throws UserFinderException
     */
    public function loadUserByIdentifier(string $identifier): UserModel
    {
        $qb = $this->getBaseQuery();
        $qb
            ->andWhere('email = :email')
            ->setParameter('email', $identifier)
        ;

        try {
            $result = $qb->fetchAssociative();
        } catch (Throwable $e) {
            throw UserFinderException::errorGettingRows($e);
        }

        return $this->createFromRow($result);
    }

    private function getBaseQuery(): QueryBuilder
    {
        $qb = $this->connection->createQueryBuilder();
        $qb->select('*')->from('users');

        return $qb;
    }

    /**
     * @param array<string, false|mixed> $row
     *
     * @throws UserFinderException
     */
    private function createFromRow(array|false $row): UserModel
    {
        if (!is_array($row)) {
            throw UserFinderException::notFound();
        }

        try {
            $id = UserId::fromString($row['id']);
            $createdAt = DateTime::fromString($row['created_at']);
            $updatedAt = DateTime::fromString($row['updated_at']);

            $deletedAt = null;
            if (is_string($row['deleted_at'])) {
                $deletedAt = DateTime::fromString($row['deleted_at']);
            }
        } catch (InvalidArgumentException $e) {
            throw UserFinderException::invalidId($e);
        }

        return new UserModel(
            id: $id,
            name: $row['name'],
            email: $row['email'],
            password: $row['password'],
            createdAt: $createdAt,
            updatedAt: $updatedAt,
            deletedAt: $deletedAt
        );
    }
}
