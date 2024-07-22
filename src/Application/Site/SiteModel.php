<?php

declare(strict_types=1);

namespace App\Application\Site;

use App\Application\Address\AddressModel;
use App\Application\Company\CompanyModel;
use App\Domain\Common\ValueObject\DateTime;
use App\Domain\Site\ValueObject\SiteId;

readonly class SiteModel
{
    /**
     * @param array<AddressModel> $addresses
     */
    public function __construct(
        public SiteId $id,
        public string $name,
        public string $description,
        public CompanyModel $company,
        public array $addresses,
        public DateTime $createdAt,
        public DateTime $updatedAt,
        public ?DateTime $deletedAt,
    ) {}
}
