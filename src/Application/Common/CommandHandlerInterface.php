<?php

namespace App\Application\Common;

use App\Domain\Common\ValueObject\AbstractId;

interface CommandHandlerInterface
{
    public function handle(CommandInterface $command, mixed ...$args): ?AbstractId;
}
