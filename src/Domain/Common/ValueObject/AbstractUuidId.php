<?php

declare(strict_types=1);

namespace App\Domain\Common\ValueObject;

use Symfony\Component\Uid\UuidV7;

abstract class AbstractUuidId extends AbstractId
{
    final protected function __construct(
        private readonly UuidV7 $uuid
    ) {
    }

    /**
     * Get a string representation of this object.
     *
     * @return string the ID
     */
    public function __toString(): string
    {
        return $this->uuid->toString();
    }

    /**
     * Check if a string represents a valid UUID.
     *
     * @param string $uuid the string to check
     *
     * @return bool true if the string is valid
     */
    public static function isValid(string $uuid): bool
    {
        $valid = true;

        try {
            static::fromString($uuid);
        } catch (\InvalidArgumentException) {
            $valid = false;
        }

        return $valid;
    }

    /**
     * Generate a random UUID instance.
     */
    public static function generate(): static
    {
        return new static(new UuidV7());
    }

    /**
     * Create an instance from a string.
     *
     * @param string $uuid the ID
     *
     * @throws \InvalidArgumentException thrown if the string is not a valid UUID
     */
    public static function fromString(string $uuid): static
    {
        return new static(UuidV7::fromString($uuid));
    }

    /**
     * Test for equality between IDs.
     *
     * @param AbstractUuidId $uuid the ID to compare to
     *
     * @return bool true if the two objects are equal
     */
    public function equals(AbstractUuidId $uuid): bool
    {
        return (string) $uuid === (string) $this;
    }
}
