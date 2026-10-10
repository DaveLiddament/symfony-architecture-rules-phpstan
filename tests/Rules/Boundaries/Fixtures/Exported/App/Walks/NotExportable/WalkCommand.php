<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Boundaries\Fixtures\Exported\App\Walks\NotExportable;

use DaveLiddament\Architecture\Attribute\CliCommand;
use DaveLiddament\Architecture\Attribute\Exported;

#[Exported, CliCommand] // ERROR DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Boundaries\Fixtures\Exported\App\Walks\NotExportable\WalkCommand has the cliCommand role, so it cannot be exported.
final class WalkCommand
{
}
