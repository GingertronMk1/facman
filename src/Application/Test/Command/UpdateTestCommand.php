<?php

declare(strict_types=1);

namespace App\Application\Test\Command;

class UpdateTestCommand
{
    public function __construct(
        public \App\Domain\Test\ValueObject\TestId $testId,
    ) {
    }
}
