<?php

declare(strict_types=1);

namespace App\Infrastructure\Address;

use App\Application\Common\ClockInterface;
use App\Domain\Address\AddressEntity;
use App\Domain\Address\AddressRepositoryException;
use App\Domain\Address\AddressRepositoryInterface;
use App\Domain\Common\Exception\AbstractRepositoryException;
use App\Infrastructure\Common\AbstractDbalRepository;
use Doctrine\DBAL\Connection;
use InvalidArgumentException;

readonly class DbalAddressRepository extends AbstractDbalRepository implements AddressRepositoryInterface
{
    private const TABLE = 'addresses';

    public function __construct(
        private Connection $connection,
        private ClockInterface $clockInterface,
    ) {}

    /**
     * @throws AbstractRepositoryException
     * @throws InvalidArgumentException
     */
    public function store(AddressEntity $addressEntity): void
    {
        $qb = $this->connection->createQueryBuilder();
        $qb->insert(self::TABLE)
            ->values([
                'addressee_id' => ':addresseeId',
                'addressee_type' => ':addresseeType',
                'address_type' => ':addressType',
                'line1' => ':line1',
                'line2' => ':line2',
                'line3' => ':line3',
                'postcode' => ':postcode',
                'city' => ':city',
                'country' => ':country',
                'created_at' => ':now',
                'updated_at' => ':now',
            ])
            ->setParameters([
                'addresseeId' => $addressEntity->addresseeId,
                'addresseeType' => $addressEntity->addresseeType,
                'addressType' => $addressEntity->addressType->value,
                'line1' => $addressEntity->line1,
                'line2' => $addressEntity->line2,
                'line3' => $addressEntity->line3,
                'postcode' => $addressEntity->postcode,
                'city' => $addressEntity->city,
                'country' => $addressEntity->country,
                'now' => (string) $this->clockInterface->getTime(),
            ])
        ;
        $this->executeAndCheck($qb, AddressRepositoryException::class);
    }

    /**
     * @throws AbstractRepositoryException
     * @throws InvalidArgumentException
     */
    public function update(AddressEntity $addressEntity): void
    {
        $qb = $this->connection->createQueryBuilder();
        $qb->update(self::TABLE)
            ->where([
                'addressee_id' => ':addresseeId',
                'addressee_type' => ':addresseeType',
                'address_type' => ':addressType',
            ])
            ->set('line1', ':line1')
            ->set('line2', ':line2')
            ->set('line3', ':line3')
            ->set('postcode', ':postcode')
            ->set('city', ':city')
            ->set('country', ':country')
            ->set('updated_at', ':now')
            ->setParameters([
                'addresseeId' => $addressEntity->addresseeId,
                'addresseeType' => $addressEntity->addresseeType,
                'addressType' => $addressEntity->addressType->value,
                'line1' => $addressEntity->line1,
                'line2' => $addressEntity->line2,
                'line3' => $addressEntity->line3,
                'postcode' => $addressEntity->postcode,
                'city' => $addressEntity->city,
                'country' => $addressEntity->country,
                'now' => (string) $this->clockInterface->getTime(),
            ])
        ;
        $this->executeAndCheck($qb, AddressRepositoryException::class);
    }
}
