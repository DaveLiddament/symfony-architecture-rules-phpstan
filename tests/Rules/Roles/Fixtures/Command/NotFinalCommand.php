<?php

declare(strict_types=1);

namespace DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Roles\Fixtures\Command;

use DaveLiddament\SymfonyArchitecture\Attribute\Command;

#[Command] // ERROR Command DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Roles\Fixtures\Command\NotFinalCommand must be final.
class NotFinalCommand
{
}
