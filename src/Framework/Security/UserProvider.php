<?php

namespace App\Framework\Security;

use App\Domain\User\UserEntity;
use App\Domain\User\ValueObject\UserId;
use Doctrine\DBAL\Connection;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

readonly class UserProvider implements UserProviderInterface
{
    public function __construct(
        private readonly Connection $connection
    ) {
    }

    public function refreshUser(UserInterface $user): UserInterface
    {
        return $this->loadUserByIdentifier($user->getUserIdentifier());
    }

    public function supportsClass(string $class): bool
    {
        return UserEntity::class === $class || is_subclass_of($class, UserEntity::class);
    }

    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        $qb = $this->connection->createQueryBuilder();
        $qb
            ->select('*')
            ->from('users')
            ->where('email = :email')
            ->setParameter('email', $identifier);

        $result = $qb->fetchAssociative();

        if (!$result) {
            throw new UserNotFoundException();
        }

        return new UserEntity(
            id: UserId::fromString($result['id']),
            name: $result['name'],
            email: $result['email'],
            password: $result['password'],
        );
    }
}
