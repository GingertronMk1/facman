<?php

declare(strict_types=1);

namespace App\Application\Company;

use App\Domain\Company\ValueObject\CompanyId;

interface CompanyFinderInterface
{
    public function findById(CompanyId $id): CompanyModel;

    /**
     * @return array<CompanyModel>
     */
    public function all(): array;
}
