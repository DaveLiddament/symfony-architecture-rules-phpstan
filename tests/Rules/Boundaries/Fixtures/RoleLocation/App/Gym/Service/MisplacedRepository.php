<?php

declare(strict_types=1);

namespace DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Boundaries\Fixtures\RoleLocation\App\Gym\Service;

use DaveLiddament\SymfonyArchitecture\Attribute\Repository;

#[Repository] // ERROR DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Boundaries\Fixtures\RoleLocation\App\Gym\Service\MisplacedRepository|Repository|DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Boundaries\Fixtures\RoleLocation\App\Gym\Repository or DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Boundaries\Fixtures\RoleLocation\App\Gym
final class MisplacedRepository
{
}
