<?php

declare(strict_types=1);

namespace App\Application\Address;

use App\Domain\Address\AddressTypeEnum;
use App\Domain\Common\ValueObject\DateTime;
use JsonSerializable;

readonly class AddressModel
{
    public function __construct(
        public AddressTypeEnum $addressType,
        public string $line1,
        public string $line2,
        public string $line3,
        public string $postcode,
        public string $city,
        public string $country,
        public DateTime $createdAt,
        public DateTime $updatedAt,
        public ?DateTime $deletedAt,
    ) {}
}
