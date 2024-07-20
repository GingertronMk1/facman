<?php

declare(strict_types=1);

namespace App\Infrastructure\Company;

use App\Application\Company\CompanyFinderException;
use App\Application\Company\CompanyFinderInterface;
use App\Application\Company\CompanyModel;
use App\Domain\Common\ValueObject\DateTime;
use App\Domain\Company\ValueObject\CompanyId;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

readonly class DbalCompanyFinder implements CompanyFinderInterface
{
    public function __construct(
        private Connection $connection,
    ) {}

    public function findById(CompanyId $id): CompanyModel
    {
        $qb = $this->getBaseQuery();
        $qb->where('id = :id')->setParameter('id', (string) $id);
        $result = $qb->fetchAssociative();

        return $this->createFromRow($result);
    }

    public function all(): array
    {
        $qb = $this->getBaseQuery();
        $rows = $qb->fetchAllAssociative();

        return array_map(fn ($row) => $this->createFromRow($row), $rows);
    }

    /**
     * @param array<string, mixed>|false $row
     *
     * @throws CompanyFinderException
     */
    private function createFromRow(array|false $row): CompanyModel
    {
        if (!$row) {
            throw new CompanyFinderException('No rows found');
        }

        return new CompanyModel(
            CompanyId::fromString($row['id']),
            $row['name'],
            $row['description'],
            DateTime::fromString($row['created_at']),
            DateTime::fromString($row['updated_at']),
            is_string($row['deleted_at']) ? DateTime::fromString($row['deleted_at']) : null,
        );
    }

    private function getBaseQuery(): QueryBuilder
    {
        $qb = $this->connection->createQueryBuilder();
        $qb->select('*')->from('companies');

        return $qb;
    }
}
