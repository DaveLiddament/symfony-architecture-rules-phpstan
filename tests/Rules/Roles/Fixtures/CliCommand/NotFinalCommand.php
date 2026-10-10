<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\CliCommand;

use DaveLiddament\Architecture\Attribute\CliCommand;

#[CliCommand] // ERROR CLI command DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\CliCommand\NotFinalCommand must be final.
class NotFinalCommand
{
}
