<?php

declare(strict_types=1);

namespace App\Application\Address\CommandHandler;

use App\Application\Address\Command\StoreAddressCommand;
use App\Application\Common\CommandHandlerInterface;
use App\Application\Common\Exception\CommandHandlerException;
use App\Domain\Address\AddressEntity;
use App\Domain\Address\AddressRepositoryInterface;
use App\Domain\Common\ValueObject\AbstractId;

/**
 * @implements CommandHandlerInterface<StoreAddressCommand>
 */
readonly class StoreAddressCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private AddressRepositoryInterface $addressRepositoryInterface,
    ) {}

    public function handle(mixed $command, mixed ...$args): null
    {
        $addresseeId = false;
        $addresseeType = false;
        // Getting addressId
        foreach ($args as $arg) {
            if ($arg instanceof AbstractId) {
                $addresseeId = $arg;

                break;
            }
        }
        foreach ($args as $arg) {
            if (is_string($arg) && class_exists($arg)) {
                $addresseeType = $arg;

                break;
            }
        }

        if (!($addresseeId instanceof AbstractId && is_string($addresseeType))) {
            throw new CommandHandlerException('No type or ID given');
        }

        $addressEntity = new AddressEntity(
            $addresseeId,
            $addresseeType,
            $command->addressType,
            $command->line1,
            $command->line2,
            $command->line3,
            $command->postcode,
            $command->city,
            $command->country
        );

        $this->addressRepositoryInterface->store($addressEntity);

        return null;
    }
}
