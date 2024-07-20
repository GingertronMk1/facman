<?php

declare(strict_types=1);

namespace App\Domain\Company;

class CompanyEntity
{
public function __construct(
public \App\Domain\Company\ValueObject\CompanyId $companyId,
) {}
}
