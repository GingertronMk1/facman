<?php

declare(strict_types=1);

namespace App\Infrastructure\Address;

use App\Application\Address\AddressFinderException;
use App\Application\Address\AddressFinderInterface;
use App\Application\Address\AddressModel;
use App\Domain\Address\AddressTypeEnum;
use App\Domain\Common\ValueObject\AbstractId;
use App\Domain\Common\ValueObject\DateTime;
use App\Infrastructure\Common\AbstractDbalRepository;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Throwable;

readonly class DbalAddressFinder extends AbstractDbalRepository implements AddressFinderInterface
{
    public function __construct(
        private Connection $connection,
    ) {}

    public function find(AbstractId $modelId, string $modelClass): array
    {
        $qb = $this->getBaseQuery();
        $qb->where(
            'addressee_id = :addressee_id',
            'addressee_type = :addressee_type',
        )
            ->setParameters([
                'addressee_id' => (string) $modelId,
                'addressee_type' => $modelClass,
            ])
        ;

        try {
            $results = $qb->fetchAllAssociative();
        } catch (Throwable $e) {
            throw AddressFinderException::errorGettingRows($e);
        }

        return array_map(fn ($row) => $this->createFromRow($row), $results);
    }

    /**
     * @param array<string, mixed> $row
     *
     * @throws AddressFinderException
     */
    private function createFromRow(array $row): AddressModel
    {
        try {
            return new AddressModel(
                addressType: AddressTypeEnum::tryFrom($row['address_type']) ?? AddressTypeEnum::MAIN,
                line1: $row['line1'] ?? '',
                line2: $row['line2'] ?? '',
                line3: $row['line3'] ?? '',
                postcode: $row['postcode'] ?? '',
                city: $row['city'] ?? '',
                country: $row['country'] ?? '',
                createdAt: DateTime::fromString($row['created_at']),
                updatedAt: DateTime::fromString($row['updated_at']),
                deletedAt: is_string($row['deleted_at'] ?? null) ? DateTime::fromString($row['deleted_at']) : null,
            );
        } catch (Throwable $e) {
            throw AddressFinderException::errorCreatingModel($e);
        }
    }

    private function getBaseQuery(): QueryBuilder
    {
        $qb = $this->connection->createQueryBuilder();
        $qb->select('*')->from('addresses');

        return $qb;
    }
}
