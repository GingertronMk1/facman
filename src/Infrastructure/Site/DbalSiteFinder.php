<?php

declare(strict_types=1);

namespace App\Infrastructure\Site;

use App\Application\Company\CompanyFinderInterface;
use App\Application\Site\SiteFinderException;
use App\Application\Site\SiteFinderInterface;
use App\Application\Site\SiteModel;
use App\Domain\Common\ValueObject\DateTime;
use App\Domain\Company\ValueObject\CompanyId;
use App\Domain\Site\ValueObject\SiteId;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

readonly class DbalSiteFinder implements SiteFinderInterface
{
    public function __construct(
        private Connection $connection,
        private CompanyFinderInterface $companyFinder
    ) {}

    public function findById(SiteId $id): SiteModel
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

    public function allForCompany(CompanyId $companyId): array
    {
        $qb = $this->getBaseQuery();
        $qb
            ->where('company_id = :companyId')
            ->setParameter('companyId', (string) $companyId)
        ;
        $rows = $qb->fetchAllAssociative();

        return array_map(fn ($row) => $this->createFromRow($row), $rows);
    }

    /**
     * @param array<string, mixed>|false $row
     *
     * @throws SiteFinderException
     */
    private function createFromRow(array|false $row): SiteModel
    {
        if (!$row) {
            throw new SiteFinderException('No rows found');
        }

        return new SiteModel(
            SiteId::fromString($row['id']),
            $row['name'],
            $row['description'],
            $this->companyFinder->findById(CompanyId::fromString($row['company_id'])),
            DateTime::fromString($row['created_at']),
            DateTime::fromString($row['updated_at']),
            is_string($row['deleted_at']) ? DateTime::fromString($row['deleted_at']) : null,
        );
    }

    private function getBaseQuery(): QueryBuilder
    {
        $qb = $this->connection->createQueryBuilder();
        $qb->select('*')->from('sites');

        return $qb;
    }
}
