<?php

declare(strict_types=1);

namespace DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Roles\Fixtures\ConfigProviderProperties;

enum Level: string
{
    case Low = 'low';
    case High = 'high';
}
