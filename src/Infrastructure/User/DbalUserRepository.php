<?php

declare(strict_types=1);

namespace App\Infrastructure\User;

use Exception;
use App\Application\User\UserRepositoryInterface;
use App\Domain\User\UserEntity;
use Doctrine\DBAL\Connection;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Uid\Uuid;

final readonly class DbalUserRepository implements UserRepositoryInterface
{
    public function __construct(
        private Connection $connection,
        private UserPasswordHasherInterface $passwordHasher
    ) {
    }

    public function getNextId(): Uuid
    {
        return Uuid::v7();
    }

    public function createUser(string $email, string $password): void
    {
        try {
            $this->connection->transactional(function (Connection $conn) use ($email, $password) {
                $user = new UserEntity(
                    (string) $this->getNextId(),
                    $email,
                    [],
                    $password
                );
                $queryBuilder = $conn->createQueryBuilder();
                $queryBuilder
                    ->insert('users')
                    ->values([
                        'id' => ':id',
                        'email' => ':email',
                        'password' => ':password',
                    ])
                    ->setParameters([
                        'id' => $user->getId(),
                        'email' => $user->getEmail(),
                        'password' => $this->passwordHasher->hashPassword($user, $user->getPassword()),
                    ])
                    ->executeQuery()
                ;
            });
        } catch (Exception $e) {
            throw $e;
        }
    }
}
