<?php

declare(strict_types=1);

namespace DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Roles\Fixtures\ValueObjectProperties;

enum Status: string
{
    case Active = 'active';
    case Retired = 'retired';
}
