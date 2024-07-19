<?php

namespace App\Application\Common;

use App\Domain\Common\ValueObject\DateTime;

interface ClockInterface
{
    public function getTime(?string $modifier = null): DateTime;
}
