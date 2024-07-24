<?php

declare(strict_types=1);

namespace App\Application\Site\Command;

use App\Application\Address\Command\StoreAddressCommand;
use App\Application\Common\CommandInterface;
use App\Application\Company\CompanyModel;

class CreateSiteCommand implements CommandInterface
{
    public function __construct(
        public string $name = '',
        public string $description = '',
        public ?StoreAddressCommand $address = null,
        public ?CompanyModel $company = null
    ) {}
}
