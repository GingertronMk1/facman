<?php

declare(strict_types=1);

namespace App\Application\Test\CommandHandler;

class UpdateTestCommandHandler
{
    public function __construct(
        private \App\Domain\Test\TestRepositoryInterface $testRepositoryInterface,
    ) {
    }
}
