<?php

declare(strict_types=1);

namespace App\Domain\Address;

use App\Domain\Common\AbstractMappedEntity;
use App\Domain\Common\ValueObject\AbstractId;

class AddressEntity extends AbstractMappedEntity
{
    public function __construct(
        public AbstractId $addresseeId,
        public string $addresseeType,
        public AddressTypeEnum $addressType,
        public ?string $line1 = '',
        public ?string $line2 = '',
        public ?string $line3 = '',
        public ?string $postcode = '',
        public ?string $city = '',
        public ?string $country = '',
    ) {}

    public function getIdentifierColumns(): array
    {
        return [
            'addressee_id' => (string) $this->addresseeId,
            'addressee_type' => $this->addresseeType,
        ];
    }
}
