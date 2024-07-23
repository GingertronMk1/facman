<?php

declare(strict_types=1);

namespace App\Infrastructure\Site;

use App\Domain\Common\Exception\AbstractRepositoryException;
use App\Domain\Site\SiteEntity;
use App\Domain\Site\SiteRepositoryException;
use App\Domain\Site\SiteRepositoryInterface;
use App\Domain\Site\ValueObject\SiteId;
use App\Infrastructure\Common\AbstractDbalRepository;
use InvalidArgumentException;

class DbalSiteRepository extends AbstractDbalRepository implements SiteRepositoryInterface
{
    protected string $tableName = 'sites';
    protected string $exceptionClass = SiteRepositoryException::class;

    public function generateId(): SiteId
    {
        return SiteId::generate();
    }

    /**
     * @throws AbstractRepositoryException
     * @throws InvalidArgumentException
     */
    public function store(SiteEntity $entity): SiteId
    {
        $this->storeMappedEntity($entity);

        return $entity->id;
    }

    /**
     * @throws AbstractRepositoryException
     * @throws InvalidArgumentException
     */
    public function update(SiteEntity $entity): SiteId
    {
        $this->updateMappedEntity($entity);

        return $entity->id;
    }
}
