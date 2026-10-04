<?php

declare(strict_types=1);

namespace DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Boundaries\Fixtures\RoleLocation\App\Gym\Service;

use DaveLiddament\SymfonyArchitecture\Attribute\Command;

#[Command] // ERROR DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Boundaries\Fixtures\RoleLocation\App\Gym\Service\MisplacedCommand|Command|DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Boundaries\Fixtures\RoleLocation\App\Gym\Command
final class MisplacedCommand
{
}
