<?php

declare(strict_types=1);

namespace App\Infrastructure\User;

use App\Application\Common\ClockInterface;
use App\Domain\User\UserEntity;
use App\Domain\User\UserRepositoryException;
use App\Domain\User\UserRepositoryInterface;
use App\Domain\User\ValueObject\UserId;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Throwable;

readonly class DbalUserRepository implements UserRepositoryInterface
{
    private const TABLE = 'users';

    public function __construct(
        private Connection $connection,
        private UserPasswordHasherInterface $hasher,
        private ClockInterface $clock
    ) {}

    public function generateId(): UserId
    {
        return UserId::generate();
    }

    public function store(UserEntity $entity): UserId
    {
        $qb = $this->connection->createQueryBuilder();
        $qb->insert(self::TABLE)->values([
            'id' => ':id',
            'name' => ':name',
            'email' => ':email',
            'password' => ':password',
            'created_at' => ':now',
            'updated_at' => ':now',
        ])
            ->setParameters([
                'id' => (string) $entity->id,
                'name' => $entity->name,
                'email' => $entity->email,
                'password' => $this->hasher->hashPassword($entity, $entity->password),
                'now' => (string) $this->clock->getTime(),
            ])
        ;

        $this->executeAndCheck($qb);

        return $entity->id;
    }

    public function update(UserEntity $entity): UserId
    {
        $qb = $this->connection->createQueryBuilder();
        $qb->update(self::TABLE)
            ->where('id = :id')
            ->values([
                'name' => ':name',
                'email' => ':email',
                'password' => ':password',
                'created_at' => ':now',
                'updated_at' => ':now',
            ])
            ->setParameters([
                'id' => (string) $entity->id,
                'name' => $entity->name,
                'email' => $entity->email,
                'password' => $this->hasher->hashPassword($entity, $entity->password),
                'now' => (string) $this->clock->getTime(),
            ])
        ;

        $this->executeAndCheck($qb);

        return $entity->id;
    }

    /**
     * @throws UserRepositoryException
     */
    private function executeAndCheck(QueryBuilder $qb): void
    {
        try {
            $rowsAffected = $qb->executeStatement();
        } catch (Throwable $e) {
            throw UserRepositoryException::errorUpdatingRows(previous: $e);
        }

        if (1 !== $rowsAffected) {
            throw UserRepositoryException::wrongNumberOfRows($rowsAffected);
        }
    }
}
