<?php

declare(strict_types=1);

namespace App\Domain\Site;

use App\Domain\Site\ValueObject\SiteId;

interface SiteRepositoryInterface
{
    public function generateId(): SiteId;

    public function store(SiteEntity $entity): SiteId;

    public function update(SiteEntity $entity): SiteId;
}
