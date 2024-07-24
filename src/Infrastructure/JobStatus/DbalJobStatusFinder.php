<?php

declare(strict_types=1);

namespace App\Infrastructure\JobStatus;

use App\Application\JobStatus\JobStatusFinderException;
use App\Application\JobStatus\JobStatusFinderInterface;
use App\Application\JobStatus\JobStatusModel;
use App\Domain\Common\ValueObject\DateTime;
use App\Domain\JobStatus\ValueObject\JobStatusId;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Query\QueryBuilder;
use InvalidArgumentException;

class DbalJobStatusFinder implements JobStatusFinderInterface
{
    public function __construct(
        private Connection $connection,
    ) {}

    /**
     * @throws JobStatusFinderException
     * @throws Exception
     */
    public function findById(JobStatusId $id): JobStatusModel
    {
        $qb = $this->getBaseQuery();
        $qb->where('id = :id')
            ->setParameter('id', (string) $id)
        ;
        $result = $qb->fetchAssociative();

        return $this->createFromRow($result);
    }

    /**
     * @throws JobStatusFinderException
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

    private function getBaseQuery(): QueryBuilder
    {
        $qb = $this->connection->createQueryBuilder();
        $qb->select('*')->from('job_statuses');

        return $qb;
    }

    /**
     * @param array<string, mixed>|false $row
     *
     * @throws JobStatusFinderException
     */
    private function createFromRow(array|false $row): JobStatusModel
    {
        if (!is_array($row)) {
            throw JobStatusFinderException::errorCreatingModel();
        }

        try {
            return new JobStatusModel(
                id: JobStatusId::fromString($row['id']),
                name: $row['name'],
                description: $row['description'],
                colour: $row['colour'],
                createdAt: DateTime::fromString($row['created_at']),
                updatedAt: DateTime::fromString($row['updated_at']),
                deletedAt: is_string($row['deleted_at']) ? DateTime::fromString($row['deleted_at']) : null,
            );
        } catch (InvalidArgumentException $e) {
            throw JobStatusFinderException::errorCreatingModel($e);
        }
    }
}
