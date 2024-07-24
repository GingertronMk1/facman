<?php

declare(strict_types=1);

namespace App\Application\Address\Command;

use App\Domain\Address\AddressTypeEnum;

class StoreAddressCommand
{
    public function __construct(
        public AddressTypeEnum $addressType = AddressTypeEnum::MAIN,
        public ?string $line1 = '',
        public ?string $line2 = '',
        public ?string $line3 = '',
        public ?string $postcode = '',
        public ?string $city = '',
        public ?string $country = '',
    ) {}
}
