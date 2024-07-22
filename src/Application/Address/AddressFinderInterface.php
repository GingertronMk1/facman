<?php

declare(strict_types=1);

namespace App\Application\Address;

use App\Domain\Common\ValueObject\AbstractId;

interface AddressFinderInterface
{
    /**
     * @return array<AddressModel>
     *
     * @throws AddressFinderException
     */
    public function find(
        AbstractId $modelId,
        string $modelClass
    ): array;
}
