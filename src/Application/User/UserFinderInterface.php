<?php

declare(strict_types=1);

namespace App\Application\User;

use App\Domain\User\ValueObject\UserId;
use Symfony\Component\Security\Core\User\UserProviderInterface;

interface UserFinderInterface extends UserProviderInterface
{
    public function findById(UserId $id): UserModel;

    /**
     * @return array<UserModel>
     */
    public function all(): array;
}
