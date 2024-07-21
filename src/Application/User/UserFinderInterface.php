<?php

declare(strict_types=1);

namespace App\Application\User;

use App\Domain\User\ValueObject\UserId;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * @extends UserProviderInterface<UserModel>
 */
interface UserFinderInterface extends UserProviderInterface
{
    /**
     * @throws UserFinderException
     */
    public function findById(UserId $id): UserModel;

    /**
     * @return array<UserModel>
     *
     * @throws UserFinderException
     */
    public function all(): array;
}
