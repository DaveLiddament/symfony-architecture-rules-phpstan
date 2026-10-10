<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\ValueObjectProperties;

enum Status: string
{
    case Active = 'active';
    case Retired = 'retired';
}
