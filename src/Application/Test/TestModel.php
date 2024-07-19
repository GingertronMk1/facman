<?php

declare(strict_types=1);

namespace App\Application\Test;

readonly class TestModel
{
    public function __construct(
        public \App\Domain\Test\ValueObject\TestId $testId,
    ) {
    }
}
