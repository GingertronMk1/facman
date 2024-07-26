<?php

namespace App\Application\Common;

use App\Application\Common\Exception\CommandHandlerException;
use App\Domain\Common\ValueObject\AbstractId;

/**
 * @template T
 */
interface CommandHandlerInterface
{
    /**
     * @param T $command
     *
     * @throws CommandHandlerException
     */
    public function handle(mixed $command, mixed ...$args): ?AbstractId;
}
