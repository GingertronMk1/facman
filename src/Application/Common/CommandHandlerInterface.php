<?php

namespace App\Application\Common;

use App\Application\Common\Exception\CommandHandlerException;
use App\Domain\Common\ValueObject\AbstractId;

interface CommandHandlerInterface
{
    /**
     * @throws CommandHandlerException
     */
    public function handle(CommandInterface $command, mixed ...$args): ?AbstractId;
}
