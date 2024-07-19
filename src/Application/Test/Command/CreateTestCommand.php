<?php

declare(strict_types=1);

namespace App\Application\Test\Command;

class CreateTestCommand
{
    public function __construct(
        public \App\Domain\Test\ValueObject\TestId $testId,
    ) {
    }
}
