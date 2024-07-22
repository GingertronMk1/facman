<?php

declare(strict_types=1);

namespace App\Infrastructure\Building;

use App\Application\Address\AddressFinderInterface;
use App\Application\Building\BuildingFinderException;
use App\Application\Building\BuildingFinderInterface;
use App\Application\Building\BuildingModel;
use App\Application\Site\SiteFinderInterface;
use App\Domain\Building\ValueObject\BuildingId;
use App\Domain\Common\ValueObject\DateTime;
use App\Domain\Site\ValueObject\SiteId;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Throwable;

readonly class DbalBuildingFinder implements BuildingFinderInterface
{
    public function __construct(
        private Connection $connection,
        private SiteFinderInterface $siteFinder,
        private AddressFinderInterface $addressFinder
    ) {}

    public function findById(BuildingId $id): BuildingModel
    {
        $qb = $this->getBaseQuery();
        $qb->where('id = :id')
            ->setParameter('id', (string) $id)
        ;

        try {
            $result = $qb->fetchAssociative();
        } catch (Throwable $e) {
            throw BuildingFinderException::errorGettingRows($e);
        }

        return $this->createFromRow($result);
    }

    public function all(): array
    {
        $qb = $this->getBaseQuery();

        try {
            $rows = $qb->fetchAllAssociative();
        } catch (Throwable $e) {
            throw BuildingFinderException::errorGettingRows($e);
        }

        return array_map(fn ($row) => $this->createFromRow($row), $rows);
    }

    /**
     * @param array<string, mixed>|false $row
     *
     * @throws BuildingFinderException
     */
    private function createFromRow(array|false $row): BuildingModel
    {
        try {
            if (!$row) {
                throw new BuildingFinderException('No rows found');
            }

            $id = BuildingId::fromString($row['id']);
            $createdAt = DateTime::fromString($row['created_at']);
            $updatedAt = DateTime::fromString($row['updated_at']);
            $deletedAt = null;
            if (is_string($row['deleted_at'])) {
                $deletedAt = DateTime::fromString($row['deleted_at']);
            }

            $site = $this->siteFinder->findById(SiteId::fromString($row['site_id']));

            return new BuildingModel(
                id: $id,
                name: $row['name'],
                description: $row['description'],
                site: $site,
                addresses: $this->addressFinder->find($id, BuildingModel::class),
                createdAt: $createdAt,
                updatedAt: $updatedAt,
                deletedAt: $deletedAt
            );
        } catch (Throwable $e) {
            throw BuildingFinderException::errorCreatingModel($e);
        }
    }

    private function getBaseQuery(): QueryBuilder
    {
        $qb = $this->connection->createQueryBuilder();
        $qb->select('*')->from('buildings');

        return $qb;
    }
}
