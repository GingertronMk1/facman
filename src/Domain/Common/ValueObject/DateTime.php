<?php

namespace App\Domain\Common\ValueObject;

use DateTimeImmutable;
use DateTimeInterface;

class DateTime implements \Stringable, \JsonSerializable
{
    public const FORMAT_SECONDS = 'Y-m-d H:i:s';
    public const FORMAT_MILLISECONDS = 'Y-m-d H:i:s.v';
    public const FORMAT_MICROSECONDS = 'Y-m-d H:i:s.u';

    /** @var string The underlying date. */
    private readonly string $date;

    private function __construct(
        string $date
    ) {
        if (!\DateTimeImmutable::createFromFormat(self::FORMAT_MICROSECONDS, $date)) {
            throw new \InvalidArgumentException('Invalid date provided: '.$date.'. Format: '.self::FORMAT_MICROSECONDS.'.');
        }

        $this->date = $date;
    }

    /**
     * Get a string representation of this object.
     *
     * @return string the date in a string format
     */
    public function __toString(): string
    {
        return $this->format(self::FORMAT_MICROSECONDS);
    }

    /**
     * Create an instance from a string.
     *
     * @param string $dateString the date string
     */
    public static function fromString(string $dateString): self
    {
        $dateFormat = match (true) {
            false !== \DateTimeImmutable::createFromFormat(self::FORMAT_MICROSECONDS, $dateString) => self::FORMAT_MICROSECONDS,
            false !== \DateTimeImmutable::createFromFormat(self::FORMAT_MILLISECONDS, $dateString) => self::FORMAT_MILLISECONDS,
            false !== \DateTimeImmutable::createFromFormat(self::FORMAT_SECONDS, $dateString) => self::FORMAT_SECONDS,
            default => throw new \InvalidArgumentException(sprintf('Invalid date provided: %s. Format: %s.', $dateString, self::FORMAT_MICROSECONDS)),
        };

        $dateTimeImmutable = \DateTimeImmutable::createFromFormat($dateFormat, $dateString);

        if (!$dateTimeImmutable) {
            throw new \InvalidArgumentException("Invalid date string `{$dateString}`.");
        }

        return new self($dateTimeImmutable->format(self::FORMAT_MICROSECONDS));
    }

    /**
     * Create an instance from a DateTimeInterface.
     *
     * @param \DateTimeInterface $dateTime the date
     */
    public static function fromDateTimeInterface(\DateTimeInterface $dateTime): self
    {
        $dateString = $dateTime->format(self::FORMAT_MICROSECONDS);

        return new self($dateString);
    }

    /**
     * Create a DateTime object from a timestamp in seconds.
     */
    public static function fromTimestamp(int $timestamp): self
    {
        $length = strlen((string) $timestamp);

        switch ($length) {
            case $length <= 10: // seconds
                $immutable = \DateTimeImmutable::createFromFormat('U', (string) $timestamp);

                break;

            case 13: // milliseconds
                $seconds = floor($timestamp / 1000);
                $fractionalMilliseconds = $timestamp - ($seconds * 1000);

                $immutable = \DateTimeImmutable::createFromFormat('U.v', "{$seconds}.{$fractionalMilliseconds}");

                break;

            case 16: // microseconds
                $seconds = floor($timestamp / 1000000);
                $fractionalMicroseconds = $timestamp - ($seconds * 1000000);

                $immutable = \DateTimeImmutable::createFromFormat('U.u', "{$seconds}.{$fractionalMicroseconds}");

                break;

            default:
                throw new \InvalidArgumentException('Invalid integer format');
        }

        if (!$immutable) {
            throw new \InvalidArgumentException("Invalid timestamp `{$timestamp}`.");
        }

        return self::fromDateTimeInterface($immutable);
    }

    /**
     * Format the object as a string, using the PHP DateTime formatting options.
     */
    public function format(string $formatString): string
    {
        $immutable = $this->toDateTimeImmutable();

        return $immutable->format($formatString);
    }

    /**
     * Get a DateTimeImmutable version of this object.
     *
     * @return \DateTimeImmutable the date in a DateTimeImmutable format
     */
    public function toDateTimeImmutable(): \DateTimeImmutable
    {
        $returnVal = \DateTimeImmutable::createFromFormat(self::FORMAT_MICROSECONDS, $this->date);
        if (!$returnVal) {
            throw new \Exception("Invalid date {$this->date}");
        }

        return $returnVal;
    }

    /**
     * Test for equality between dates.
     *
     * @param DateTime $dateTime the date to compare to
     *
     * @return bool true if the two objects are equal
     */
    public function equals(DateTime $dateTime): bool
    {
        return $dateTime->date === $this->date;
    }

    /**
     * Determine if this date occurs before another date.
     */
    public function isBefore(DateTime $other): bool
    {
        return $this->toTimestamp() < $other->toTimestamp();
    }

    /**
     * Determine if this date occurs after another date.
     */
    public function isAfter(DateTime $other): bool
    {
        return $this->toTimestamp() > $other->toTimestamp();
    }

    /**
     * Get a timestamp representation of the date.
     */
    public function toTimestamp(): int
    {
        return (int) $this->toDateTimeImmutable()
            ->format('U')
        ;
    }

    /**
     * Get a microsecond timestamp representation of the date.
     */
    public function toMicrosecondTimestamp(): int
    {
        return (int) $this->toDateTimeImmutable()
            ->format('Uu')
        ;
    }

    public function jsonSerialize(): mixed
    {
        return (string) $this;
    }
}
