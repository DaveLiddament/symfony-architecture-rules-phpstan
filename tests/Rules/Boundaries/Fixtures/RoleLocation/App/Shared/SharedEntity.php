<?php

declare(strict_types=1);

namespace DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Boundaries\Fixtures\RoleLocation\App\Shared;

use DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Boundaries\Fixtures\RoleLocation\FakeOrm\Entity;

#[Entity] // ERROR DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Boundaries\Fixtures\RoleLocation\App\Shared\SharedEntity|Entity|DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Boundaries\Fixtures\RoleLocation\App\Shared\Entity
final class SharedEntity
{
}
