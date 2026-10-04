<?php

declare(strict_types=1);

namespace DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Roles\Fixtures\Repository;

use DaveLiddament\SymfonyArchitecture\Attribute\Repository;

#[Repository] // ERROR Repository DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Roles\Fixtures\Repository\NotReadonlyRepository must be final and readonly.
final class NotReadonlyRepository
{
}
