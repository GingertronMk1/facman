<?php

declare(strict_types=1);

namespace App\Application\Site;

use App\Domain\Company\ValueObject\CompanyId;
use App\Domain\Site\ValueObject\SiteId;

interface SiteFinderInterface
{
    public function findById(SiteId $id): SiteModel;

    /**
     * @return array<SiteModel>
     */
    public function all(): array;

    /**
     * @return array<SiteModel>
     */
    public function allForCompany(CompanyId $companyId): array;
}
