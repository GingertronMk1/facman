<?php

declare(strict_types=1);

namespace App\Infrastructure\JobType;

use App\Application\JobType\JobTypeFinderException;
use App\Application\JobType\JobTypeFinderInterface;
use App\Application\JobType\JobTypeModel;
use App\Domain\Common\ValueObject\DateTime;
use App\Domain\JobType\ValueObject\JobTypeId;
use App\Infrastructure\Common\AbstractDbalFinder;
use Doctrine\DBAL\Exception;
use Throwable;

class DbalJobTypeFinder extends AbstractDbalFinder implements JobTypeFinderInterface
{
    /**
     * @throws JobTypeFinderException
     * @throws Exception
     */
    public function findById(JobTypeId $id): JobTypeModel
    {
        $qb = $this->getBaseQuery();
        $qb->where('id = :id')
            ->setParameter('id', (string) $id)
        ;

        $result = $qb->fetchAssociative();

        return $this->createFromRow($result);
    }

    /**
     * @throws JobTypeFinderException
     * @throws Exception
     */
    public function all(): array
    {
        $qb = $this->getBaseQuery();

        return array_map(
            fn (array $row) => $this->createFromRow($row),
            $qb->fetchAllAssociative()
        );
    }

    protected function getTableName(): string
    {
        return 'job_types';
    }

    /**
     * @param array<string, mixed>|false $row
     *
     * @throws JobTypeFinderException
     */
    private function createFromRow(array|false $row): JobTypeModel
    {
        if (!$row) {
            throw JobTypeFinderException::errorCreatingModel();
        }

        try {
            return new JobTypeModel(
                id: JobTypeId::fromString($row['id']),
                name: $row['name'],
                description: $row['description'],
                colour: $row['colour'],
                createdAt: DateTime::fromString($row['created_at']),
                updatedAt: DateTime::fromString($row['updated_at']),
                deletedAt: is_string($row['deleted_at']) ? DateTime::fromString($row['deleted_at']) : null,
            );
        } catch (Throwable $e) {
            throw JobTypeFinderException::errorCreatingModel($e);
        }
    }
}
