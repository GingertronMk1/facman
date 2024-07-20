<?php

declare(strict_types=1);

namespace App\Infrastructure\Company;

readonly class DbalCompanyRepository implements \App\Domain\Company\CompanyRepositoryInterface
{
public function __construct(
private \Doctrine\DBAL\Connection $connection,
private \App\Application\Common\ClockInterface $clockInterface,
) {}
}
