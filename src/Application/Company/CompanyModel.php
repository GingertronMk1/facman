<?php

declare(strict_types=1);

namespace App\Application\Company;

readonly class CompanyModel
{
public function __construct(
public \App\Domain\Company\ValueObject\CompanyId $companyId,
) {}
}
