<?php

declare(strict_types=1);

namespace App\Application\Address;

use App\Domain\Address\AddressTypeEnum;
use App\Domain\Common\ValueObject\DateTime;
use JsonSerializable;

readonly class AddressModel implements JsonSerializable
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

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return [
            'addressType' => $this->addressType->value,
            'line1' => $this->line1,
            'line2' => $this->line2,
            'line3' => $this->line3,
            'postcode' => $this->postcode,
            'city' => $this->city,
            'country' => $this->country,
            'createdAt' => $this->createdAt,
            'updatedAt' => $this->updatedAt,
            'deletedAt' => $this->deletedAt,
        ];
    }
}
