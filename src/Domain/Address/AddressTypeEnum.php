<?php

namespace App\Domain\Address;

enum AddressTypeEnum: string
{
    case MAIN = 'main';
    case SECONDARY = 'secondary';
    case HOME = 'home';
}
