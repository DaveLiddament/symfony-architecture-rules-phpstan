<?php

declare(strict_types=1);

namespace DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Roles\Fixtures\ServiceDependency\FakeApp;

use DaveLiddament\SymfonyArchitecture\Attribute\Repository;

#[Repository]
final readonly class SomeRepository
{
}
