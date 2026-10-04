<?php

declare(strict_types=1);

namespace DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Boundaries\Fixtures\RoleLocation\App\Gym;

use DaveLiddament\SymfonyArchitecture\Attribute\Controller;

#[Controller] // ERROR DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Boundaries\Fixtures\RoleLocation\App\Gym\BadController|Controller|DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Boundaries\Fixtures\RoleLocation\App\Gym\Controller
final class BadController
{
}
