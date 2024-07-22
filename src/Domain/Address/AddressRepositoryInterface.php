<?php

declare(strict_types=1);

namespace App\Domain\Address;

interface AddressRepositoryInterface
{
    public function store(AddressEntity $addressEntity): void;

    public function update(AddressEntity $addressEntity): void;
}
