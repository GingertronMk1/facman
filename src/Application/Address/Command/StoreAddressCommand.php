<?php

declare(strict_types=1);

namespace App\Application\Address\Command;

use App\Domain\Address\AddressEntity;
use App\Domain\Address\AddressTypeEnum;
use App\Domain\Common\ValueObject\AbstractId;

class StoreAddressCommand
{
    public function __construct(
        public AddressTypeEnum $addressType = AddressTypeEnum::MAIN,
        public string $line1 = '',
        public string $line2 = '',
        public string $line3 = '',
        public string $postcode = '',
        public string $city = '',
        public string $country = '',
    ) {}

    public function toEntity(AbstractId $addresseeId, string $addresseeType): AddressEntity
    {
        return new AddressEntity(
            addresseeId: $addresseeId,
            addresseeType: $addresseeType,
            addressType: $this->addressType,
            line1: $this->line1,
            line2: $this->line2,
            line3: $this->line3,
            postcode: $this->postcode,
            city: $this->city,
            country: $this->country,
        );
    }
}
