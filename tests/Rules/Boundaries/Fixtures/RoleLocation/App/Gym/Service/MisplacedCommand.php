<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Boundaries\Fixtures\RoleLocation\App\Gym\Service;

use DaveLiddament\Architecture\Attribute\CliCommand;

#[CliCommand] // ERROR DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Boundaries\Fixtures\RoleLocation\App\Gym\Service\MisplacedCommand|CliCommand|DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Boundaries\Fixtures\RoleLocation\App\Gym\CliCommand
final class MisplacedCommand
{
}
