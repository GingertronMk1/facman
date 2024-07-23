<?php

declare(strict_types=1);

namespace App\Infrastructure\JobStatus;

use App\Domain\Common\Exception\AbstractRepositoryException;
use App\Domain\JobStatus\JobStatusEntity;
use App\Domain\JobStatus\JobStatusRepositoryException;
use App\Domain\JobStatus\JobStatusRepositoryInterface;
use App\Domain\JobStatus\ValueObject\JobStatusId;
use App\Infrastructure\Common\AbstractDbalRepository;
use InvalidArgumentException;
use LogicException;

class DbalJobStatusRepository extends AbstractDbalRepository implements JobStatusRepositoryInterface
{
    protected string $tableName = 'job_statuses';
    protected string $exceptionClass = JobStatusRepositoryException::class;

    public function generateId(): JobStatusId
    {
        return JobStatusId::generate();
    }

    /**
     * @throws AbstractRepositoryException
     * @throws InvalidArgumentException
     * @throws LogicException
     */
    public function store(JobStatusEntity $entity): JobStatusId
    {
        $this->storeMappedEntity($entity);

        return $entity->id;
    }

    /**
     * @throws AbstractRepositoryException
     * @throws InvalidArgumentException
     * @throws LogicException
     */
    public function update(JobStatusEntity $entity): JobStatusId
    {
        $this->updateMappedEntity($entity);

        return $entity->id;
    }
}
