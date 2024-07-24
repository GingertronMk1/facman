<?php

declare(strict_types=1);

namespace App\Infrastructure\JobType;

use App\Domain\JobType\JobTypeEntity;
use App\Domain\JobType\JobTypeRepositoryException;
use App\Domain\JobType\JobTypeRepositoryInterface;
use App\Domain\JobType\ValueObject\JobTypeId;
use App\Infrastructure\Common\AbstractDbalRepository;

class DbalJobTypeRepository extends AbstractDbalRepository implements JobTypeRepositoryInterface
{
    public function generateId(): JobTypeId
    {
        return JobTypeId::generate();
    }

    public function store(JobTypeEntity $entity): JobTypeId
    {
        $this->storeMappedEntity($entity);

        return $entity->id;
    }

    public function update(JobTypeEntity $entity): JobTypeId
    {
        $this->updateMappedEntity($entity);

        return $entity->id;
    }

    protected function getTableName(): string
    {
        return 'job_types';
    }

    protected function getExceptionClass(): string
    {
        return JobTypeRepositoryException::class;
    }
}
