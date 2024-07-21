<?php

declare(strict_types=1);

namespace App\Infrastructure\Company;

use App\Application\Common\ClockInterface;
use App\Domain\Common\Exception\AbstractRepositoryException;
use App\Domain\Company\CompanyEntity;
use App\Domain\Company\CompanyRepositoryException;
use App\Domain\Company\CompanyRepositoryInterface;
use App\Domain\Company\ValueObject\CompanyId;
use App\Infrastructure\Common\AbstractDbalRepository;
use Doctrine\DBAL\Connection;
use InvalidArgumentException;
use Throwable;

readonly class DbalCompanyRepository extends AbstractDbalRepository implements CompanyRepositoryInterface
{
    private const TABLE = 'companies';

    public function __construct(
        private Connection $connection,
        private ClockInterface $clockInterface,
    ) {}

    public function generateId(): CompanyId
    {
        return CompanyId::generate();
    }

    public function generatePrefix(string $companyName): string
    {
        $qb = $this->connection->createQueryBuilder();
        $qb->select('prefix')->from(self::TABLE);

        try {
            $companyPrefixes = $qb->fetchFirstColumn();
        } catch (Throwable $e) {
            throw CompanyRepositoryException::errorGettingPrefixes($e);
        }

        $companyNameWords = preg_split('/\s+/', $companyName);

        if (!$companyNameWords) {
            throw CompanyRepositoryException::errorGeneratingPrefix();
        }
        $numLetters = 1;
        $prefix = '';
        do {
            $oldPrefix = $prefix;
            $prefix = strtoupper(
                implode(
                    separator: '',
                    array: array_map(
                        fn (string $word) => substr($word, 0, $numLetters),
                        $companyNameWords
                    )
                )
            );

            if ($prefix === $oldPrefix) {
                throw CompanyRepositoryException::errorGeneratingPrefix();
            }
            ++$numLetters;
        } while (!empty($prefix) && in_array($prefix, $companyPrefixes));

        return $prefix;
    }

    /**
     * @throws AbstractRepositoryException
     * @throws InvalidArgumentException
     */
    public function store(CompanyEntity $entity): CompanyId
    {
        $qb = $this->connection->createQueryBuilder();
        $qb->insert(self::TABLE)
            ->values([
                'id' => ':id',
                'name' => ':name',
                'description' => ':description',
                'prefix' => ':prefix',
                'created_at' => ':now',
                'updated_at' => ':now',
            ])
            ->setParameters([
                'id' => (string) $entity->id,
                'name' => $entity->name,
                'description' => $entity->description,
                'prefix' => $entity->prefix,
                'now' => (string) $this->clockInterface->getTime(),
            ])
        ;

        $this->executeAndCheck($qb, CompanyRepositoryException::class);

        return $entity->id;
    }

    /**
     * @throws AbstractRepositoryException
     * @throws InvalidArgumentException
     */
    public function update(CompanyEntity $entity): CompanyId
    {
        $qb = $this->connection->createQueryBuilder();
        $qb
            ->update(self::TABLE)
            ->where('id = :id')
            ->set('name', ':name')
            ->set('description', ':description')
            ->set('updated_at', ':now')
            ->setParameters([
                'id' => (string) $entity->id,
                'name' => $entity->name,
                'description' => $entity->description,
                'now' => (string) $this->clockInterface->getTime(),
            ])
        ;

        $this->executeAndCheck($qb, CompanyRepositoryException::class);

        return $entity->id;
    }
}
