<?php

namespace App\Infrastructure\Common;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

abstract class AbstractDbalFinder
{
    public function __construct(
        protected readonly Connection $connection
    ) {}

    protected function getBaseQuery(): QueryBuilder
    {
        $qb = $this->connection->createQueryBuilder();
        $qb->select('*')->from($this->getTableName());

        return $qb;
    }

    abstract protected function getTableName(): string;
}
