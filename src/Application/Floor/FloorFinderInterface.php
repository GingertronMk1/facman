<?php

declare(strict_types=1);

namespace App\Application\Floor;

use App\Application\Common\Exception\AbstractFinderException;
use App\Domain\Floor\ValueObject\FloorId;

interface FloorFinderInterface
{
    /**
     * @throws AbstractFinderException
     */
    public function findById(FloorId $id): FloorModel;

    /**
     * @return array<FloorModel>
     *
     * @throws AbstractFinderException
     */
    public function all(): array;
}
