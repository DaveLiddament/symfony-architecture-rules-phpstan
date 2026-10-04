<?php

declare(strict_types=1);

namespace DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Roles\Fixtures\ViewModelProperties;

enum Status: string
{
    case Open = 'open';
    case Done = 'done';
}
