<?php

declare(strict_types=1);

use Doctrine\Dbal\Connection;

class DbalUserFinder
{
    public function __construct(
        Connection $conn
    ) {
    }
}
