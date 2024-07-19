<?php

declare(strict_types=1);

namespace App\Domain\Test;

class TestEntity
{
    public function __construct(
        public ValueObject\TestId $testId,
    ) {
    }
}
