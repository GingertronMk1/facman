<?php

namespace App\Domain\Common;

use BackedEnum;
use ReflectionClass;
use ReflectionProperty;
use Stringable;

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
            $propertyName = $property->getName();
            $replacedName = preg_replace('/([a-z])([A-Z])/', '$1_$2', $propertyName);
            if (!is_string($replacedName)) {
                $replacedName = $propertyName;
            }
            $tabledName = strtolower($replacedName);

            $propertyValue = $property->getValue($this);
            $mappedData[$tabledName] = match (true) {
                ($propertyValue instanceof BackedEnum) => $propertyValue->value,
                ($propertyValue instanceof Stringable) => (string) $propertyValue,
                default => $propertyValue,
            };
        }

        return $mappedData;
    }

    /**
     * @return array<string, string>
     */
    abstract public function getIdentifierColumns(): array;
}
