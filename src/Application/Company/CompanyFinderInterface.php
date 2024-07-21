<?php

declare(strict_types=1);

namespace App\Application\Company;

use App\Domain\Company\ValueObject\CompanyId;

interface CompanyFinderInterface
{
    /**
     * @throws CompanyFinderException
     */
    public function findById(CompanyId $id): CompanyModel;

    /**
     * @return array<CompanyModel>
     *
     * @throws CompanyFinderException
     */
    public function all(): array;
}
