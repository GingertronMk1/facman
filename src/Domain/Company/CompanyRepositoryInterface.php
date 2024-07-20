<?php

declare(strict_types=1);

namespace App\Domain\Company;

use App\Domain\Company\ValueObject\CompanyId;

interface CompanyRepositoryInterface
{
    public function generateId(): CompanyId;

    public function store(CompanyEntity $entity): CompanyId;

    public function update(CompanyEntity $entity): CompanyId;
}
