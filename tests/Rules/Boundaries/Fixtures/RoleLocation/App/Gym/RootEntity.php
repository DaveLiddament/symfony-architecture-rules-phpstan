<?php

declare(strict_types=1);

namespace DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Boundaries\Fixtures\RoleLocation\App\Gym;

use DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Boundaries\Fixtures\RoleLocation\FakeOrm\Entity;

#[Entity] // ERROR DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Boundaries\Fixtures\RoleLocation\App\Gym\RootEntity|Entity|DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Boundaries\Fixtures\RoleLocation\App\Gym\Entity
final class RootEntity
{
}
