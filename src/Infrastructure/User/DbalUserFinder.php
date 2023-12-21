<?php

declare(strict_types=1);
namespace App\Infrastructure\User;

use App\Domain\User\UserEntity;
use Doctrine\DBAL\Connection;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

final readonly class DbalUserFinder implements UserProviderInterface, PasswordUpgraderInterface
{
    public function __construct(
        private Connection $connection
    ) {}

    public function refreshUser(UserInterface $user): UserInterface
    {
        return $user;
    }

    public function supportsClass(string $class): bool
    {
        return $class === UserEntity::class;
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
}
