<?php

namespace App\Domain\Common;

use ReflectionClass;
use ReflectionProperty;

abstract class AbstractMappedEntity
{
    public ?string $createdAt = 'created_at';
    public ?string $updatedAt = 'updated_at';
    public ?string $deletedAt = 'deleted_at';

    /**
     * @return array<string, int|string>
     */
    public function getMappedData(): array
    {
        $reflection = new ReflectionClass($this);
        $thisProperties = array_filter(
            $reflection->getProperties(),
            fn (ReflectionProperty $property) => $property->getDeclaringClass()->name === static::class
        );
        $mappedData = [];
        foreach ($thisProperties as $property) {
            $mappedData[$property->getName()] = $property->getValue($this);
        }

        return $mappedData;
    }

    /**
     * @return array<string, string>
     */
    abstract public function getIdentifierColumns(): array;
}
