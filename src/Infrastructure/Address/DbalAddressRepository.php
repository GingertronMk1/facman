<?php

declare(strict_types=1);

namespace App\Infrastructure\Address;

use App\Domain\Address\AddressEntity;
use App\Domain\Address\AddressRepositoryException;
use App\Domain\Address\AddressRepositoryInterface;
use App\Domain\Common\Exception\AbstractRepositoryException;
use App\Infrastructure\Common\AbstractDbalRepository;
use InvalidArgumentException;
use LogicException;

class DbalAddressRepository extends AbstractDbalRepository implements AddressRepositoryInterface
{
    protected string $tableName = 'addresses';
    protected string $exceptionClass = AddressRepositoryException::class;

    /**
     * @throws AbstractRepositoryException
     * @throws InvalidArgumentException
     * @throws LogicException
     */
    public function store(AddressEntity $addressEntity): void
    {
        $this->storeMappedEntity($addressEntity);
    }

    /**
     * @throws AbstractRepositoryException
     * @throws InvalidArgumentException
     * @throws LogicException
     */
    public function update(AddressEntity $addressEntity): void
    {
        $this->updateMappedEntity($addressEntity);
    }
}
