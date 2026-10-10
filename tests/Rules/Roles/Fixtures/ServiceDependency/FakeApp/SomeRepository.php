<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\ServiceDependency\FakeApp;

use DaveLiddament\Architecture\Attribute\Repository;

#[Repository]
final readonly class SomeRepository
{
}
