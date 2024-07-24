<?php

namespace App\Application\Common\Exception;

use App\Application\Common\CommandInterface;
use Exception;

class CommandHandlerException extends Exception
{
    public static function invalidCommandPassed(CommandInterface $command): self
    {
        return new self(
            sprintf(
                'Invalid class \'%s\' passed into handle method',
                $command::class
            )
        );
    }
}
