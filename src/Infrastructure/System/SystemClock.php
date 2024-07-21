<?php

namespace App\Infrastructure\System;

use App\Application\Common\ClockInterface;
use App\Domain\Common\ValueObject\DateTime;
use DateTimeImmutable;
use InvalidArgumentException;

class SystemClock implements ClockInterface
{
    /**
     * @throws InvalidArgumentException
     */
    public function getTime(?string $modifier = null): DateTime
    {
        $initialDateTime = new DateTimeImmutable();
        if ($modifier) {
            $initialDateTime = $initialDateTime->modify($modifier);
        }

        return DateTime::fromDateTimeInterface($initialDateTime);
    }
}
