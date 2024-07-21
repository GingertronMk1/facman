<?php

declare(strict_types=1);

namespace App\Domain\Site;

use App\Domain\Site\ValueObject\SiteId;

interface SiteRepositoryInterface
{
    public function generateId(): SiteId;

    /**
     * @throws SiteRepositoryException
     */
    public function store(SiteEntity $entity): SiteId;

    /**
     * @throws SiteRepositoryException
     */
    public function update(SiteEntity $entity): SiteId;
}
