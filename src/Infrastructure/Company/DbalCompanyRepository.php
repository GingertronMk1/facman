<?php

declare(strict_types=1);

namespace App\Infrastructure\Company;

use App\Domain\Common\Exception\AbstractRepositoryException;
use App\Domain\Company\CompanyEntity;
use App\Domain\Company\CompanyRepositoryException;
use App\Domain\Company\CompanyRepositoryInterface;
use App\Domain\Company\ValueObject\CompanyId;
use App\Infrastructure\Common\AbstractDbalRepository;
use InvalidArgumentException;
use Throwable;

class DbalCompanyRepository extends AbstractDbalRepository implements CompanyRepositoryInterface
{
    protected string $tableName = 'companies';

    public function generateId(): CompanyId
    {
        return CompanyId::generate();
    }

    public function generatePrefix(string $companyName): string
    {
        $qb = $this->connection->createQueryBuilder();
        $qb->select('prefix')->from($this->tableName);

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
        $this->storeMappedEntity($entity);

        return $entity->id;
    }

    /**
     * @throws AbstractRepositoryException
     * @throws InvalidArgumentException
     */
    public function update(CompanyEntity $entity): CompanyId
    {
        $this->updateMappedEntity($entity);

        return $entity->id;
    }
}
