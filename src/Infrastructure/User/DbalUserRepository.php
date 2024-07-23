<?php

declare(strict_types=1);

namespace App\Infrastructure\User;

use App\Application\Common\ClockInterface;
use App\Domain\Common\Exception\AbstractRepositoryException;
use App\Domain\User\UserEntity;
use App\Domain\User\UserRepositoryException;
use App\Domain\User\UserRepositoryInterface;
use App\Domain\User\ValueObject\UserId;
use App\Infrastructure\Common\AbstractDbalRepository;
use Doctrine\DBAL\Connection;
use InvalidArgumentException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class DbalUserRepository extends AbstractDbalRepository implements UserRepositoryInterface
{
    private const TABLE = 'users';

    public function __construct(
        private readonly UserPasswordHasherInterface $hasher,
        Connection $connection,
        ClockInterface $clock
    ) {
        parent::__construct($connection, $clock);
    }

    public function generateId(): UserId
    {
        return UserId::generate();
    }

    /**
     * @throws AbstractRepositoryException
     * @throws InvalidArgumentException
     */
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

        $this->executeAndCheck($qb, UserRepositoryException::class);

        return $entity->id;
    }

    /**
     * @throws AbstractRepositoryException
     * @throws InvalidArgumentException
     */
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

        $this->executeAndCheck($qb, UserRepositoryException::class);

        return $entity->id;
    }
}
