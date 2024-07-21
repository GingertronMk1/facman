<?php

declare(strict_types=1);

namespace App\Application\Company;

use App\Domain\Common\ValueObject\DateTime;
use App\Domain\Company\ValueObject\CompanyId;

readonly class CompanyModel
{
    public function __construct(
        public CompanyId $id,
        public string $name,
        public string $description,
        public DateTime $createdAt,
        public DateTime $updatedAt,
        public ?DateTime $deletedAt,
    ) {}
}
