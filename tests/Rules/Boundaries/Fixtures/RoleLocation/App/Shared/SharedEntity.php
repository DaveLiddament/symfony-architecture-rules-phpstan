<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Boundaries\Fixtures\RoleLocation\App\Shared;

use DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Boundaries\Fixtures\RoleLocation\FakeOrm\Entity;

#[Entity] // ERROR DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Boundaries\Fixtures\RoleLocation\App\Shared\SharedEntity|entity|DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Boundaries\Fixtures\RoleLocation\App\Shared\Entity
final class SharedEntity
{
}
