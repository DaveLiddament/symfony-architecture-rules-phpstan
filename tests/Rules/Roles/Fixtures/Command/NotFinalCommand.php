<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\Command;

use DaveLiddament\SymfonyArchitecture\Attribute\Command;

#[Command] // ERROR Command DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\Command\NotFinalCommand must be final.
class NotFinalCommand
{
}
