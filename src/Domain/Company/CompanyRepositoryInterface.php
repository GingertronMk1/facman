<?php

declare(strict_types=1);

namespace App\Domain\Company;

use App\Domain\Company\ValueObject\CompanyId;

interface CompanyRepositoryInterface
{
    public function generateId(): CompanyId;

    /**
     * @throws CompanyRepositoryException
     */
    public function generatePrefix(string $companyName): string;

    /**
     * @throws CompanyRepositoryException
     */
    public function store(CompanyEntity $entity): CompanyId;

    /**
     * @throws CompanyRepositoryException
     */
    public function update(CompanyEntity $entity): CompanyId;
}
