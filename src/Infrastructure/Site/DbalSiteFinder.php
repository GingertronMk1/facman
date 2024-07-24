<?php

declare(strict_types=1);

namespace App\Infrastructure\Site;

use App\Application\Address\AddressFinderInterface;
use App\Application\Company\CompanyFinderInterface;
use App\Application\Site\SiteFinderException;
use App\Application\Site\SiteFinderInterface;
use App\Application\Site\SiteModel;
use App\Domain\Common\ValueObject\DateTime;
use App\Domain\Company\ValueObject\CompanyId;
use App\Domain\Site\ValueObject\SiteId;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Throwable;

readonly class DbalSiteFinder implements SiteFinderInterface
{
    public function __construct(
        private Connection $connection,
        private CompanyFinderInterface $companyFinder,
        private AddressFinderInterface $addressFinder
    ) {}

    public function findById(SiteId $id): SiteModel
    {
        $qb = $this->getBaseQuery();
        $qb->where('id = :id')->setParameter('id', (string) $id);

        try {
            $result = $qb->fetchAssociative();
        } catch (Throwable $e) {
            throw SiteFinderException::errorGettingRows($e);
        }

        return $this->createFromRow($result);
    }

    public function all(): array
    {
        $qb = $this->getBaseQuery();

        try {
            $rows = $qb->fetchAllAssociative();
        } catch (Throwable $e) {
            throw SiteFinderException::errorGettingRows($e);
        }

        return array_map(fn ($row) => $this->createFromRow($row), $rows);
    }

    public function allForCompany(CompanyId $companyId): array
    {
        $qb = $this->getBaseQuery();
        $qb
            ->where('company_id = :companyId')
            ->setParameter('companyId', (string) $companyId)
        ;

        try {
            $rows = $qb->fetchAllAssociative();
        } catch (Throwable $e) {
            throw SiteFinderException::errorGettingRows($e);
        }

        return array_map(fn ($row) => $this->createFromRow($row), $rows);
    }

    /**
     * @param array<string, mixed>|false $row
     *
     * @throws SiteFinderException
     */
    private function createFromRow(array|false $row): SiteModel
    {
        if (!is_array($row)) {
            throw new SiteFinderException('No rows found');
        }

        try {
            $id = SiteId::fromString($row['id']);
            $createdAt = DateTime::fromString($row['created_at']);
            $updatedAt = DateTime::fromString($row['updated_at']);
            $deletedAt = null;
            if (is_string($row['deleted_at'])) {
                $deletedAt = DateTime::fromString($row['deleted_at']);
            }

            $company = $this->companyFinder->findById(CompanyId::fromString($row['company_id']));
        } catch (Throwable $e) {
            throw SiteFinderException::errorCreatingModel($e);
        }

        $addresses = [];

        try {
            $addresses = $this->addressFinder->find($id, SiteModel::class);
        } catch (Throwable $e) {
            // ignore
        }

        return new SiteModel(
            id: $id,
            name: $row['name'],
            description: $row['description'],
            company: $company,
            addresses: $addresses,
            createdAt: $createdAt,
            updatedAt: $updatedAt,
            deletedAt: $deletedAt
        );
    }

    private function getBaseQuery(): QueryBuilder
    {
        $qb = $this->connection->createQueryBuilder();
        $qb->select('*')->from('sites');

        return $qb;
    }
}
