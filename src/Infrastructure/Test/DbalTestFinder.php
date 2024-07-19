<?php

declare(strict_types=1);

namespace App\Infrastructure\Test;

readonly class DbalTestFinder implements \App\Application\Test\TestFinderInterface
{
    public function __construct(
        private \Doctrine\DBAL\Connection $connection,
    ) {
    }
}
