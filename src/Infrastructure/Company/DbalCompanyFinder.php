<?php

declare(strict_types=1);

namespace App\Infrastructure\Company;

readonly class DbalCompanyFinder implements \App\Application\Company\CompanyFinderInterface
{
public function __construct(
private \Doctrine\DBAL\Connection $connection,
) {}
}
