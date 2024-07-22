<?php

declare(strict_types=1);

namespace App\Application\Address\CommandHandler;

use App\Application\Address\Command\StoreAddressCommand;
use App\Domain\Address\AddressEntity;
use App\Domain\Address\AddressRepositoryInterface;
use App\Domain\Common\ValueObject\AbstractId;

readonly class StoreAddressCommandHandler
{
    public function __construct(
        private AddressRepositoryInterface $addressRepositoryInterface,
    ) {}

    public function handle(StoreAddressCommand $command, AbstractId $addressId, string $addresseeType): void
    {
        $addressEntity = new AddressEntity(
            $addressId,
            $addresseeType,
            $command->addressType,
            $command->line1,
            $command->line2,
            $command->line3,
            $command->postcode,
            $command->city,
            $command->country
        );

        $this->addressRepositoryInterface->store($addressEntity);
    }
}
