<?php

declare(strict_types=1);

namespace App\Infrastructure\Test;

readonly class DbalTestRepository implements \App\Domain\Test\TestRepositoryInterface
{
    public function __construct(
        private \Doctrine\DBAL\Connection $connection,
    ) {
    }
}
