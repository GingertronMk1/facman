<?php

namespace App\Domain\Common;

use BackedEnum;
use LogicException;
use ReflectionClass;
use ReflectionProperty;

abstract class AbstractMappedEntity
{
    public ?string $createdAt = 'created_at';
    public ?string $updatedAt = 'updated_at';
    public ?string $deletedAt = 'deleted_at';

    /**
     * @return array<string, int|string>
     *
     * @throws LogicException
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
            if (!$replacedName) {
                throw new LogicException("{$propertyName} could not be tableised.");
            }
            $tabledName = strtolower($replacedName);

            $propertyValue = $property->getValue($this);
            if ($propertyValue instanceof BackedEnum) {
                $mappedData[$tabledName] = $propertyValue->value;
            } else {
                $mappedData[$tabledName] = $propertyValue;
            }
        }

        return $mappedData;
    }

    /**
     * @return array<string, string>
     */
    abstract public function getIdentifierColumns(): array;
}
