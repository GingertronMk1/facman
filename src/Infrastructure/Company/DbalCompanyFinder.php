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
use Throwable;

readonly class DbalCompanyFinder implements CompanyFinderInterface
{
    public function __construct(
        private Connection $connection,
    ) {}

    public function findById(CompanyId $id): CompanyModel
    {
        $qb = $this->getBaseQuery();
        $qb->where('id = :id')->setParameter('id', (string) $id);

        try {
            $result = $qb->fetchAssociative();
        } catch (Throwable $e) {
            throw CompanyFinderException::errorGettingRows($e);
        }

        return $this->createFromRow($result);
    }

    public function all(): array
    {
        try {
            $rows = $this->getBaseQuery()->fetchAllAssociative();
        } catch (Throwable $e) {
            throw CompanyFinderException::errorGettingRows($e);
        }

        return array_map(fn ($row) => $this->createFromRow($row), $rows);
    }

    /**
     * @param array<string, false|mixed> $row
     *
     * @throws CompanyFinderException
     */
    private function createFromRow(array|false $row): CompanyModel
    {
        if (!$row) {
            throw new CompanyFinderException('No rows found');
        }

        try {
            $id = CompanyId::fromString($row['id']);
            $createdAt = DateTime::fromString($row['created_at']);
            $updatedAt = DateTime::fromString($row['updated_at']);
            $deletedAt = is_string($row['deleted_at']) ? DateTime::fromString($row['deleted_at']) : null;
        } catch (Throwable $e) {
            throw CompanyFinderException::errorCreatingModel($e);
        }

        return new CompanyModel(
            $id,
            $row['name'],
            $row['description'],
            $createdAt,
            $updatedAt,
            $deletedAt,
        );
    }

    private function getBaseQuery(): QueryBuilder
    {
        $qb = $this->connection->createQueryBuilder();
        $qb->select('*')->from('companies');

        return $qb;
    }
}
